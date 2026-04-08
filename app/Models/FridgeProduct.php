<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FridgeProduct extends Model
{
    protected $table = 'fridge_products';

    protected $fillable = [
        'fridge_id',
        'product_id',
        'quantidade',
    ];

    public function fridge()
    {
        return $this->belongsTo(Fridge::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}