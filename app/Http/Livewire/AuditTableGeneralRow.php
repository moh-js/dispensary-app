<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AuditTableGeneralRow extends Component
{
    public $audit;
    public $sr;
    public $created = 'primary';
    public $updated = 'warning';
    public $deleted = 'danger';

    public function render()
    {
        return view('livewire.audit-table-general-row');
    }
}
