<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use Illuminate\Http\Request;

class EncounterController extends Controller
{
    public function index(Encounter $encounter)
    {
        $this->authorize('encounter-view');

        return view('encounter.index', [
            'encounter' => $encounter,
            'patient' => $encounter->patient_id
        ]);
    }

    public function createEncounter()
    {
        $this->authorize('encounter-create');

        $encounter = Encounter::where([['patient_id', request('patient_id')], ['status', 0]])->first();

        if (!$encounter) {
            $encounter = Encounter::create([
                'name' => null,
                'patient_id' => request('patient_id'),
            ]);
        }

        return redirect()->route('encounter', $encounter->id);

    }

}
