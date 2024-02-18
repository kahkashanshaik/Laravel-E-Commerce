<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Traits\ReportTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    use ReportTrait;
    public function customers() {
        $query = Customer::query();
        return $this->prepareDataForBarChart($query, "Customers By Day");
    }

    public function orders() {
        $query = Order::query();
        return $this->prepareDataForBarChart($query, "Orders By Day");
    }

    private function prepareDataForBarChart($query, $label) {
        $fromdate = $this->getFromDate() ?: Carbon::now()->subDays(30);
        $query
            ->select([DB::raw('CAST(created_at as DATE) AS day'), DB::raw('COUNT(created_at) AS count')])
            ->groupBy(DB::raw('CAST(created_at as DATE)'));
        if($fromdate) {
            $query->where('created_at','>', $fromdate);
        }
        $records = $query->get()->keyBy('day');

        $days = [];
        $labels = [];
        $now = Carbon::now();
        while($fromdate < $now) {
            $key = $fromdate->format('Y-m-d');
            $labels[] = $key;
            $fromdate = $fromdate->addDay(1);
            $days[] = isset($records[$key]) ? $records[$key]['count'] :0;
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => $label,
                'backgroundColor' => '#f87979',
                'data' => $days
            ]]
        ];
    }
}
