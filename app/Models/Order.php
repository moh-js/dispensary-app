<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Order extends Model implements ContractsAuditable
{
    use HasFactory;
    use Auditable;

    protected $guarded = [];

    protected $casts = [
        'order_date' => 'datetime'
    ];

    public function items()
    {
        return $this->hasMany(OrderService::class, 'order_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
