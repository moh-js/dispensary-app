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
    public $action;

    public function mount()
    {

    }

    public function updatingWhen()
    {
        $this->resetPage();
    }

    public function render()
    {
        $function = $this->function;

        $audits = $this->$function()
        ->when($this->action, function ($query)
        {
            $query->where('event', $this->action);
        })
        ->paginate(20);
        return view('livewire.audit-table', [
            'audits' => $audits
        ]);
    }
}
