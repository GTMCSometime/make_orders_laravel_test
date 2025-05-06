<?php

namespace App\Models;

use App\Http\Filters\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Filterable;


    public $timestamps = false;
    protected $fillable = [
        'customer',
        'created_at',
        'completed_at',
        'warehouse_id',
        'status',
    ];
    

    const ACTIVE = 'active';
    const COMPLETED = 'completed';
    const CANCELED = 'canceled';

    // связь с таблицей OrderItems
    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    // связь с таблицей Warehouses
    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }
}
