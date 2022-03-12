<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cashflow extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilterData(Builder $builder, Request $request)
    {
        return $builder
            ->when($request->filled('month'), function ($query) use ($request) {
                $query->whereMonth('date', $request->month);
            }, function ($query) {
                $query->whereMonth('date', now()->format('m'));
            })
            ->when($request->filled('year'), function ($query) use ($request) {
                $query->whereYear('date', $request->year);
            }, function ($query) {
                $query->whereYear('date', now()->format('Y'));
            });
    }
}
