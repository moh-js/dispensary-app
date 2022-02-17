<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Encounter;
use Illuminate\Http\Request;
use App\Traits\PatientIdGenerator;

class PatientController extends Controller
{
    use PatientIdGenerator;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('patient-add');

        $patient = Patient::all()->last();
        $patient_id = $this->generate((($patient->id)??0) + 1);

        return view('patient.add', [
            'patient_id' => $patient_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('patient-add');

        $request->validate([
            "patient_id" => ['required', 'string'],
            "first_name" => ['required', 'string'],
            "middle_name" => ['nullable', 'string'],
            "last_name" => ['required', 'string'],
            "dob" => ['date', 'required'],
            "phone" => ['string', 'required'],
            "gender" => ['string', 'required'],
            "address" => ['string', 'required']
        ]);

        $data = $request->except(['_token']);

        flash('Patient added successfully');
        return redirect()->route('patient.show', Patient::firstOrCreate($data)->patient_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        $this->authorize('patient-view');

        $users = User::role('doctor')->get();
        $services = Service::where('service_category_id', 4)->get();
        $encounter = Encounter::where([['patient_id', $patient->id], ['status', 0]])->first();

        foreach (Encounter::all() as $e) {
            $e->update([ 'name' => $e->created_at->format('dmYHi').rand(100,1000)]);
        }

        return view('patient.show', [
            'encounter' => $encounter,
            'services' => $services,
            'user' => $patient,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
