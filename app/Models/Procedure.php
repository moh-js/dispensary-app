<?php

namespace App\Models;

use App\Traits\ReceiptGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Procedure extends Model implements ContractsAuditable
{
    use HasFactory;
    use ReceiptGenerator;
    use Auditable;

    protected $guarded = [];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function orderService()
    {
        return $this->belongsTo(OrderService::class, 'order_service_id');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($procedure) {
            $order = $procedure->encounter->patient->getLastPendingOrder();

            if (!$order) {
                $order = $procedure->encounter->patient->orders()->firstOrCreate([
                    'patient_id' => $procedure->encounter->patient_id,
                    'order_date' => now(),
                    'encounter_id' => $procedure->encounter->id
                ]);

                $order->update([
                    'invoice_id' => self::generate($order, 'invoice'),
                ]);
            }

            // Add bill service to order
            $procedure->update([
                'order_service_id' => $order->items()->firstOrCreate([
                                            'service_id' => $procedure->service->id,
                                            'unit_id' => 2,
                                            'service_category_id' => 3,
                                            'sub_total' => $procedure->service->price,
                                            'total_price' => $procedure->service->price * $procedure->quantity,
                                            'quantity' => $procedure->quantity
                                        ])->id
            ]);
        });

        static::deleted(function ($procedure)
        {
            $item = $procedure->orderService;

            if ($item) {
                $item->delete();
            }
        });
    }

}
