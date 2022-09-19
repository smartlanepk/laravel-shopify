<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopWebhooksPostDataLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('shopify')->create('shop_webhooks_installer_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('uri');
            $table->longText('request');
            $table->dateTime('request_time')->nullable();
            $table->longText('response');
            $table->dateTime('response_time')->nullable();
            $table->string('request_ip')->nullable();
            $table->string('status_code')->nullable();
            $table->integer('shop_id')->nullable();
            $table->longText('comments')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('shopify')->dropIfExists('shop_webhooks_post_data_logs');
        Schema::connection('shopify')->dropIfExists('shop_webhooks_install_logs');
        Schema::connection('shopify')->dropIfExists('shop_webhooks_installer_logs');
    }
}
