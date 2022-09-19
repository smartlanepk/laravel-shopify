<?php

namespace OhMyBrew\ShopifyApp\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use OhMyBrew\ShopifyApp\Models\Shop;
use GuzzleHttp\Exception\RequestException;
use OhMyBrew\ShopifyApp\Models\ShopWebhooksInstallerLogs;

/**
 * Responsible for managing webhooks.
 */
class WebhookManager
{
    /**
     * The shop.
     *
     * @var \OhMyBrew\ShopifyApp\Models\Shop
     */
    protected $shop;

    /**
     * The shop API.
     *
     * @var \OhMyBrew\BasicShopifyAPI
     */
    protected $api;

    /**
     * Cached shop webhooks result.
     *
     * @var array
     */
    protected $shopWebhooks;

    /**
     * Create a new job instance.
     *
     * @param object $shop The shop object
     *
     * @return void
     */
    public function __construct($shop)
    {
        $this->shop = $shop;
        $this->api = $this->shop->api();
    }

    /**
     * Gets the webhooks present in the shop.
     *
     * @return array
     */
    public function shopWebhooks()
    {
        if (!$this->shopWebhooks) {
            $this->shopWebhooks = $this->api->rest(
                'GET',
                '/admin/webhooks.json',
                [
                    'limit'  => 250,
                    'fields' => 'id,address',
                ]
            )->body->webhooks;
        }

        return $this->shopWebhooks;
    }

    /**
     * Gets the webhooks present in the configuration.
     *
     * @return array
     */
    public function configWebhooks()
    {
        return Config::get('shopify-app.webhooks');
    }

    /**
     * Check if webhook is in the shop (by address).
     *
     * @param array $webhook The webhook
     *
     * @return bool
     */
    public function webhookExists(array $webhook)
    {
        $shopWebhooks = $this->shopWebhooks();
        foreach ($shopWebhooks as $shopWebhook) {
            if ($shopWebhook->address === $webhook['address']) {
                // Found the webhook in our list
                return true;
            }
        }

        return false;
    }

    /**
     * Creates webhooks (if they do not exist).
     *
     * @return array
     */
    public function createWebhooks()
    {
        $configWebhooks = $this->configWebhooks();

        // Create if it does not exist
        $created = [];
        // For response logging purposes
        $requestLog = [
            'uri' =>  $this->shop->shopify_domain . "/admin/webhooks.json"
        ];
        $URI = $this->shop->shopify_domain . "/admin/webhooks.json";

        $created_webhooks = [];
        $logRecord = null;
        $response = null;
        $requestTime = null;
        $payload = null;
        $payload_request = [];

        foreach ($configWebhooks as $webhook) {

            // Check if the required webhook exists on the shop
            if (!$this->webhookExists($webhook)) {

                // It does not... create the webhook
                $requestTime = Carbon::now();
                $payload = ['webhook' => $webhook];
                $payload_request[] = $payload;

                    $response = $this->api->rest('POST', '/admin/webhooks.json', $payload);
                        if(isset($response->errors->body)) {
                            $created_webhooks[] = $response->errors->body;
                        }

                        if (isset($response->body->webhook)) {
                            $created_webhooks[] = $response->body->webhook;
                        }

                $created[] = $webhook;
            }
        }

        $logRecord = [
            [
                'uri' => $URI,
                'request' => json_encode(array_merge($requestLog, $payload_request)),
                'request_time' => $requestTime,
                'response' => json_encode($created_webhooks),
                'request_ip' => Request::ip(),
                'shop_id' => $this->shop->id,
            ]
        ];

        ShopWebhooksInstallerLogs::insert(
            $logRecord
        );

        return $created;
    }

    /**
     * Deletes webhooks in the shop tied to the app.
     *
     * @return array
     */
    public function deleteWebhooks()
    {
        $shopWebhooks = $this->shopWebhooks();

        $deleted = [];
        foreach ($shopWebhooks as $webhook) {
            // Its a webhook in the config, delete it
            $this->api->rest('DELETE', "/admin/webhooks/{$webhook->id}.json");
            $deleted[] = $webhook;
        }

        // Reset
        $this->shopWebhooks = null;

        return $deleted;
    }

    /**
     * Recreates the webhooks.
     *
     * @return void
     */
    public function recreateWebhooks()
    {
        $this->deleteWebhooks();
        $this->createWebhooks();
    }
}
