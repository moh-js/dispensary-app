<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class OrderService extends Model implements ContractsAuditable
{
    use HasFactory;
    use Auditable;

    protected $guarded = [];

    protected $table = 'order_service';

    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function scopeMedicine($query)
    {
        $query->where('service_category_id', 1);
    }

}
