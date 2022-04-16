<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessExpense;
use Illuminate\Http\Request;

class BusinessExpenseUpdateIsPaidController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Business $business, BusinessExpense $businessExpense)
    {
        $businessExpense->update(['is_paid' => !$businessExpense->is_paid]);
        return response('', 200);
    }
}
