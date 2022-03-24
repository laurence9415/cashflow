<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessExpense extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const STATUS_IN = 'In';
    public const STATUS_OUT = 'Out';

    public static function statuses()
    {
        return [
            self::STATUS_IN => self::STATUS_IN,
            self::STATUS_OUT => self::STATUS_OUT
        ];
    }
}
