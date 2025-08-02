@php
    $prefs = $user->preferences;
    const RECEIVE_RESULTS = 0b00000001;
    const RECEIVE_TRIALS = 0b00000010;
    const RECEIVE_NEWS = 0b00000100;

    if($prefs & RECEIVE_RESULTS) {
        $receiveResults ="on";
    } else {
        $receiveResults ="";
    }

    $prefs & RECEIVE_RESULTS ? $receiveResults = "checked" : $receiveResults = "";
    $prefs & RECEIVE_TRIALS ? $receiveTrials = "checked" : $receiveTrials = "";
    $prefs & RECEIVE_NEWS ? $receiveNews = "checked" : $receiveNews = "";

//    dump($receiveResults);
@endphp


<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Email Notifications') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Choose whether you\'d like to receive notifications of results, future events etc. via email. ') }}
        </p>
    </header>

    <form method="post" action="{{ route('preferences.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <div>
            <x-input-label for="receive_emails" :value="__('Receive notifications')" />
            <input type="checkbox" id="receive_emails" name="receive_emails" {{$receiveResults}} class="mt-1 block"  />
        </div>

{{--        <div>--}}
{{--            <x-input-label for="receive_trials" :value="__('New trials published')" />--}}
{{--            <input type="checkbox" id="receive_trials" name="receive_trials"  {{$receiveTrials}}  class="mt-1 block"  />--}}
{{--        </div>--}}

{{--        <div>--}}
{{--            <x-input-label for="receive_news" :value="__('General')" />--}}
{{--            <input type="checkbox" id="receive_news" name="receive_news"  {{$receiveNews}}  class="mt-1 block"  />--}}
{{--        </div>--}}


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

        </div>
    </form>
</section>
