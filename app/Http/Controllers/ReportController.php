<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\Ledger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use  Meneses\LaravelMpdf\Facades\LaravelMpdf as PDF;


class ReportController extends Controller
{
    /*
    * Hours
    */
    protected $today = 1;
    protected $week = 6;
    protected $month = 29;
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
            if ($when == 'today') {
                $startDate = now()->today();
            } else {
                $startDate = now()->subDays($this->$when);
            }

            $query->whereBetween('created_at', [$startDate, now()]);
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

        $unit = $request['unit'];
        $when = $request['when']??'today';
        $name = $request['name'];

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
            if ($when == 'today') {
                $startDate = now()->today();
            } else {
                $startDate = now()->subDays($this->$when);
            }

            $query->whereBetween('created_at', [$startDate, now()]);
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

        $when = $request['when']??'today';
        $name = $request['name'];

        $orders = Order::query()
        ->where('status', 'completed')
        ->when($name, function ($query) use ($name)
        {
            $query->whereHas('items', function (Builder $query) use ($name)
            {
                $query->whereHas('service', function (Builder $query) use ($name)
                {
                    $query->where('name', 'like', "%$name%");
                });
            });
        })
        ->when($when, function ($query) use ($when)
        {
            if ($when == 'today') {
                $startDate = now()->today();
            } else {
                $startDate = now()->subDays($this->$when);
            }

            $query->whereBetween('updated_at', [$startDate, now()]);
        })
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('reports.cash', [
            'name' => $name,
            'when' => $when,
            'orders' => $orders,
        ]);
    }

    public function cashBookAdvancePage()
    {
        return view('reports.advance.cash');
    }

    public function cashBookAdvance(Request $request)
    {

        $name = $request->service_name;
        $datetimes = explode(' - ', $request->datetimes);
        $start_date = Carbon::parse($datetimes[0]);
        $end_date = Carbon::parse($datetimes[1]);

        if ($request->payment_type == 'all') {
            $item_query_clause = [
                ['payment_type', 'nhif'],
                ['payment_type', 'exempted'],
                ['payment_type', 'cash'],
            ];
        } else {
            $item_query_clause = [['payment_type', $request->payment_type]];
        }

        $orders = Order::query()
        ->where('status', 'completed')
        ->when($name != 'All', function ($query) use ($name)
        {
            $query->whereHas('items', function (Builder $query) use ($name)
            {
                $query->whereHas('service', function (Builder $query) use ($name)
                {
                    $query->where('name', 'like', "%$name%");
                });
            });
        })
        ->when(($start_date && $end_date), function ($query) use ($end_date, $start_date)
        {
            $query->whereBetween('updated_at', [$start_date, $end_date]);
        })
        ->with('items', function ($query) use ($request)
        {
            if ($request->payment_type == 'all') {
                $query;
            } else {
                $query->where('payment_type', $request->payment_type);
            }
        })
        ->orderBy('updated_at', 'desc')
        ->get();

        return $orders;

        if ($request->submit == 'PDF') {
            $pdf = PDF::loadView('pdf.cash-report', [] ,[] ,[
            //   'format' => [80,$pageHeigt],
              'default_font_size' => '10'
            ]);

            $pdfString = $pdf->stream();

            return $pdfString;
        }


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

    public function cashBookSearch(Request $request)
    {
        $this->authorize('report-cash-view');

        return redirect()->route('cash.index', [
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
