<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer',
        'created_at',
        'completed_at',
        'warehouse_id',
        'status',
    ];
}
