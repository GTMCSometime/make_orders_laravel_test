<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    
    public $timestamps = false;


    protected $fillable = [
        'name',
    ];

    // связь с таблицей Stocks
    public function stocks() {
        return $this->hasMany(Stock::class);
    }

    // связь с таблицей Orders
    public function orders() {
        return $this->hasMany(Order::class);
    }
}
