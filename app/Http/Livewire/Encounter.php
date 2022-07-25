<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Prescription;
use App\Models\Investigation;
use App\Models\Procedure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Encounter extends Component
{
    use AuthorizesRequests;

    public $encounter;
    public $investigation;
    public $procedure;
    public $message = null;
    public $lab_flag = 0;
    public $general_flag = 0;
    public $procedure_flag = 0;
    public $signs_flag = 0;
    public $allergies_flag = 0;
    public $medical_flag = 0;
    public $bill_flag = 0;
    public $prescription_flag = 0;

    protected function getListeners()
    {
        return [
            'notification' => 'notify',
            'removeInvestigation',
            'removePrescription',
            'removeProcedure'
        ];
    }

    public function mount()
    {
        $flag = session($this->encounter->patient->patient_id);
        $this->changeFlag($flag);
    }

    public function changeFlag($flag = null)
    {
        session([$this->encounter->patient->patient_id => $flag]);

        if($flag) {

            $flag = $flag.'_flag';

            $this->lab_flag = 0;
            $this->general_flag = 0;
            $this->signs_flag = 0;
            $this->allergies_flag = 0;
            $this->medical_flag = 0;
            $this->bill_flag = 0;
            $this->procedure_flag = 0;
            $this->form_flag = 0;
            $this->prescription_flag = 0;

            $this->$flag = 1;
        }

    }

    public function notify($message)
    {
        if ($message['type'] == 'success') {
            flash()->success($message['text']);
            return redirect()->route('encounter', $this->encounter->name);
        }
        session()->flash('message', $message);
    }

    public function removeInvestigation($investigation_id)
    {
        $this->authorize('investigation-delete');

        $investigation = Investigation::find($investigation_id);
        $name = $investigation->service->name;

        if (($investigation->orderService->order->status??false) != 'completed') {
            $investigation->delete();
            $this->encounter = $this->encounter->fresh();
            $message = ['text' => "$name removed successfully", 'type' => 'success'];
            session()->flash('message', $message);

        } else {
            $message = ['text' => 'Cannot remove this item is already billed', 'type' => 'danger'];
            session()->flash('message', $message);
        }

    }

    public function removePrescription($prescription_id)
    {
        $this->authorize('prescription-delete');

        $prescription = Prescription::find($prescription_id);
        $name = $prescription->service->name;

        if (($prescription->orderService->order->status??false) != 'completed') {
            $prescription->delete();

            $this->encounter = $this->encounter->fresh();

            $message = ['text' => "$name removed successfully", 'type' => 'success'];
            session()->flash('message', $message);
        } else {
            $message = ['text' => 'Cannot remove this item is already billed', 'type' => 'danger'];
            session()->flash('message', $message);
        }

    }

    public function removeProcedure($procedure_id)
    {
        $this->authorize('procedure-delete');

        $procedure = Procedure::find($procedure_id);
        $name = $procedure->service->name;

        if (($procedure->orderService->order->status??false) != 'completed') {
            $procedure->delete();
            $this->encounter = $this->encounter->fresh();

            $message = ['text' => "$name removed successfully", 'type' => 'success'];
            session()->flash('message', $message);
            
        } else {
            $message = ['text' => 'Cannot remove this item is already billed', 'type' => 'danger'];
            session()->flash('message', $message);
        }

    }

    public function render()
    {
        $this->authorize('encounter-view');

        return view('livewire.encounter');
    }
}
