<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    
    public $timestamps = false;


    protected $fillable = [
        'product_id',
        'warehouse_id',
        'stock',
    ];


    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }
}
