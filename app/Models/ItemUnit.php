<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class ItemUnit extends Model implements ContractsAuditable
{
    use HasFactory;
    use Auditable;

    protected $table = 'item_unit';

    protected $guarded = [];

    public $timestamps = false;

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
