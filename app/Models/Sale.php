<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'sale_date', 'grand_total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Accessor → will display the total in a nice format
    public function getFormattedTotalAttribute()
    {
        return number_format($this->grand_total, 2) . ' BDT';
    }

    // Polymorphic notes

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    // Here we will get the relation with Soft Deleted item
    public function itemsWithTrashed()
    {
        return $this->hasMany(SaleItem::class)->withTrashed();
    }
}
