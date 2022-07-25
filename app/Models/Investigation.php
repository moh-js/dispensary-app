<?php

namespace App\Models;

use App\Traits\ReceiptGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Investigation extends Model implements ContractsAuditable
{
    use HasFactory;
    use ReceiptGenerator;
    use Auditable;


    protected $guarded  = [];

    // protected $with = ['service', 'encounter'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
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
        static::created(function ($investigation) {
            $order = $investigation->encounter->patient->getLastPendingOrder();

            if (!$order) {
                $order = $investigation->encounter->patient->orders()->firstOrCreate([
                    'patient_id' => $investigation->encounter->patient_id,
                    'order_date' => now(),
                    'encounter_id' => $investigation->encounter->id
                ]);

                $order->update([
                    'invoice_id' => self::generate($order, 'invoice'),
                ]);
            }

            // Add bill service to order
            $investigation->update([
                'order_service_id' => $order->items()->firstOrCreate([
                                            'service_id' => $investigation->service->id,
                                            'unit_id' => 2,
                                            'service_category_id' => 2,
                                            'sub_total' => $investigation->service->price,
                                            'total_price' => $investigation->service->price * 1,
                                            'quantity' => 1
                                        ])->id
            ]);

        });

        static::deleted(function ($investigation)
        {
            $item = $investigation->orderService;

            if ($item) {
                $item->delete();
            }
        });
    }
}
