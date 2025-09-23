<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController; // Customer management
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\SaleItemController;
use App\Http\Controllers\Admin\ProductAvailabilityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// 🔹 Admin routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Customer (Users) resource routes
    Route::resource('customers', UserController::class)->names('customers');

    // Product Start------------------------------------
    Route::resource('products', ProductController::class)->names('products');

    // AJAX note store
    Route::post('products/{product}/notes', [ProductController::class, 'storeNote'])
        ->name('products.notes.store');

    // Product End------------------------------------

    // Route::post('notes', [NoteController::class, 'store'])->name('admin.notes.store');

    // Sales Start -----------------------------------------------
    Route::resource('sales', SaleController::class)->names('sales');
    Route::get('sales-trash', [SaleController::class, 'trash'])->name('sales.trash');
    Route::post('sales/{id}/restore', [SaleController::class, 'restore'])->name('sales.restore');
    Route::post('sales/notes', [SaleController::class, 'storeNote'])->name('sales.notes.store');

    // Sales End -----------------------------------------------

    // Sales Item Start---------------------------------------------

    Route::post('sales/items/{id}/soft-delete', [SaleItemController::class, 'softDeleteItem'])->name('admin.sales.items.soft-delete');

    Route::patch('sale-items/{id}/restore', [SaleItemController::class, 'restore'])->name('sale-items.restore');

    // Sales Item End---------------------------------------------


    // ProductAvailability Start

    Route::get('availabilities', [ProductAvailabilityController::class, 'index'])
        ->name('product_availability.index');

    Route::post('availabilities/{productId}/add', [ProductAvailabilityController::class, 'addStock'])
        ->name('product_availability.addStock');

    Route::post('availabilities/{productId}/subtract', [ProductAvailabilityController::class, 'subtractStock'])
        ->name('product_availability.subtractStock');

    //  ProductAvailability End
});
