<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function createEncounter(Request $request)
    {
        $this->authorize('encounter-create');

        $validator = Validator::make($request->all(), [
            'chief' => ['nullable', 'integer'],
            'purpose' => ['required', 'integer'],
            'payment_type' => ['required', 'string']
        ]);

        if($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error) {
                flash()->error($error[0]);
            }
            return back()->withInput();
        }

        $encounter = Encounter::where([['patient_id', request('patient_id')], ['status', 0]])->first();
        if ($encounter) {
            $encounter->update([
                'status' => 1
            ]);
        }

        $encounter = Encounter::create([
            'name' => now()->format('dmYHi').rand(100,1000),
            'cheif' => $request->chief,
            'purpose' => $request->purpose,
            'patient_id' => request('patient_id'),
        ]);

        return redirect()->route('encounter', $encounter->name);

    }

    public function ToggleEncounterStatus(Request $request, Encounter $encounter)
    {
        $this->authorize('encounter-status-toggle');

        $encounter->status = !$encounter->status;
        $encounter->update();

        $action = $encounter->status == 1? 'closed':'opened';

        flash("Encouter $action successfully");
        return back();

    }

}
