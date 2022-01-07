<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EncounterGeneralForm extends Component
{
    public $diagnosis;
    public $treatment;
    public $comments;

    protected $rules = [
        'diagnosis' => 'nullable|string|min:6',
        'treatment' => 'nullable|string|email',
        'comments' => 'nullable|string|email',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validatedData = $this->validate();
    }

    public function render()
    {
        return view('livewire.encounter-general-form');
    }
}
