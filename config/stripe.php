<?php

return [
    'stripe_publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
    'stripe_secret_key' => env('STRIPE_SECRET_KEY'),

    'product' =>[
            'id' => 'prod_RsQXCS3leLObxy',
        'name' => 'Classic Quartz Watch',
        'description' => 'Water resistant, Stop watch, Alarm features.',
        'price' => 1000,
    ]
];
?>