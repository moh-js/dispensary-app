<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission implements ContractsAuditable
{
    use HasFactory;
    use Auditable;
}
