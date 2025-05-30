<?php

namespace App\Providers\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReCaptchaV3 implements ValidationRule
{
    public function __construct(
        private ?string $action = null,
        private ?float $minScore = null)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Send a POST request to the google siteverify service to validate the
//        dd($attribute, $value, $fail);
        $siteVerify = Http::asForm()
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha_v3.secretKey'),
                'response' => $value,
            ]);


        // This happens if google denied our request with an error
        if ($siteVerify->failed()) {
            $fail('Google reCAPTCHA was not able to verify the form, please try again.');

            Log::info("Form verify fail IP: " .request()->ip());
            return;
        }

        // This means Google successfully processed our POST request. We still need to check the results!
        if ($siteVerify->successful()) {
            $body = $siteVerify->json();

            // When this fails it means the browser didn't send a correct code. This means it's very likely a bot we should block
            if ($body['success'] !== true) {
                $fail('Your form submission failed the Google reCAPTCHA verification, please try again.');
                Log::info("Recaptcha fail");
                return;
            }

            // Tests pass so proceed
            if ($body['success'] == true) {
                Log::info("Recaptcha Success");
        }

            // When this fails it means the action didn't match the one set in the button's data-action.
            // Either a bot or a code mistake. Compare form data-action and value passed to $action (should be equal).
            if (!is_null($this->action) && $this->action != $body['action']) {
                $fail('The action found in the form didn\'t match the Google reCAPTCHA action, please try again.');

                Log::info("Recaptcha action fail");
                return;
            }

            // If we set a minScore treshold, verify that the spam score didn't go below it
            // More info can be found at: https://developers.google.com/recaptcha/docs/v3#interpreting_the_score
            if (!is_null($this->minScore) && $this->minScore > $body['score']) {
                $fail('The Google reCAPTCHA verification score was too low, please try again.');

                Log::info("Low score fail");
                return;
            }
        }
    }
}