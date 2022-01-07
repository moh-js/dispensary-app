<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Encounter extends Component
{
    public $patient;
    public $lab_flag = 0;
    public $general_flag = 1;
    public $signs_flag = 0;
    public $allergies_flag = 0;
    public $medical_flag = 0;
    public $bill_flag = 0;
    public $form_flag = 0;

    public function changeFlag($flag)
    {
        $flag = $flag.'_flag';

        $this->lab_flag = 0;
        $this->general_flag = 0;
        $this->signs_flag = 0;
        $this->allergies_flag = 0;
        $this->medical_flag = 0;
        $this->bill_flag = 0;
        $this->form_flag = 0;

        $this->$flag = 1;
    }

    public function showForm()
    {
        $this->form_flag = 1;
    }

    public function render()
    {
        return view('livewire.encounter');
    }
}
