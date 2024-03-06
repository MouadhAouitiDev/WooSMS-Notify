<?php
/*
Plugin Name: WooSMS Notify
Description: Send SMS notifications when WooCommerce products are out of stock.
Version: 1.0
Author: Mouadh Aouiti
*/

require_once 'vendor/autoload.php';
use Twilio\Rest\Client;

add_action('woocommerce_product_set_stock_status', 'send_sms_notification_on_stock_change', 10, 3);

function send_sms_notification_on_stock_change($product_id, $status, $product) {

    if ($status === 'outofstock') {

        $admin_user_id = get_option('admin_user_id');


        $admin_billing_phone = get_user_meta($admin_user_id, 'billing_phone', true);


        if ($admin_billing_phone) {

            $account_sid = 'ACc554ecb834951ab2e472d6929db5c402';
            $auth_token = '8921e2ebe0ed14fba34ae93aa7ac994d';
            $twilio_phone_number = '+18304200110';


            $message = 'Product ' . $product->get_name() . ' is out of stock.';


            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                $admin_billing_phone,
                array(
                    'from' => $twilio_phone_number,
                    'body' => $message
                )
            );

            error_log('SMS notification sent successfully.');
        } else {

            error_log('Admin billing phone number not found.');
        }
    }
}
