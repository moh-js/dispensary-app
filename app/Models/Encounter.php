<?php

namespace App\Models;

use App\Traits\ReceiptGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Encounter extends Model implements ContractsAuditable
{
    use HasFactory;
    use ReceiptGenerator;
    use Auditable;

    protected $guarded = [];

    protected $with = ['patient', 'orders', 'investigations'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function pendingOrder()
    {
        return $this->orders()->where('status', 'pending')->first();
    }

    public function investigations()
    {
        return $this->hasMany(Investigation::class);
    }

    public function vital()
    {
        return $this->hasOne(Vital::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function procedures()
    {
        return $this->hasMany(Procedure::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'purpose');
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($encounter) {
            $order = $encounter->patient->getLastPendingOrder();

            if (!$order) {
                $order = $encounter->patient->orders()->firstOrCreate([
                    'patient_id' => $encounter->patient_id,
                    'order_date' => now(),
                    'encounter_id' => $encounter->id
                ]);

                $order->update([
                    'invoice_id' => self::generate($order, 'invoice'),
                ]);
            }

            // Add bill service to order
            $order->items()->firstOrCreate([
                'service_id' => $encounter->service->id,
                'service_category_id' => 4,
                'payment_type' => request('payment_type'),
                'sub_total' => $encounter->service->price,
                'total_price' => $encounter->service->price * 1,
                'quantity' => 1
            ]);
        });

        static::deleted(function ($encounter)
        {
            $order = $encounter->patient->getLastPendingOrder();


            if ($order) {
                $order->items()->where('service_id', $encounter->service_id)->first()->delete();
            }
        });

    }

}
