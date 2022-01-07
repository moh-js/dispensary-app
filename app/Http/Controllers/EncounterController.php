<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use App\Models\Patient;
use Illuminate\Http\Request;

class EncounterController extends Controller
{
    public function index(Encounter $encounter)
    {
        $this->authorize('encounter-view');

        return view('encounter.index', [
            'encounter' => $encounter
        ]);
    }

    public function createEncounter()
    {
        $this->authorize('encounter-create');

        $encounter = Encounter::firstOrCreate([
            'name' => null,
            'patient_id' => request('patient_id'),
        ]);

        return redirect()->route('encounter', $encounter->id);

    }
}
