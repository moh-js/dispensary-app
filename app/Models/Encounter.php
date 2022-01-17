<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    use HasFactory;

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


}
