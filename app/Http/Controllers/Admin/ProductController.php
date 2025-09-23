<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\NoteRepositoryInterface;
use App\Repositories\Contracts\ProductAvailabilityRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;

class ProductController extends Controller
{
    protected $products;
    protected $notes;
    protected $availability;

    public function __construct(

        ProductRepositoryInterface $products,
        NoteRepositoryInterface $notes,
        ProductAvailabilityRepositoryInterface $availability
    ) {
        $this->products = $products;
        $this->notes = $notes;
        $this->availability = $availability;
    }

    public function index()
    {
        try {
            $products = $this->products->getAll();
            return view('admin.products.index', compact('products'));
        } catch (Exception $e) {
            Log::error('Product Index Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load products.');
        }
    }

    public function create(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function store(ProductRequest $request)
    {
        try {
            $product = $this->products->create($request->only(['name', 'price', 'stock', 'description']));

            $this->availability->addStock($product->id, (int) $request->input('stock', 0));

            if ($request->filled('note')) {
                $this->notes->createForModel($product, $request->note, auth()->id() ?? 1);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (Exception $e) {
            Log::error('Product Store Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create product.')->withInput();
        }
    }

    public function show($id)
    {
        try {
            // Get product data from ProductRepository
            $product = $this->products->findById($id);

            // Get notes from NoteRepository
            $notes = $this->notes->getForModel($product); // new method in NoteRepository

            return view('admin.products.show', compact('product', 'notes'));
        } catch (Exception $e) {
            Log::error('Product Show Error: ' . $e->getMessage());
            return back()->with('error', 'Product not found.');
        }
    }


    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            $this->products->update($product, $request->only(['name', 'price', 'description']));


            if ($request->filled('note')) {
                $this->notes->createForModel($product, $request->note, auth()->id() ?? 1);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (Exception $e) {
            Log::error('Product Update Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update product.')->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {

            $this->products->delete($product);
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (Exception $e) {

            Log::error('Product Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete product.');
        }
    }

    // Add Note (AJAX or form submit) 

    public function storeNote(Product $product, Request $request)
    {
        try {
            $request->validate(['note' => 'required|string|max:1000']);
            $note = $this->notes->createForModel($product, $request->note, auth()->id() ?? 1);

            return response()->json([
                'success' => true,
                'message' => 'Note added successfully!',
                'note' => $note->note,
                'user' => $note->user->name ?? 'Unknown',
            ]);
        } catch (\Exception $e) {
            Log::error('Note Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add note.'], 500);
        }
    }
}
