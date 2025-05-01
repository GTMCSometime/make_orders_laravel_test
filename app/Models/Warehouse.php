<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
    ];

    public function stocks() {
        return $this->hasMany(Stock::class);
    }
}
