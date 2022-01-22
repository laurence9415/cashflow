<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashflowRequest;
use App\Models\Cashflow;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashflowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Cashflow::when($request->filled('date'), function ($query) use ($request) {
            $query->when($request->date === 'today', function ($query) {
                $query->whereDate('date', Carbon::today());
            }, function ($query) use ($request) {
                $query->whereDate('date', $request->date);
            });
        })
            ->when($request->filled('deduction'), function ($query) use ($request) {
                $query->where('amount', $request->deduction ? '<' : '>', 0);
            })
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashflowRequest $request)
    {
        collect($request->validated()['forms'])->each(function ($form) use ($request) {
            $form['date'] = $request->validated()['date'];
            $form['user_id'] = auth()->id();
            Cashflow::create($form);
        });

        return response()->noContent();
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
        return Cashflow::findOrFail($id)->delete();
    }
}
