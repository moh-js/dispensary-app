<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class EncounterGeneralForm extends Component
{
    use AuthorizesRequests;

    public $chief_complains;
    public $amplification;
    public $review_of_systems;
    public $past_medical;
    public $social_family_history;
    public $physical_examination;
    public $systemic_examination;
    public $provisional_diagnosis;
    public $investigation;
    public $treatment;
    public $comments;
    public $encounter;

    protected $rules = [
        'chief_complains' => ['nullable', 'string', 'max:3000'],
        'amplification' => ['nullable', 'string', 'max:3000'],
        'review_of_systems' => ['nullable', 'string', 'max:3000'],
        'past_medical' => ['nullable', 'string', 'max:3000'],
        'social_family_history' => ['nullable', 'string', 'max:3000'],
        'physical_examination' => ['nullable', 'string', 'max:3000'],
        'systemic_examination' => ['nullable', 'string', 'max:3000'],
        'provisional_diagnosis' => ['nullable', 'string', 'max:3000'],
        'investigation' => ['nullable', 'string', 'max:3000'],
        'treatment' => ['nullable', 'string', 'max:3000'],
        'comments' => ['nullable', 'string', 'max:3000'],
    ];

    public function mount()
    {
        $this->chief_complains = $this->encounter->chief_complains;
        $this->amplification = $this->encounter->amplification;
        $this->review_of_systems = $this->encounter->review_of_systems;
        $this->past_medical = $this->encounter->past_medical;
        $this->social_family_history = $this->encounter->social_family_history;
        $this->physical_examination = $this->encounter->physical_examination;
        $this->systemic_examination = $this->encounter->systemic_examination;
        $this->provisional_diagnosis = $this->encounter->provisional_diagnosis;
        $this->investigation = $this->encounter->investigation;
        $this->treatment = $this->encounter->treatment;
        $this->comments = $this->encounter->comments;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function saveData()
    {
        $this->authorize('encounter-general-info-add');

        if ($this->encounter->cheif) {
            if ($this->encounter->cheif != auth()->id()) {
                flash('You cannot edit this encounter because you were not its chief provider');
                return redirect()->route('encounter', $this->encounter->id);
            }
        } else {
            $this->encounter->update([
                'cheif' => auth()->id()
            ]);
        }

        $validatedData = $this->validate();

        $this->encounter->update($validatedData);

        $message = ['text' => 'Data saved successfully', 'type' => 'success'];

        $this->emitUp('notification', $message);

    }

    public function render()
    {
        $this->authorize('encounter-general-info-create');

        return view('livewire.encounter-general-form');
    }
}
