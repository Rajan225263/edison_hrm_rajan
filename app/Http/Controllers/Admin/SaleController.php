<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Repositories\Contracts\NoteRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;

class SaleController extends Controller
{
    protected $service;
    protected $notes;
    protected $userRepo;
    protected $productRepo;

    public function __construct(
        SaleService $service,
        NoteRepositoryInterface $notes,
        UserRepositoryInterface $userRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->service = $service;
        $this->notes = $notes;
        $this->userRepo = $userRepo;
        $this->productRepo = $productRepo;
    }

    //List all sales
    public function index(Request $request)
    {
        try {
            $sales = $this->service->getAllSales($request);

            $pageTotal = $sales->sum('grand_total');

            return view('admin.sales.index', compact('sales', 'pageTotal'));
        } catch (\Exception $e) {
            Log::error('Sale Index Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load sales list.');
        }
    }

    // New sale form
    public function create()
    {
        $users = $this->userRepo->getAllOrderedByName();       
        $products = $this->productRepo->getAllOrderedByName();

        return view('admin.sales.create', compact('users', 'products'));
    }

    // Save new sale
    public function store(SaleRequest $request)
    {
        try {
            $sale = $this->service->createSale(
                $request->only('user_id'),
                $request->input('items'),
                $request->input('note')
            );

            return response()->json([
                'success' => true,
                'message' => 'Sale created successfully',
                'sale_id' => $sale->id,
                'redirect_url' => route('admin.sales.create', $sale->id),
            ]);
        } catch (\Exception $e) {
            Log::error('Sale Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sale',
            ], 500);
        }
    }


    // Showing the sale
    public function show($id)
    {
        try {
            $sale = $this->service->getSaleById($id);

            return view('admin.sales.show', [
                'sale'  => $sale,
                'notes' => $sale->notes ?? collect(),
            ]);
        } catch (\Exception $e) {
            Log::error('Sale Show Error: ' . $e->getMessage());
            return back()->with('error', 'Sale not found.');
        }
    }

    public function edit($id)
    {
        try {
            $sale = $this->service->findSaleWithItems($id);
            $users = User::orderBy('name')->get();
            $products = Product::orderBy('name')->get();

            return view('admin.sales.edit', compact('sale', 'users', 'products'));
        } catch (\Exception $e) {
            Log::error('Sale Edit Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load edit form.');
        }
    }

    public function update(SaleRequest $request, $id)
    {
        try {
            $this->service->updateSale(
                Sale::findOrFail($id),
                $request->only('user_id', 'sale_date'),
                $request->input('items'),
                $request->input('notes')
            );

            return redirect()->route('admin.sales.index')->with('success', 'Sale updated successfully');
        } catch (\Exception $e) {
            Log::error('Sale Update Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update sale')->withInput();
        }
    }

    // (soft delete)
    public function destroy($id)
    {
        try {
            $this->service->deleteSale($id);

            return redirect()->route('admin.sales.index')->with('success', 'Sale deleted');
        } catch (\Exception $e) {
            Log::error('Sale Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete sale.');
        }
    }

    // Trashed sale
    public function trash()
    {
        try {
            $sales = $this->service->getTrashedSales();
            return view('admin.sales.trash', compact('sales'));
        } catch (\Exception $e) {
            Log::error('Trashed Sales Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to fetch trashed sales.');
        }
    }


    // Restore from Trash
    public function restore($id)
    {
        try {
            $this->service->restoreSale($id);
            return redirect()->route('admin.sales.trash')->with('success', 'Sale restored');
        } catch (\Exception $e) {
            Log::error('Sale Restore Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to restore sale.');
        }
    }

    public function storeNote(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'sale_id' => 'required|exists:sales,id',
                'note' => 'required|string|max:1000',
            ]);

            // Load Sale model using sale_id from request
            $sale = $this->service->getSaleById($request->sale_id);
            if (!$sale) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sale not found.'
                ], 404);
            }

            // Create note using NoteRepository
            $note = $this->notes->createForModel($sale, $request->note, auth()->id() ?? 1);

            return response()->json([
                'success' => true,
                'message' => 'Note added successfully!',
                'note' => $note->note,
                'user' => $note->user->name ?? 'Unknown',
            ]);
        } catch (\Exception $e) {
            Log::error('Note Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add note.'
            ], 500);
        }
    }
}
