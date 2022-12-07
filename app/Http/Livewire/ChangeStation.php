<?php

namespace App\Http\Livewire;

use App\Models\Station;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ChangeStation extends Component
{
    public $station_id;
    public $stations = [];

    public function mount()
    {
        $this->station_id = request()->user()->station_id;
        $this->stations = Station::all();
    }

    public function updatedStationId($value)
    {
        request()->user()->update([
            'station_id' => $value
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.change-station');
    }
}
