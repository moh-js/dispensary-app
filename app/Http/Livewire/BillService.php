<?php

namespace App\Http\Livewire;

use App\Models\Unit;
use App\Models\Service;
use Livewire\Component;
use App\Models\ServiceCategory;

class BillService extends Component
{
    public $services = [];
    public $units = [];
    public $serviceCategory;
    public $patient;
    public $category_id;
    public $quantity = 1;
    public $service_id;
    public $order;
    public $disabledServiceInput = 0;
    public $totalPrice = 0;

    public function mount()
    {
        $this->serviceCategory = ServiceCategory::all();
        $this->units = Unit::where('name', '!=', 'store')->get();
        $this->category_id = old('category');
        $this->service_id = old('service');
        $this->quantity = old('quantity');

        $this->updatedCategoryId();
        $this->changeTotalPrice();
    }

    public function updatedCategoryId()
    {
        $this->disabledServiceInput = 1;
        $this->services = Service::where('service_category_id', $this->category_id)->get();
        $this->disabledServiceInput = 0;
    }

    public function updatedQuantity()
    {
        $this->changeTotalPrice();
    }


    public function updatedServiceId()
    {
        $this->changeTotalPrice();
    }

    public function changeTotalPrice()
    {
        $service = Service::find($this->service_id);

        if (!$this->quantity) {
            $this->totalPrice = 0;
        } else {
            $this->totalPrice = ($service->price??0) * ($this->quantity);
        }

    }

    public function render()
    {
        return view('livewire.bill-service');
    }
}
