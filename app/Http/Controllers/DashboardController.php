<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Encounter;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class DashboardController extends Controller
{
    public function index()
    {
        $allAudits = Audit::where('auditable_type', Patient::class)
        ->orWhere('auditable_type', Encounter::class)
        ->latest()
        ->limit(15)
        ->get();

        return view('dashboard', [
            'activities' => $allAudits
        ]);
    }
}
