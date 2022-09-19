<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyChargeIdType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('shopify')->table('charges', function (Blueprint $table) {
            $table->bigInteger('charge_id')->change();
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
            $table->integer('charge_id')->change();
        });
    }
}
