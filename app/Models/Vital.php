<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Vital extends Model implements ContractsAuditable
{
    use HasFactory;
    use Auditable;

    protected $guarded = [];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }
}
