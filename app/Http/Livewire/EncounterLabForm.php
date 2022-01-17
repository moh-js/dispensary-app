<?php

namespace App\Http\Livewire;

use App\Models\Investigation;
use App\Models\Service;
use Livewire\Component;

class EncounterLabForm extends Component
{

    public $form_flag = 0;
    public $services = [];
    public $service_id;
    public $result;
    public $investigation;
    public $encounter;

    protected $rules = [
        'service_id' => ['required', 'integer'],
        'result' => ['nullable', 'string', 'max:1000'],
    ];

    protected function getListeners()
    {
        return [
            'editInvestigation'
        ];
    }

    public function mount()
    {
        $this->services = Service::where('service_category_id', 2)->get();
        $this->clearForm();
    }

    public function showForm()
    {
        $this->form_flag = 1;
        session()->forget('message');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function editInvestigation($investigation_id)
    {
        $this->showForm();

        $this->investigation = Investigation::find($investigation_id);
        $this->service_id = $this->investigation->service_id;
        $this->result  = $this->investigation->result;
    }

    public function saveData()
    {
        $validatedData = $this->validate();
        $validatedData = collect($validatedData)->merge(['encounter_id' => $this->encounter->id])->toArray();

        if ($this->investigation) {
            $this->investigation->update($validatedData);
        }

        Investigation::firstOrCreate($validatedData);

        $this->clearForm();

        $message = ['text' => 'Data saved successfully', 'type' => 'success'];

        $this->emitUp('notification', $message);
    }

    public function clearForm()
    {
        $this->form_flag = 0;
        $this->service_id = null;
        $this->result = null;
        $this->investigation = null;
    }

    public function render()
    {
        return view('livewire.encounter-lab-form');
    }
}
