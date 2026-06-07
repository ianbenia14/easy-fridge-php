<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FridgeMovement extends Model
{
    protected $fillable = [
        'user_id',
        'fridge_id',
        'product_id',
        'action',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fridge()
    {
        return $this->belongsTo(Fridge::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}