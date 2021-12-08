<?php

namespace App\Http\Livewire;

use App\Models\Patient;
use Livewire\Component;

class Search extends Component
{
    public $patients;
    public $searchResultContainer = 0;
    public $query = '';

    public function mount()
    {

    }

    public function toogleSearchResult()
    {
        $this->searchResultContainer = $this->searchResultContainer == 1? 0:1;
        $this->query = '';
    }

    public function render()
    {
        $this->patients = Patient::when(strlen($this->query) >= 3, function ($query)
        {
            $query->where('patient_id', 'like', "%{$this->query}%");
        })
        ->when(strlen($this->query) < 3, function ($query)
        {
            $query->where('id', 0);
        })
        ->get();
        return view('livewire.search');
    }
}
