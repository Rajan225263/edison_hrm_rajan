<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock'];

    // Relation: Product has many SaleItems
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Polymorphic Notes relation
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable'); // 'notable_id' & 'notable_type'
    }

    // ProductAvailability relation
    public function availability()
    {
        return $this->hasOne(ProductAvailability::class);
    }
}
