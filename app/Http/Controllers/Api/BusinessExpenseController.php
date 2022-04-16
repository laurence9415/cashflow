<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessExpenseRequest;
use App\Models\Business;
use App\Models\BusinessExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BusinessExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Business $business)
    {
        return $business->expenses()
            ->when($request->filled('date') && $request->date === 'today', fn ($query) => $query->whereDate('created_at', Carbon::today()))
            ->latest()
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BusinessExpenseRequest $request, Business $business)
    {
        $businessExpenseData = $request->validated();
        $businessExpenseData['user_id'] = auth()->id();
        $businessExpenseData['business_id'] = $business->id;

        return BusinessExpense::create($businessExpenseData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
