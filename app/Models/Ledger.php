<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Ledger extends Model implements ContractsAuditable
{
    use HasFactory;
    use SoftDeletes;
    use Auditable;

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'to');
    }
}
