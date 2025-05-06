<?php


namespace App\Http\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    public const CUSTOMER = 'customer';
    public const DATE = 'created_at';
    public const COMPLETED_AT = 'completed_at';
    public const WAREHOUSE_ID = 'warehouse_id';
    public const STATUS = 'status';


    protected function getCallbacks(): array
    {
        return [
            self::CUSTOMER => [$this, 'customer'],
            self::DATE => [$this, 'created_at'],
            self::COMPLETED_AT => [$this, 'completed_at'],
            self::WAREHOUSE_ID => [$this, 'warehouse_id'],
            self::STATUS => [$this, 'status'],
        ];
    }
    // фильтрация по имени заказчика
    public function customer(Builder $builder, $value)
    {
        $builder->where('customer',  $value);
    }
    // фильтрация по дате создания
    protected function created_at(Builder $builder, $value) 
    {
        $builder->whereDate('created_at', new Carbon($value));
    }
    // фильтрация по дате завершения
    protected function completed_at(Builder $builder, $value) 
    {
        $builder->whereDate('completed_at', new Carbon($value));
    }
    // фильтрация по ID склада
    public function warehouse_id(Builder $builder, $value)
    {
        $builder->where('warehouse_id',  $value);
    }
    // фильтрация по статусу
    public function status(Builder $builder, $value)
    {
        $builder->where('status',  $value);
    }
    

}