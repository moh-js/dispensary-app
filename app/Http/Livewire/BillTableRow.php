<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BillTableRow extends Component
{
    public $item;
    public $key;
    public $payment;

    public function mount()
    {
        $this->payment = $this->item->payment_type;
    }

    public function updatedPayment()
    {
        $sessionName = $this->item->order->patient->patient_id.'session'.$this->item->id;
        session([$sessionName => true]);
        $this->item->update([
            'payment_type' => $this->payment
        ]);
    }

    public function render()
    {
        return view('livewire.bill-table-row');
    }
}
