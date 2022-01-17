<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InventoryCategory;

class InventoryItemList extends Component
{
    use WithPagination;

    public $category;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.inventory-item-list', [
            'items' => Item::withTrashed()
                        ->where('inventory_category_id', $this->category->id)
                        ->when(strlen($this->search) > 0, function ($query)
                        {
                            $query->where('name', 'like', "%{$this->search}%");
                        })
                        ->paginate(20)
        ]);
    }
}
