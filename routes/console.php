<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\Trial;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('getTrialList', function () {
    $url = 'https://batarfi.org/data/getTrialList.php';
    $rawData = (file_get_contents($url, 'r'));
    $trials = json_decode($rawData, true);

    foreach ($trials as $trial) {
        if (DB::table("trials")->where("id", $trial["id"])->exists()) {
            $trialToUpdate = Trial::findOrFail($trial["id"]);

            $attrs = [
                'name' => $trial["name"],
                'classlist' => $trial["classlist"],
                'courselist' => $trial["courselist"],
                'date' => $trial["date"],
                'club' => $trial["club"],
                'updated_at' => NOW(),
            ];
            $trialToUpdate->update($attrs);

        } else {
            Trial::create([
                'id' => $trial["id"],
                'name' => $trial["name"],
                'classlist' => $trial["classlist"],
                'courselist' => $trial["courselist"],
                'date' => $trial["date"],
                'club' => $trial["club"],
            ]);
        }



    }
});
