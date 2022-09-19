<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPlanToShopsTableAndChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('shopify')->table('charges', function (Blueprint $table) {
            // Linking
            $table->integer('plan_id')->unsigned()->nullable();
            $table->foreign('plan_id')->references('id')->on('plans');
        });

        Schema::connection('shopify')->table('shops', function (Blueprint $table) {
            // Linking
            $table->integer('plan_id')->unsigned()->nullable();
            $table->foreign('plan_id')->references('id')->on('plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('shopify')->table('charges', function (Blueprint $table) {
            // @codeCoverageIgnoreStart
            if (DB::connection('shopify')->getDriverName() != 'sqlite') {
                $table->dropForeign(['plan_id']);
            }
            // @codeCoverageIgnoreEnd

            $table->dropColumn(['plan_id']);
        });

        Schema::connection('shopify')->table('shops', function (Blueprint $table) {
            // @codeCoverageIgnoreStart
            if (DB::connection('shopify')->getDriverName() != 'sqlite') {
                $table->dropForeign(['plan_id']);
            }
            // @codeCoverageIgnoreEnd

            $table->dropColumn(['plan_id']);
        });
    }
}
