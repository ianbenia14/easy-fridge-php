<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeladeiraProduct extends Model
{
    protected $table = 'geladeira_produto';

    protected $fillable = [
        'geladeira_id',
        'produto_id',
        'quantidade',
    ];

    public function geladeira()
    {
        return $this->belongsTo(Geladeira::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class, 'produto_id');
    }
}