<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Traits\PatientIdGenerator;
use Illuminate\Http\Request;

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
        $patient_id = $this->generate($patient->id + 1);

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

        return view('patient.show', [
            'user' => $patient
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
