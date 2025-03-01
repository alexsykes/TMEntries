<?php

use Illuminate\Support\Str;

return [
    'stripe_secret_key' => env('STRIPE_SECRET'),
    'stripe_key' => env('STRIPE_KEY'),
    'stripe_webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'stripe_webhook_tolerance' => env('STRIPE_WEBHOOK_TOLERANCE'),

];