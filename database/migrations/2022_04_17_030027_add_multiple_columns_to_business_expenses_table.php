<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnsToBusinessExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_expenses', function (Blueprint $table) {
            $table->boolean('is_paid')->default(0)->after('amount');
            $table->date('date')->default(now())->after('is_paid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_expenses', function (Blueprint $table) {
            $table->dropColumn('is_paid');
            $table->dropColumn('date');
        });
    }
}
