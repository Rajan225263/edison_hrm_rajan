<?php
if (!function_exists('calculateSaleTotal')) {
    /**
     * $items: array of ['product_id'=>, 'quantity'=>, 'price'=>, 'discount'=>]
     */
    function calculateSaleTotal(array $items): float {
        $total = 0.0;
        foreach ($items as $item) {
            $quantity = (int) data_get($item, 'quantity', 1);
            $price = (float) data_get($item, 'price', 0);
            $discount = (float) data_get($item, 'discount', 0);
            $line = ($price * $quantity) - $discount;
            $total += $line;
        }
        return round($total, 2);
    }
}
