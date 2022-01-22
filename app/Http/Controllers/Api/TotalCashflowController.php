<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cashflow;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TotalCashflowController extends Controller
{
    private static function colors()
    {
        return [
            "blue",
            "indigo",
            "deep-purple",
            "cyan",
            "green",
            "orange",
            "grey darken-1",
        ];
    }

    public function __invoke(Request $request)
    {
        return Cashflow::selectRaw('date, SUM(amount) amount')
            ->groupByRaw('date')
            ->get()
            ->map(function ($data, $key) {
                $color = array_rand(self::colors(), 1);
                return [
                    'color' => $data->amount < -1000 ? 'red' : self::colors()[$color],
                    'name' => $data->amount,
                    'start' => Carbon::parse($data->date)->format('Y-m-d H:m'),
                    'end' => Carbon::parse($data->date)->format('Y-m-d H:m'),
                    'timed' => true,
                    'date' => $data->date
                ];
            });
    }
}
