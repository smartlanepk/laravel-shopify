<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFreemiumFlagToShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('shopify')->table('shops', function (Blueprint $table) {
            $table->boolean('freemium')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('shopify')->table('shops', function (Blueprint $table) {
            $table->dropColumn(['freemium']);
        });
    }
}
