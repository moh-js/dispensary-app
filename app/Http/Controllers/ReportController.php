<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Order;
use App\Models\Ledger;
use Mpdf\MpdfException;
use App\Models\Encounter;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use PDF;

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
            $query/* ->whereHas('items', function (Builder $query) use ($name)
            {
                $query->whereHas('service', function (Builder $query) use ($name)
                {
                    $query->where('name', 'like', "%$name%");
                });
            }) */
            ->with('items', function ($query) use ($name)
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

        $orders = $orders->filter(function ($value, $key)
        {
            return $value->items??false;
        });

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
        $request->validate([
            "category" => ['nullable', 'integer'],
            "payment_type" => ['required', 'string'],
            "datetimes" => ['string', 'required'],
        ]);

        $status = $request->status??'complete';

        $category_id = $request->category;
        $datetimes = explode(' - ', $request->datetimes);
        $start_date = Carbon::parse($datetimes[0]);
        $end_date = Carbon::parse($datetimes[1]);
        $datetimes = $start_date->format('dS M Y'). ' - ' .$end_date->format('dS M Y');

        $orders = Order::query()
        ->where('status', $status)
        ->when(($start_date && $end_date), function ($query) use ($end_date, $start_date)
        {
            $query->whereBetween('updated_at', [$start_date, $end_date]);
        })
        ->with('items', function ($query) use ($request, $category_id)
        {
            if ($request->payment_type == 'all' && $category_id) {
                $query->where('service_category_id', $category_id);
            }elseif ($request->payment_type == 'all' && !$category_id) {
                $query;
            } elseif($request->payment_type != 'all' && $category_id) {
                $query->where('payment_type', $request->payment_type)->where('service_category_id', $category_id);;
            }
        })
        ->orderBy('updated_at', 'asc')
        ->get();

        if ($request->submit == 'PDF') {
            try {
                $data = ['orders' => $orders, 'dates' => $datetimes, 'status' => $status];

                PDF::chunkLoadView('<html-separator/>', 'pdf.cash-report', [], $data ,[
                'default_font_size' => '8'
                ])->stream();

            } catch (MpdfException $e) {
                // Process the exception, log, print etc.
                flash()->error($e->getMessage());
                return back()->withInput();
            }
        } elseif ($request->submit == 'View') {
            return view('reports.cash', [
                'orders' => $orders,
                'name' => null,
                'when' => null,
            ]);
        } else {
            flash()->error('Invalid input');
            return redirect()->back();
        }


    }

    public function inventoryLedgerAdavancePage()
    {
        return view('reports.advance.ledgers', [
            'units' => Unit::all()
        ]);
    }

    public function inventoryLedgerAdavance(Request $request)
    {
        $request->validate([
            "service_name" => ['required', 'string'],
            "unit" => ['required', 'string'],
            "datetimes" => ['string', 'required'],
        ]);

        $name = $request->service_name;
        $unit = $request->unit;
        $datetimes = explode(' - ', $request->datetimes);
        $start_date = Carbon::parse($datetimes[0]);
        $end_date = Carbon::parse($datetimes[1]);
        $datetimes = $start_date->format('dS M Y'). ' - ' .$end_date->format('dS M Y');

        $ledgers = Ledger::query()
        ->when($name != 'All', function ($query) use ($name)
        {
            $query->whereHas('item', function (Builder $query) use ($name)
            {
                $query->where('name', 'like', "%$name%");
            });
        })
        ->when($unit != 'all', function ($query) use ($unit)
        {
            $query->whereHas('unit', function (Builder $query) use ($unit)
            {
                $query->where('slug', $unit);
            });
        })
        ->when(($start_date && $end_date), function ($query) use ($end_date, $start_date)
        {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        })
        ->orderBy('created_at', 'asc');

        if ($request->submit == 'PDF') {
            try {
                $data = ['ledgers' => $ledgers->get(), 'dates' => $datetimes];

                PDF::chunkLoadView('<html-separator/>', 'pdf.inventory-ledger-report', $data, [] ,[
                'default_font_size' => '10'
                ])->stream();

            } catch (MpdfException $e) {
                // Process the exception, log, print etc.
                flash()->error($e->getMessage());
                return back()->withInput();
            }

        } elseif ($request->submit == 'View') {
            return view('reports.ledgers', [
                'ledgers' => $ledgers->paginate(20),
                'name' => $name,
                'unit' => $unit,
                'when' => null,
            ]);
        } else {
            flash()->error('Invalid input');
            return redirect()->back();
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

    public function patientVisitPage(Request $request)
    {
        $this->authorize('report-patient-visit-view');

        $when = $request['when']??'today';
        $name = $request['name'];

        $encounters = Encounter::query()
        ->when($name, function ($query) use ($name)
        {
            $query->whereHas('patient', function (Builder $query) use ($name)
            {
                $query
                ->where('first_name', 'like', "%$name%")
                ->orWhere('middle_name', 'like', "%$name%")
                ->orWhere('last_name', 'like', "%$name%")
                ->orWhere('patient_id', 'like', "%$name%");
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

        return view('reports.patient', [
            'encounters' => $encounters,
            'name' => $name,
            'when' => $when,
        ]);

    }

    public function patientVisitSearch(Request $request)
    {
        $this->authorize('report-cash-view');

        return redirect()->route('patient-visit.index', [
            'name' => $request->name,
            'when' => $request->when,
        ]);
    }

    public function patientVisitAdavancePage()
    {
        return view('reports.advance.patient', [
            'units' => Unit::all()
        ]);
    }

    public function patientVisitAdavance(Request $request)
    {
        $request->validate([
            "name" => ['required', 'string'],
            "datetimes" => ['string', 'required'],
        ]);

        $name = $request->service_name;
        $datetimes = explode(' - ', $request->datetimes);
        $start_date = Carbon::parse($datetimes[0]);
        $end_date = Carbon::parse($datetimes[1]);
        $datetimes = $start_date->format('dS M Y'). ' - ' .$end_date->format('dS M Y');

        $encounters = Encounter::query()
        ->when($name != 'All', function ($query) use ($name)
        {
            $query->whereHas('patient', function (Builder $query) use ($name)
            {
                $query
                ->where('first_name', 'like', "%$name%")
                ->orWhere('middle_name', 'like', "%$name%")
                ->orWhere('last_name', 'like', "%$name%")
                ->orWhere('patient_id', 'like', "%$name%");
            });
        })
        ->when(($start_date && $end_date), function ($query) use ($end_date, $start_date)
        {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        })
        ->orderBy('created_at', 'asc');

        if ($request->submit == 'PDF') {
            try {
                $data = ['encounters' => $encounters->get(), 'dates' => $datetimes];

                PDF::chunkLoadView('<html-separator/>', 'pdf.patient-report', $data, [] ,[
                'default_font_size' => '10',
                'format' => 'a4',
                'orientation' => 'L'
                ])->stream();

            } catch (MpdfException $e) {
                // Process the exception, log, print etc.
                flash()->error($e->getMessage());
                return back()->withInput();
            }

        } elseif ($request->submit == 'View') {
            return view('reports.patient', [
                'encounters' => $encounters->paginate(20),
                'name' => $name,
                'when' => null,
            ]);
        } else {
            flash()->error('Invalid input');
            return redirect()->back();
        }
    }
}
