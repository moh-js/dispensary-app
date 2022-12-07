<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Encounter;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    protected $duration;
    protected const weeks = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Suturday', 'Sunday'];
    protected const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    public function index()
    {
        $allAudits = Audit::where('auditable_type', Patient::class)
        ->orWhere('auditable_type', Encounter::class)
        ->latest()
        ->limit(15)
        ->get();

        return view('dashboard', [
            'activities' => $allAudits
        ]);
    }

    public function getData($gender)
    {
        if ($this->duration === 12) {
            $ordersData = Order::complete()
            ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
            ->whereHas('patient', function (Builder $query) use ($gender)
            {
                $query->where('gender', $gender);
            })
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as data'))
            ->orderBy('date', 'asc')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M')"))
            ->get();

            $ordersData = $ordersData
            ->map(function ($item, $index)
            {
                return ['data' => $item['data'], 'date' => Carbon::parse($item['date'])->format('M')];
            });

            $data = collect();
            foreach (self::months as $month) {
                if ($order = $ordersData->where('date', $month)->first()) {
                    $data->push($order['data']);
                } else {
                    $data->push(0);
                }

            }

            $labels =  self::months;

            return [($data->toArray()), $labels];

        } else if ($this->duration == 7) {
            $ordersData = Order::complete()
            ->whereBetween('created_at', [now()->startOfWeek(Carbon::MONDAY), now()->endOfWeek(Carbon::SUNDAY)])
            ->whereHas('patient', function (Builder $query) use ($gender)
            {
                $query->where('gender', $gender);
            })
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as data'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

            $ordersData = $ordersData
            ->map(function ($item, $index)
            {
                return ['data' => $item['data'], 'date' => Carbon::parse($item['date'])->format('l')];
            });

            $data = collect();
            foreach (self::weeks as $weekDay) {
                if ($order = $ordersData->where('date', $weekDay)->first()) {
                    $data->push($order['data']);
                } else {
                    $data->push(0);
                }
            }

            $labels =  self::weeks;

            return [($data->toArray()), $labels];


        } else if ($this->duration > 20) {
            $ordersData = Order::complete()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereHas('patient', function (Builder $query) use ($gender)
            {
                $query->where('gender', $gender);
            })
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as data'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

            $dataYear = Carbon::parse($ordersData->first()['date'])->year;
            $dataMonth = Carbon::parse($ordersData->first()['date'])->month;
                // return Carbon::parse($item['date'])->format('d M | D');

            $ordersData = $ordersData
            ->map(function ($item, $index)
            {
                return ['data' => $item['data'], 'date' => Carbon::parse($item['date'])->format('d')];
            });

            $data = collect();
            for ($i=1; $i <= $this->duration; $i++) {
                if ($order = $ordersData->where('date', $i)->first()) {
                    $data->push(['data' => $order['data'], 'labels' => now()->setDate($dataYear, $dataMonth, $i)->format('d M | D')]);
                } else {
                    if (now()->setDate($dataYear, $dataMonth, $i) > now()) {
                        $data->push(['data' => null, 'labels' => now()->setDate($dataYear, $dataMonth, $i)->format('d M | D')]);
                    } else {
                        $data->push(['data' => 0, 'labels' => now()->setDate($dataYear, $dataMonth, $i)->format('d M | D')]);
                    }
                }
            }

            return [($data->pluck('data')->toArray()), $data->pluck('labels')->toArray()];
            return $data->toArray();
        }
    }
}
