<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Order;
use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PatientVisit extends BaseChart
{
    protected $duration;
    protected const weeks = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Suturday', 'Sunday'];
    protected const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    public function __construct()
    {
        $duration = request()->duration;

        if ($duration == 'day') {
            $this->duration = now()->daysInMonth;
        } else if ($duration == 'week') {
            $this->duration = 7;
        } else if ($duration == 'month') {
            $this->duration = 12;
        }
    }
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $ordersMaleData = $this->getData('male');
        $ordersFemaleData = $this->getData('female');

        return Chartisan::build()
            ->labels($ordersMaleData[1])
            ->dataset('Male', $ordersMaleData[0])
            ->dataset('Female', $ordersFemaleData[0]);
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
            ->select(DB::raw('count(id) as `data`'),DB::raw("DATE_FORMAT(created_at, '%M') month"))
            ->groupby('month')
            ->orderBy('created_at', 'asc')
            ->get();

            $labels = $ordersData->pluck('month')
            ->map(function ($item, $index)
            {
                return $item;
            });

            return [array_slice($ordersData->pluck('data')->toArray(), -12), $labels->toArray()];

        } else if ($this->duration == 7) {
            $ordersData = Order::complete()
            ->whereBetween('created_at', [now()->startOfWeek(Carbon::MONDAY), now()->endOfWeek(Carbon::SUNDAY)])
            ->whereHas('patient', function (Builder $query) use ($gender)
            {
                $query->where('gender', $gender);
            })
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as data'))
            ->groupBy('date')
            ->get();

            $labels = $ordersData
            ->map(function ($item, $index)
            {
                return Carbon::parse($item['date'])->format('l');
            });

            return [($ordersData->pluck('data')->toArray()), $labels->toArray()];


        } else if ($this->duration > 20) {
            $ordersData = Order::complete()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereHas('patient', function (Builder $query) use ($gender)
            {
                $query->where('gender', $gender);
            })
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as data'))
            ->groupBy('date')
            ->get();

            $labels = $ordersData
            ->map(function ($item, $index)
            {
                return Carbon::parse($item['date'])->format('d M | D');
            });

            return [array_slice($ordersData->pluck('data')->toArray(), -now()->daysInMonth), $labels->toArray()];
        }
    }
}
