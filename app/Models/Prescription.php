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
            $order->items()->firstOrCreate([
                'service_id' => $prescription->service->id,
                'unit_id' => 1,
                'service_category_id' => 1,
                'sub_total' => $prescription->service->price,
                'total_price' => $prescription->service->price * $prescription->quantity,
                'quantity' => $prescription->quantity
            ]);
        });

        static::updated(function ($prescription)
        {
            $order = $prescription->encounter->patient->getLastPendingOrder();

            $orderItem = $order->items()->where('service_id', $prescription->service_id)->first();

            $orderItem->update([
                'sub_total' => $prescription->service->price,
                'total_price' => $prescription->service->price * $prescription->quantity,
                'quantity' => $prescription->quantity
            ]);
        });

        static::deleted(function ($prescription)
        {
            $order = $prescription->encounter->patient->getLastPendingOrder();

            if ($order) {
                $order->items()->where('service_id', $prescription->service_id)->first()->delete();
            }
        });

    }
}
