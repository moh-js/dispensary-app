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
    public $quantity;
    public $encounter;

    protected $rules = [
        'service_id' => ['required', 'integer'],
        'quantity' => ['required', 'integer'],
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
        $this->quantity = 1;
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
        $this->quantity  = $this->procedure->quantity;
    }

    public function saveData()
    {
        $validatedData = $this->validate();
        $validatedData = collect($validatedData)->merge(['encounter_id' => $this->encounter->id])->toArray();

        if ($this->procedure) {
            $this->authorize('procedure-update');

            if ($this->updateService()) {
                $this->procedure->update($validatedData);

                $this->clearForm();
                $message = ['text' => 'Data saved successfully', 'type' => 'success'];
                $this->emitUp('notification', $message);
            } else {
                $this->clearForm();
                $message = ['text' => 'Can not edit this item has already been billed', 'type' => 'danger'];
                $this->emitUp('notification', $message);
            }
        } else {
            $this->authorize('procedure-create');
            Procedure::create($validatedData);

            $this->clearForm();
            $message = ['text' => 'Data saved successfully', 'type' => 'success'];
            $this->emitUp('notification', $message);
        }
    }

    public function clearForm()
    {
        $this->form_flag = 0;
        $this->service_id = null;
        $this->result = null;
        $this->quantity = null;
        $this->procedure = null;
    }

    public function updateService()
    {
        $order = $this->procedure->orderService->order;

        if ($order) {
            $orderItem = $order->items()->where('service_id', $this->procedure->service_id)->get()->last();

            $service = Service::find($this->service_id);

            if (($orderItem->order->status??false) == 'pending') {
                $orderItem->update([
                    'service_id' => $this->service_id,
                    'sub_total' => $service->price,
                    'total_price' => $service->price * 1,
                    'quantity' => $this->procedure->quantity
                ]);

                return 1;
            }
        }

        return 0;
    }

    public function render()
    {
        $this->authorize('procedure-create');

        return view('livewire.encounter-procedure-form');
    }
}
