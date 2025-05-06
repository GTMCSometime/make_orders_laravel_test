<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{   
    public $timestamps = false;
    protected $primaryKey = false;
    public $incrementing = false;
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'stock',
    ];

    // связь с таблицей Products
    public function product() {
        return $this->belongsTo(Product::class);
    }

    // связь с таблицей Warehouses
    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }
}
