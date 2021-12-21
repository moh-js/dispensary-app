<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole implements ContractsAuditable
{
    use HasFactory;
    use Auditable;
    use SoftDeletes;

    public function resolveRouteBinding($value, $field = null)
    {
        return Role::withTrashed()->where('name', $value)->first();
    }

}
