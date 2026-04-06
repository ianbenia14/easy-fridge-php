<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'food_table';

    protected $fillable = [
        'name',
        'quantidade',
        'data_validade',
    ];
}