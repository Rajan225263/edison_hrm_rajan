<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductAvailabilityRepositoryInterface;
use Illuminate\Http\Request;

class ProductAvailabilityController extends Controller
{
    protected $availabilityRepo;

    public function __construct(ProductAvailabilityRepositoryInterface $availabilityRepo)
    {
        $this->availabilityRepo = $availabilityRepo;
    }

    /**
     * Stock list page
     */
    public function index()
    {
        $availabilities = $this->availabilityRepo->getAll();
        return view('admin.product_availability.index', compact('availabilities'));
    }

    /**
     * Add stock
     */
    public function addStock(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->availabilityRepo->addStock($productId, $request->quantity);

        return redirect()->route('admin.product_availability.index')
            ->with('success', 'Stock added successfully.');
    }

    /**
     * Subtract stock
     */
    public function subtractStock(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->availabilityRepo->subtractStock($productId, $request->quantity);

        return redirect()->route('admin.product_availability.index')
            ->with('success', 'Stock reduced successfully.');
    }
}
