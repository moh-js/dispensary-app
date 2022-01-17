<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Prescription;
use App\Models\Investigation;

class Encounter extends Component
{
    public $encounter;
    public $investigation;
    public $message = null;
    public $lab_flag = 0;
    public $general_flag = 0;
    public $signs_flag = 0;
    public $allergies_flag = 0;
    public $medical_flag = 0;
    public $bill_flag = 0;
    public $prescription_flag = 0;

    protected function getListeners()
    {
        return [
            'notification' => 'notify',
            'removeInvestigation',
            'removePrescription'
        ];
    }

    public function mount()
    {
        $flag = session($this->encounter->patient->patient_id)?:1;
        $this->changeFlag($flag);
    }

    public function changeFlag($flag)
    {
        session([$this->encounter->patient->patient_id => $flag]);

        $flag = $flag.'_flag';

        $this->lab_flag = 0;
        $this->general_flag = 0;
        $this->signs_flag = 0;
        $this->allergies_flag = 0;
        $this->medical_flag = 0;
        $this->bill_flag = 0;
        $this->form_flag = 0;
        $this->prescription_flag = 0;

        $this->$flag = 1;

    }

    public function notify($message)
    {
        session()->flash('message', $message);
    }

    public function removeInvestigation($investigation_id)
    {
        $investigation = Investigation::find($investigation_id);
        $name = $investigation->service->name;

        $investigation->delete();

        $this->encounter = $this->encounter->fresh();

        $message = ['text' => "$name removed successfully", 'type' => 'success'];
        session()->flash('message', $message);
    }

    public function removePrescription($prescription_id)
    {
        $prescription = Prescription::find($prescription_id);
        $name = $prescription->service->name;

        $prescription->delete();

        $this->encounter = $this->encounter->fresh();

        $message = ['text' => "$name removed successfully", 'type' => 'success'];
        session()->flash('message', $message);
    }

    public function render()
    {
        return view('livewire.encounter');
    }
}
