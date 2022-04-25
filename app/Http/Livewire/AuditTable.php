<?php

namespace App\Http\Livewire;

use App\Traits\AuditBuilder;
use Livewire\Component;
use Livewire\WithPagination;

class AuditTable extends Component
{
    use AuditBuilder;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $function;

    public function mount()
    {

    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $function = $this->function;
        
        $audits = $this->$function()->paginate(20);
        return view('livewire.audit-table', [
            'audits' => $audits
        ]);
    }
}
