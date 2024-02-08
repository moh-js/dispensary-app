<?php
namespace App\Traits;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Models\Audit;

/**
 * Audit
 */
trait AuditBuilder
{
    public function getInventoryAudits()
    {
        $audits = Audit::where([['auditable_type', 'like', '%Item']])->orderBy('id', 'desc');
        return $audits;
    }

    public function getServiceAudits()
    {
        $audits = Audit::where([['auditable_type', 'App\Models\Service']])->orderBy('id', 'desc');
        return $audits;
    }

    public function getGeneralAudits()
    {
        $clauses = [
            ['auditable_type', '!=', 'App\Models\OrderService'],
            ['auditable_type', '!=', 'App\Models\ItemUnit'],
        ];
        $audits = Audit::where($clauses)->orderBy('id', 'desc');
        return $audits;
    }

}
