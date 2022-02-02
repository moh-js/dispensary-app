<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Unit;
use App\Models\Order;
use App\Models\Ledger;
use App\Models\OrderService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;


class ReportController extends Controller
{
    /*
    * Hours
    */
    protected $today = 24;
    protected $week = 168;
    protected $month = 730;
    protected $six_month = 4380;
    protected $year = 8760;


    public function dispensingPage(Request $request)
    {
        $this->authorize('report-dispensing-view');

        $when = $request['when']??'today';

        $orders = Order::whereHas('items', function (Builder $query)
        {
            $query->where('service_category_id', 1);
        })
        ->where('status', 'completed')
        ->when($when, function ($query) use ($when)
        {
            $hours = $this->$when;
            $query->whereBetween('created_at', [now()->subHours($hours), now()]);
        })
        ->with(['items'])
        ->get();

        $itemIds = collect();

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if($item->service_category_id == 1) {
                    $itemIds->push($item->service->item_id);
                }
            }
        }

        $items = Item::whereIn('id', $itemIds->unique())->get();

        return view('reports.dispensing', [
            'items' => $items,
            'when' => $when,
            'orders' => $orders
        ]);
    }

    public function inventoryLedgersPage(Request $request)
    {
        $this->authorize('report-inventory-ledger-view');

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
                $query->where('slug', $unit);
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

    public function cashBookPage(Request $request)
    {
        $this->authorize('report-cash-view');

        $items = OrderService::whereHas('order', function (Builder $query)
        {
            $query->where('status', 'completed');
        })->paginate(20);

        return view('reports.cash', [
            'name' => $request->name,
            'when' => $request->when,
            'items' => $items
        ]);
    }

    public function inventoryLedgersSearch(Request $request)
    {
        $this->authorize('report-inventory-ledger-view');

        return redirect()->route('inventory-ledger.index', [
            'name' => $request->name,
            'unit' => $request->unit,
            'when' => $request->when,
        ]);
    }

    public function dispensingSearch(Request $request)
    {
        $this->authorize('report-dispensing-view');

        return redirect()->route('dispensing.index', [
            'when' => $request->when,
        ]);
    }
}
