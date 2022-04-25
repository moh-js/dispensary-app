<?php
namespace App\Traits;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;

/**
 * Audit
 */
trait AuditBuilder
{
    public function getInventoryAudits()
    {
        $audits = Audit::where([['auditable_type', 'like', '%Item']]);
        return $audits;
    }

    public function getServiceAudits()
    {
        $audits = Audit::where([['auditable_type', 'App\Models\Service']]);
        return $audits;
    }
}
