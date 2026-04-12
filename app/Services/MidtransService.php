<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        $this->configure();
    }

    /**
     * Set configuration for Midtrans.
     */
    public function configure()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Get Snap Token from Midtrans.
     *
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function getSnapToken(array $params)
    {
        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify and parse Midtrans notification.
     *
     * @return Notification|null
     */
    public function verifyCallback()
    {
        try {
            // Midtrans auto-verifies signature in the Notification constructor
            // based on the Server Key set in the configure method.
            return new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Verification Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get transaction status manually.
     *
     * @param string $orderId
     * @return object|array|null
     */
    public function getStatus($orderId)
    {
        try {
            return \Midtrans\Transaction::status($orderId);
        } catch (\Exception $e) {
            Log::error("Midtrans Status Check Failed for ID $orderId: " . $e->getMessage());
            return null;
        }
    }
}
