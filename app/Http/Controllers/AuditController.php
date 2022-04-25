<?php

namespace App\Http\Controllers;

use App\Traits\AuditBuilder;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    use AuditBuilder;

    public function inventoryAudits()
    {
        $this->authorize('inventory-audits');

        $audits = $this->getInventoryAudits()->paginate(20);

        return view('audits.inventory', [
            'audits' => $audits
        ]);
    }

}
