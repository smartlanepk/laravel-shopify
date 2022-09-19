<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChargesTableExpiresOn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('shopify')->table('charges', function (Blueprint $table) {
            $table->timestamp('expires_on')->after('cancelled_on')->nullable();
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
            $table->dropColumn(['expires_on']);
        });
    }
}
