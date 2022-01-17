<?php

namespace App\Http\Livewire;

use App\Models\Vital;
use Livewire\Component;

class EncounterVitalsForm extends Component
{

    public $weight;
    public $encounter;
    public $height;
    public $temperature;
    public $bmi = 0;
    public $systolic;
    public $diastolic;

    protected $rules = [
        'weight' => ['nullable', 'integer', 'max:499.99', 'min:0.1'],
        'height' => ['nullable', 'integer', 'max:499.99', 'min:0.1'],
        'temperature' => ['nullable', 'integer', 'max:49.99', 'min:0.1'],
        'diastolic' => ['nullable', 'integer', 'max:149.99', 'min:0.1'],
        'systolic' => ['nullable', 'integer', 'max:399.99', 'min:0.1'],
    ];

    public function mount()
    {
        $this->getData();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName == 'height' || $propertyName == 'weight') {
            if ($this->weight && $this->height) {
                $heightInMeter = $this->height/100;
                $this->bmi = round($this->weight / (($heightInMeter * $heightInMeter)), 2);
            }
        }
    }


    public function saveData()
    {
        $validatedData = $this->validate();
        $validatedData = collect($validatedData)->merge(['bmi' => $this->bmi])->toArray();

        Vital::updateOrCreate([
            'encounter_id' => $this->encounter->id
        ], $validatedData);

        $message = ['text' => 'Data saved successfully', 'type' => 'success'];

        $this->emitUp('notification', $message);

    }

    public function getData()
    {
        $this->height = $this->encounter->vital->height??null;
        $this->bmi = $this->encounter->vital->bmi??null;
        $this->weight = $this->encounter->vital->weight??null;
        $this->temperature = $this->encounter->vital->temperature??null;
        $this->diastolic = $this->encounter->vital->diastolic??null;
        $this->systolic = $this->encounter->vital->systolic??null;
    }

    public function render()
    {
        return view('livewire.encounter-vitals-form');
    }
}
