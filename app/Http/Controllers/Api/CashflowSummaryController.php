<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cashflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashflowSummaryController extends Controller
{
    public function __invoke()
    {

        $summaries = Cashflow::select(DB::raw("CONCAT(year(date), '-', month(date)) as date,sum(CASE WHEN amount > 0 THEN amount END) as 'in', sum(CASE WHEN amount < 0 THEN amount END) as 'out'"))
            ->groupBy(DB::raw("CONCAT(year(date), '-', month(date))"))
            ->orderBy(DB::raw("CONCAT(year(date), '-', month(date))"))
            ->get();

        $overAllOut = $summaries->sum('out');
        $overAllIn = $summaries->sum('in');

        $chartData = [
            ["Date", "In", "Out"]
        ];

        foreach ($summaries as $summary) {
            array_push($chartData, [$summary->date, floatval($summary->in) ?? 0, abs(floatval($summary->out)) ?? 0]);
        }

        return ['overall_out' => $overAllOut, 'overall_in' => $overAllIn, 'chart_data' => $chartData];
    }
}
