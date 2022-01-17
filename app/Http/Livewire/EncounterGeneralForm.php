<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EncounterGeneralForm extends Component
{
    public $diagnosis;
    public $treatment;
    public $comments;
    public $encounter;

    protected $rules = [
        'diagnosis' => ['nullable', 'string'],
        'treatment' => ['nullable', 'string'],
        'comments' => ['nullable', 'string'],
    ];

    public function mount()
    {
        $this->diagnosis = $this->encounter->diagnosis;
        $this->treatment = $this->encounter->treatment;
        $this->comments = $this->encounter->comments;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function saveData()
    {
        $validatedData = $this->validate();

        $this->encounter->update($validatedData);

        $message = ['text' => 'Data saved successfully', 'type' => 'success'];

        $this->emitUp('notification', $message);

    }

    public function render()
    {
        return view('livewire.encounter-general-form');
    }
}
