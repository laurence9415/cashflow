<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cashflow;
use Illuminate\Http\Request;

class OverallTotalCashflowController extends Controller
{
    public function __invoke(Request $request)
    {
        $totalNiGawasKwarta = Cashflow::where('amount', '<', 0)
            ->filterData($request)
            ->sum('amount');

        $totalNiSulodKwarta = Cashflow::where('amount', '>', 0)
            ->filterData($request)
            ->sum('amount');
        return [
            'totalNiGawasKwarta' => $totalNiGawasKwarta,
            'totalNiSulodKwarta' => $totalNiSulodKwarta
        ];
    }
}
