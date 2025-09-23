<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\SaleItem;
use Illuminate\Http\Request;
use App\Services\SaleService;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\SaleItemRepositoryInterface;

class SaleItemController extends Controller
{

    protected $service;
    protected SaleItemRepositoryInterface $itemRepo;

    public function __construct(SaleService $service, SaleItemRepositoryInterface $itemRepo)
    {
        $this->service = $service;
        $this->itemRepo = $itemRepo;
    }

    /**
     * Soft delete a sale item.
     */
    public function destroy(int $id)
    {
        try {
            $item = $this->itemRepo->find($id);

            if ($item->trashed()) {
                return back()->with('error', 'This item is already deleted.');
            }

            $this->itemRepo->delete($item);

            return back()->with('success', 'Sale item deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Sale Item Destroy Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete sale item.');
        }
    }

    /**
     * Restore a soft deleted sale item.
     */
    public function restore(int $id)
    {
        try {
            $this->service->restoreSaleItem($id);

            return response()->json([
                'success' => true,
                'message' => 'Sale item restored successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Sale Item Restore Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore sale item.'
            ], 500);
        }
    }



    // Sale Item Soft Delete
    public function softDeleteItem($id)
    {
        try {
            $this->service->deleteSaleItem($id);
            return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Sale Item Delete Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete item'], 500);
        }
    }


    /**
     * (Optional) Force delete if you ever want permanent delete
     */
    public function forceDelete(int $id)
    {
        try {
            $item = $this->itemRepo->findWithTrashed($id);
            $this->itemRepo->forceDelete($id);

            return back()->with('success', 'Sale item permanently deleted.');
        } catch (\Exception $e) {
            Log::error('Sale Item Force Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to permanently delete sale item.');
        }
    }
}
