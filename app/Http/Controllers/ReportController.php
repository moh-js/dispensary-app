<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;


class ReportController extends Controller
{
    protected $today = 24;
    protected $week = 168;
    protected $month = 730;
    protected $six_month = 4380;
    protected $year = 8760;


    public function dispensingPage()
    {
        $this->authorize('reoprt-dispensing-view');

        return view('reports.dispensing');
    }

    public function inventoryLedgersPage(Request $request)
    {
        $this->authorize('reoprt-inventory-ledger-view');

        $unit =  $request['unit'];
        $when =  $request['when'];
        $name =  $request['name'];

        $ledgers = Ledger::query()
        ->when($name, function ($query) use ($name)
        {
            $query->whereHas('item', function (Builder $query) use ($name)
            {
                $query->where('name', 'like', "%$name%");
            });
        })
        ->when($unit, function ($query) use ($unit)
        {
            $query->whereHas('unit', function (Builder $query) use ($unit)
            {
                $query->where('slug', "$unit");
            });
        })
        ->when($when, function ($query) use ($when)
        {
            $hours = $this->$when;
            $query->whereBetween('created_at', [now()->subHours($hours), now()]);
        })
        ->orderBy('id', 'desc')
        ->paginate(20);

        return view('reports.ledgers', [
            'ledgers' => $ledgers,
            'name' => $name,
            'when' => $when,
            'unit' => $unit,
        ]);
    }

    public function inventoryLedgersSearch(Request $request)
    {
        $this->authorize('reoprt-inventory-ledger-view');

        return redirect()->route('inventory-ledger.index', [
            'name' => $request->name,
            'unit' => $request->unit,
            'when' => $request->when,
        ]);
    }
}
