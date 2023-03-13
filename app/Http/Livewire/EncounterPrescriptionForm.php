<?php

namespace App\Http\Livewire;

use App\Models\Unit;
use App\Models\Service;
use Livewire\Component;
use App\Models\Prescription;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EncounterPrescriptionForm extends Component
{
    use AuthorizesRequests;

    public $encounter;
    public $prescription;
    public $service_id;
    public $unit_id;
    public $services = [];
    public $units = [];
    public $quantity;
    public $form_flag = 0;

    protected $rules = [
        'service_id' => ['required', 'integer'],
        'quantity' => ['required', 'integer', 'max:1000'],
        'unit_id' => ['required', 'integer'],
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
        $this->units = Unit::where('name', '!=', 'store')->get();
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

    public function editPrescription($prescription_id)
    {
        $this->authorize('prescription-update');

        $this->showForm();

        $this->prescription = Prescription::find($prescription_id);
        $this->service_id = $this->prescription->service_id;
        $this->quantity  = $this->prescription->quantity;
        $this->unit_id  = $this->prescription->unit_id;
    }

    public function saveData()
    {

        $validatedData = $this->validate();
        $validatedData = collect($validatedData)->merge(['encounter_id' => $this->encounter->id])->toArray();

        $service = Service::find($this->service_id);
        $unitItem = $service->item->getUnitById($this->unit_id);

        if ($unitItem->remain < $this->quantity) {
            $message = ['text' => "The quantity requested is greater than the available amount in the inventory - $service->name", 'type' => 'danger'];
            $this->emitUp('notification', $message);
        } else {
            if ($this->prescription) {
                $this->authorize('prescription-update');

                if ($this->updateService($this->prescription)) { // update service
                    $this->prescription->update($validatedData);

                    $this->clearForm();
                    $message = ['text' => 'Data saved successfully', 'type' => 'success'];
                    $this->emitUp('notification', $message);
                } else { // error
                    $this->clearForm();
                    $message = ['text' => 'Can not edit this item has already been billed', 'type' => 'danger'];
                    $this->emitUp('notification', $message);
                }
            } else {
                $this->authorize('prescription-create'); // add a prescricption
                Prescription::create($validatedData);

                $this->clearForm();
                $message = ['text' => 'Data saved successfully', 'type' => 'success'];
                $this->emitUp('notification', $message);
            }
        }
    }

    public function updateService($prescription)
    {
        $order = $prescription->orderService->order;

        if ($order) {
            $orderItem = $order->items()->where('service_id', $prescription->service_id)->get()->last();

            $service = Service::find($this->service_id);

            if (($orderItem->order->status??false) == 'pending') {
                $orderItem->update([
                    'service_id' => $this->service_id,
                    'sub_total' => $service->price,
                    'total_price' => $service->price * $this->quantity,
                    'quantity' => $this->quantity
                ]);

                return 1;
            }
        }

        return 0;
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
