<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditController extends Controller
{

    public function inventoryAudits()
    {
        $this->authorize('audits-inventory-view');

        return view('audits.inventory');
    }

    public function serviceAudits()
    {
        $this->authorize('audits-service-view');

        return view('audits.service');
    }

    public function generalAudits()
    {
        $this->authorize('audits-general-view');
        
        return view('audits.general');
    }

}
