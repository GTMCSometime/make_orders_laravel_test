<?php

namespace App\Models;

use App\Http\Filters\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use Filterable;


    protected $fillable = [
        'product_id',
        'warehouse_id',
        'count',
        'operation',
        'notes',
    ];
    const STORE = 'store';
    const UPDATE = 'update';
    const RESUME = 'resume';
    const COMPLETION = 'completion';
    const CANCELED = 'cancel';

    // связь с таблицей Products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // связь с таблицей Warehouses
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    // morph-связь с моделью
    public function source()
    {
        return $this->morphTo();
    }

}
