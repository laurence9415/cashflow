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
            ->whereYear('created_at', now()->format('Y'))
            ->groupBy(DB::raw("CONCAT(year(date), '-', month(date))"))
            ->orderBy(DB::raw("CONCAT(year(date), '-', month(date))"))
            ->get();

        $overAllOut = number_format($summaries->sum('out'), 2);
        $overAllIn = number_format($summaries->sum('in'), 2);

        $chartData = [
            ["Date", "In", "Out"]
        ];

        foreach ($summaries as $summary) {
            array_push($chartData, [$summary->date, floatval($summary->in) ?? 0, abs(floatval($summary->out)) ?? 0]);
        }

        return ['overall_out' => $overAllOut, 'overall_in' => $overAllIn, 'chart_data' => $chartData];
    }
}
