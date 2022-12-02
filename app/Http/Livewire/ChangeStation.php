<?php

namespace App\Http\Livewire;

use App\Models\Station;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ChangeStation extends Component
{
    public $station;
    public $stations = [];

    public function mount()
    {
        $this->station = request()->user()->station;
        $this->stations = Station::all();
    }

    public function updatedStation($value)
    {
        request()->user()->update([
            'station' => $value
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.change-station');
    }
}
