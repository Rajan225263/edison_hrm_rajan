<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductAvailability;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'Apple', 'description' => 'Fresh Red Apple', 'price' => 50.00, 'stock' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Banana', 'description' => 'Ripe Yellow Banana', 'price' => 20.00, 'stock' => 150, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Orange', 'description' => 'Juicy Orange', 'price' => 40.00, 'stock' => 80, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mango', 'description' => 'Sweet Mango', 'price' => 100.00, 'stock' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pineapple', 'description' => 'Fresh Pineapple', 'price' => 120.00, 'stock' => 30, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            ProductAvailability::create([
                'product_id' => $product->id,
                'stock' => $productData['stock'],
            ]);
        }
    }
}
