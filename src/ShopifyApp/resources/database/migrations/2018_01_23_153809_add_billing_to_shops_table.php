<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingToShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('shopify')->table('shops', function (Blueprint $table) {
            $table->bigInteger('charge_id')->nullable(true)->default(null);
            $table->boolean('grandfathered')->default(false);
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
            // Laravel doesn't seem to support multiple dropColumn commands
            // See: (https://github.com/laravel/framework/issues/2979#issuecomment-227468621)
            $table->dropColumn(['charge_id', 'grandfathered']);
        });
    }
}
