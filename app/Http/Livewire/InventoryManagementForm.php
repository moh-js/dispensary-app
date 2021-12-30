<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Unit;
use Livewire\Component;

class InventoryManagementForm extends Component
{
    public $items;
    public $item_id;
    public $units;
    public $item;
    public $from;
    public $query = null;
    public $type = 'Choose action';
    public $itemsListSection = false;
    public $loading = false;
    public $formSection = false;

    public function mount()
    {
        $this->units = Unit::all();

        if (old('type')) {
            $this->type = old('type');
            $this->showForm(old('item_id'));

            $this->updatedType(1);
        }

        if ($this->item_id) {
            $this->showForm($this->item_id);
        }
    }

    public function updatedType($key)
    {
        if ($this->type == 'sent') {
            $this->units = Unit::whereNotIn('id', [6])->get();
            $this->from = 'Store';
        } else {
            $this->units = Unit::whereIn('id', [6])->get();
            $this->from = null;
        }
    }

    public function search()
    {
        if ($this->query) {
            $this->loading = true;
            $this->items = Item::where('name', 'like', "%{$this->query}%")->get();
            $this->itemsListSection = true;
            $this->loading = false;
        } else {

        }
    }

    public function updatedQuery()
    {
        if (strlen($this->query) > 3) {
            $this->search();
        }
    }

    public function showForm($id)
    {
        $this->loading = true;
        $this->itemsListSection = false;

        $this->item = Item::find($id);
        $this->loading = false;
        $this->formSection = true;
    }

    public function render()
    {
        return view('livewire.inventory-management-form');
    }
}
