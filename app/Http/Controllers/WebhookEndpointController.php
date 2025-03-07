<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class WebhookEndpointController extends Controller
{
    //

    public function hook(Request $request){
        http_response_code(400);
        exit();
        $stripe_sk =  config('stripe.stripe_secret_key');
        $endpoint_secret = config('stripe.stripe_webhook_secret');

        $stripe = new \Stripe\StripeClient($stripe_sk);

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }
    }
}
