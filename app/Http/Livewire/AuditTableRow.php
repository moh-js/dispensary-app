<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\InventoryCategory;
use App\Models\ServiceCategory;

class AuditTableRow extends Component
{
    public $audit;
    public $sr;
    public $created = 'primary';
    public $updated = 'warning';
    public $deleted = 'danger';
    public $inventoryCategory;
    public $serviceCategory;
    public $showNewValues = false;
    public $showOldValues = false;

    public function mount()
    {
        $this->inventoryCategory = InventoryCategory::class;
        $this->serviceCategory = ServiceCategory::class;
    }

    public function toggleNewValuesVisibility()
    {
        $this->showNewValues = !$this->showNewValues;
    }

    public function toggleOldValuesVisibility()
    {
        $this->showOldValues = !$this->showOldValues;
    }

    public function render()
    {
        return view('livewire.audit-table-row');
    }
}
