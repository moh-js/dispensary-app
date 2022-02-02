<?php

namespace App\Http\Livewire;

use App\Models\Service;
use Livewire\Component;
use App\Models\Prescription;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EncounterPrescriptionForm extends Component
{
    use AuthorizesRequests;

    public $encounter;
    public $service_id;
    public $services = [];
    public $quantity;
    public $form_flag = 0;

    protected $rules = [
        'service_id' => ['required', 'integer'],
        'quantity' => ['required', 'integer', 'max:1000'],
    ];

    protected function getListeners()
    {
        return [
            'editPrescription'
        ];
    }

    public function mount()
    {
        $this->services = Service::where('service_category_id', 1)->get();
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

    public function editPrescription($investigation_id)
    {
        $this->authorize('prescription-update');

        $this->showForm();

        $this->prescription = Prescription::find($investigation_id);
        $this->service_id = $this->prescription->service_id;
        $this->quantity  = $this->prescription->quantity;
    }

    public function saveData()
    {

        $validatedData = $this->validate();
        $validatedData = collect($validatedData)->merge(['encounter_id' => $this->encounter->id])->toArray();

        $service = Service::find($this->service_id);
        $unitItem = $service->item->getUnitById(1);

        if ($unitItem->remain < $this->quantity) {
            $message = ['text' => "The quantity requested is greater than the available amount in the inventory - $service->name", 'type' => 'danger'];
            $this->emitUp('notification', $message);
        } else {
            if ($this->prescription) {
                $this->authorize('prescription-update');
                $this->prescription->update($validatedData);
            } else {
                $this->authorize('prescription-create');
                Prescription::create($validatedData);
            }

            $this->clearForm();

            $message = ['text' => 'Data saved successfully', 'type' => 'success'];

            $this->emitUp('notification', $message);
        }
    }


    public function clearForm()
    {
        $this->form_flag = 0;
        $this->service_id = null;
        $this->quantity = null;
        $this->prescription = null;
    }

    public function render()
    {
        $this->authorize('prescription-create');

        return view('livewire.encounter-prescription-form');
    }
}
