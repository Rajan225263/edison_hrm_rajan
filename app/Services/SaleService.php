<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Repositories\Contracts\SaleRepositoryInterface;
use App\Repositories\Contracts\SaleItemRepositoryInterface;
use App\Repositories\Contracts\NoteRepositoryInterface;
use App\Repositories\Contracts\ProductAvailabilityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleService
{
    protected $saleRepo;
    protected $itemRepo;
    protected $noteRepo;
    protected $availabilityRepo;

    public function __construct(
        SaleRepositoryInterface $saleRepo,
        SaleItemRepositoryInterface $itemRepo,
        NoteRepositoryInterface $noteRepo,
        ProductAvailabilityRepositoryInterface $availabilityRepo
    ) {
        $this->saleRepo = $saleRepo;
        $this->itemRepo = $itemRepo;
        $this->noteRepo = $noteRepo;
        $this->availabilityRepo = $availabilityRepo;
    }

    /**
     * All sale lists with filters
     */
    public function getAllSales(Request $request)
    {
        $query = $this->saleRepo->queryWithRelations(['user', 'items.product']);

        if ($request->filled('customer')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%');
            });
        }

        if ($request->filled('product')) {
            $query->whereHas('items.product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->product . '%');
            });
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('sale_date', [$request->from, $request->to]);
        }

        return $query->latest()->paginate(15);
    }

    /**
     * Sale by ID
     */
    public function getSaleById(int $id): Sale
    {
        return $this->saleRepo->findById($id, ['user', 'items.product', 'notes.user']);
    }

    /**
     * Sale with items (including trashed)
     */
    public function findSaleWithItems(int $id): Sale
    {
        return $this->saleRepo->findById($id, [
            'user',
            'itemsWithTrashed.product',
        ]);
    }

    /**
     * Get paginated trashed sales
     */
    public function getTrashedSales(int $perPage = 15)
    {
        return $this->saleRepo->getTrashed($perPage);
    }


    /**
     * Sale delete (soft delete)
     */
    /**
     * Sale delete (soft delete) and stock restore
     */
    public function deleteSale(int $id): bool
    {
        $sale = $this->saleRepo->findById($id);

        // Restore stock of sale items
        foreach ($sale->items as $item) {
            $this->availabilityRepo->addStock($item->product_id, $item->quantity);
        }

        // Delete Sale soft
        return $this->saleRepo->delete($sale);
    }


    /**
     * Sale restore (from trash) and stock adjustment
     */
    public function restoreSale(int $id): bool
    {
        $sale = $this->saleRepo->findTrashed($id);

        // Restore Sale
        $restored = $this->saleRepo->restore($sale);

        if ($restored) {
            // Reduce stock according to the quantity of each SaleItem
            foreach ($sale->items as $item) {
                $this->availabilityRepo->subtractStock($item->product_id, $item->quantity);
            }
        }

        return $restored;
    }


    /**
     * Sale Item soft delete এবং stock adjust
     */
    public function deleteSaleItem(int $id): bool
    {
        $item = $this->itemRepo->find($id);

        // SaleItem soft delete
        $deleted = $this->itemRepo->delete($item);

        if ($deleted) {
            // Stock increases again
            $this->availabilityRepo->addStock($item->product_id, $item->quantity);
        }

        return $deleted;
    }

    /**
     * Sale Item restore এবং stock adjust
     */
    public function restoreSaleItem(int $id)
    {
        // Find SaleItem with soft deleted
        $item = $this->itemRepo->findWithTrashed($id);

        if (!$item->trashed()) {
            throw new \Exception('This item is not deleted.');
        }

        // Restore item
        $restoredItem = $this->itemRepo->restore($id);

        // Reduce stock again
        $this->availabilityRepo->subtractStock($item->product_id, $item->quantity);

        return $restoredItem;
    }

    /**
     * Create Sale with items
     */
    public function createSale(array $saleData, array $itemsData, ?string $notes = null): Sale
    {
        return DB::transaction(function () use ($saleData, $itemsData, $notes) {
            $grandTotal = calculateSaleTotal($itemsData);
            $saleData['grand_total'] = $grandTotal;
            $saleData['sale_date'] = now()->toDateString();

            $sale = $this->saleRepo->create($saleData);

            foreach ($itemsData as $item) {

                $availability = $this->availabilityRepo->findByProductId($item['product_id']);
                $availableStock = $availability?->stock ?? 0;

                if ($item['quantity'] > $availableStock) {
                    throw new \Exception("Quantity for product ID {$item['product_id']} exceeds available stock ({$availableStock})");
                }


                $lineTotal = ($item['price'] * $item['quantity']) - ($item['discount'] ?? 0);

                $this->itemRepo->create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'discount'   => $item['discount'] ?? 0,
                    'line_total' => $lineTotal,
                ]);

                // Stock adjust
                $this->availabilityRepo->subtractStock($item['product_id'], $item['quantity']);
            }

            // Optional notes
            if ($notes) {
                $this->noteRepo->createForModel($sale, $notes, auth()->id() ?? 1);
            }

            return $sale;
        });
    }

    /**
     * Update Sale with items and notes
     */
    public function updateSale(Sale $sale, array $saleData, array $itemsData, ?string $notes = null): Sale
    {
        return DB::transaction(function () use ($sale, $saleData, $itemsData, $notes) {
            $grandTotal = calculateSaleTotal($itemsData);
            $saleData['grand_total'] = $grandTotal;
            $saleData['sale_date'] = $saleData['sale_date'] ?? $sale->sale_date;

            $this->saleRepo->update($sale, $saleData);

            $sale->items()->delete();

            // Revert previous stock
            foreach ($sale->items as $oldItem) {
                $this->availabilityRepo->addStock($oldItem->product_id, $oldItem->quantity);
            }

            foreach ($itemsData as $item) {
                $lineTotal = ($item['price'] * $item['quantity']) - ($item['discount'] ?? 0);

                $this->itemRepo->create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'discount'   => $item['discount'] ?? 0,
                    'line_total' => $lineTotal,
                ]);

                // Stock adjust
                $this->availabilityRepo->subtractStock($item['product_id'], $item['quantity']);
            }

            if ($notes) {
                $this->noteRepo->create($sale, $notes, auth()->id() ?? 1);
            }

            return $sale;
        });
    }
}
