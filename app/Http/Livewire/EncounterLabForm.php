<?php

namespace App\Http\Livewire;

use App\Models\Investigation;
use App\Models\Service;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class EncounterLabForm extends Component
{
    use AuthorizesRequests;

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
        $this->authorize('investigation-update');

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
            $this->authorize('investigation-update');

            if ($this->updateService($this->investigation)) {
                $this->investigation->update($validatedData);

                $this->clearForm();
                $message = ['text' => 'Data saved successfully', 'type' => 'success'];
                $this->emitUp('notification', $message);
            } else {
                $this->clearForm();
                $message = ['text' => 'Can not edit this item has already been billed', 'type' => 'danger'];
                $this->emitUp('notification', $message);
            }


        } else {
            $this->authorize('investigation-create');
            Investigation::create($validatedData);

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
        $this->investigation = null;
    }

    public function updateService($investigation)
    {
        $order = $investigation->orderService->order;

        if ($order) {
            $orderItem = $order->items()->where('service_id', $investigation->service_id)->get()->last();

            $service = Service::find($this->service_id);

            if (($orderItem->order->status??false) == 'pending') {
                $orderItem->update([
                    'service_id' => $this->service_id,
                    'sub_total' => $service->price,
                    'total_price' => $service->price * 1,
                    'quantity' => 1
                ]);

                return 1;
            }
        }

        return 0;
    }


    public function render()
    {
        $this->authorize('investigation-create');

        return view('livewire.encounter-lab-form');
    }
}
