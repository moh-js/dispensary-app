<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Order extends Model implements ContractsAuditable
{
    use HasFactory;
    use Auditable;

    protected $guarded = [];

    protected $casts = [
        'order_date' => 'datetime'
    ];

    protected $with = ['items'];

    public function items()
    {
        return $this->hasMany(OrderService::class, 'order_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function getOrderItemById($item_id)
    {
        return $this->items()->whereHas('service', function (Builder $query) use ($item_id)
        {
            $query->where('item_id', $item_id);
        })->first();
    }
}
