<?php

namespace App\Http\Livewire;

use App\Models\Service;
use Livewire\Component;
use App\Models\Procedure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EncounterProcedureForm extends Component
{
    use AuthorizesRequests;
    
    public $form_flag = 0;
    public $services = [];
    public $service_id;
    public $result;
    public $procedure;
    public $encounter;

    protected $rules = [
        'service_id' => ['required', 'integer'],
        'result' => ['nullable', 'string', 'max:1000'],
    ];

    protected function getListeners()
    {
        return [
            'editProcedure'
        ];
    }

    public function mount()
    {
        $this->services = Service::where('service_category_id', 3)->get();
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

    public function editProcedure($procedure_id)
    {
        $this->authorize('procedure-update');

        $this->showForm();

        $this->procedure = Procedure::find($procedure_id);
        $this->service_id = $this->procedure->service_id;
        $this->result  = $this->procedure->result;
    }

    public function saveData()
    {
        $this->authorize('procedure-create');

        $validatedData = $this->validate();
        $validatedData = collect($validatedData)->merge(['encounter_id' => $this->encounter->id])->toArray();

        if ($this->procedure) {
            $this->authorize('procedure-update');
            $this->procedure->update($validatedData);
        }

        Procedure::create($validatedData);

        $this->clearForm();

        $message = ['text' => 'Data saved successfully', 'type' => 'success'];

        $this->emitUp('notification', $message);
    }

    public function clearForm()
    {
        $this->form_flag = 0;
        $this->service_id = null;
        $this->result = null;
        $this->procedure = null;
    }

    public function render()
    {
        $this->authorize('procedure-create');

        return view('livewire.encounter-procedure-form');
    }
}
