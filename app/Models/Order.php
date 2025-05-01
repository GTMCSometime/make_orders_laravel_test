<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Filterable;
    protected $fillable = [
        'customer',
        'created_at',
        'completed_at',
        'warehouse_id',
        'status',
    ];

    const STATUS = ["active", "completed", "canceled"];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }
}
