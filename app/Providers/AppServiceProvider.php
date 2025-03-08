<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Cashier::useCustomerModel(User::class);
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Accept terms and conditions')
                ->line('Click the button below to confirm that you have had the opportunity to read the attached Terms and Conditions and Privacy Policy and accept the terms and conditions stated within these documents.')
                ->attach('files/Disclaimer.pdf')
                ->attach('files/Privacy.pdf')
                ->action('Verify Email Address', $url);
        });
    }
}
