<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// ==== Contracts ====
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\NoteRepositoryInterface;
use App\Repositories\Contracts\SaleRepositoryInterface;
use App\Repositories\Contracts\SaleItemRepositoryInterface;
use App\Repositories\Contracts\ProductAvailabilityRepositoryInterface;

// ==== Implementations ====
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\NoteRepository;
use App\Repositories\SaleRepository;
use App\Repositories\SaleItemRepository;
use App\Repositories\ProductAvailabilityRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // User
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Product
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        // Note
        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);

        // Sale
        $this->app->bind(SaleRepositoryInterface::class, SaleRepository::class);

        // Sale Item
        $this->app->bind(SaleItemRepositoryInterface::class, SaleItemRepository::class);
        $this->app->bind(ProductAvailabilityRepositoryInterface::class, ProductAvailabilityRepository::class);
    }

    public function boot()
    {
        //
    }
}
