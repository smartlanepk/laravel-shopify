<?php

namespace OhMyBrew\ShopifyApp\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Responsible for reprecenting a shop record.
 */
class ShopWebhooksInstallerLogs extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uri',
        'request',
        'request_time',
        'response',
        'response_time',
        'request_ip',
        'status_code',
        'shop_id',
    ];
}
