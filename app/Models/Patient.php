<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Patient extends Model implements ContractsAuditable
{
    use HasFactory;
    use SoftDeletes;
    use Auditable;

    protected $guarded = [];

    protected $casts = [
        'dob' => 'date'
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    function getRouteKeyName()
    {
        return 'patient_id';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
