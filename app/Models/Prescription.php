<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use App\Traits\ReceiptGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Prescription extends Model implements ContractsAuditable
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
        static::created(function ($prescription) {
            $order = $prescription->encounter->patient->getLastPendingOrder();

            if (!$order) {
                $order = $prescription->encounter->patient->orders()->firstOrCreate([
                    'patient_id' => $prescription->encounter->patient_id,
                    'order_date' => now(),
                    'encounter_id' => $prescription->encounter->id
                ]);

                $order->update([
                    'invoice_id' => self::generate($order, 'invoice'),
                ]);
            }

            // Add bill service to order
            $prescription->update([
                'order_service_id' => $order->items()->firstOrCreate([
                                        'service_id' => $prescription->service_id,
                                        'unit_id' => $prescription->unit_id,
                                        'service_category_id' => 1,
                                        'sub_total' => $prescription->service->price,
                                        'total_price' => $prescription->service->price * $prescription->quantity,
                                        'quantity' => $prescription->quantity
                                    ])->id
            ]);

        });

        static::deleted(function ($prescription)
        {
            $item = $prescription->orderService;

            if ($item) {
                $item->delete();
            }
        });

    }
}
