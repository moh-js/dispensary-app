<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditController extends Controller
{

    public function inventoryAudits()
    {
        $this->authorize('inventory-audits');

        return view('audits.inventory');
    }

    public function serviceAudits()
    {
        $this->authorize('inventory-audits');

        return view('audits.service');
    }

}
