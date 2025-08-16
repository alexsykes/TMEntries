<?php

namespace App\Imports;

use App\Http\Controllers\UtilityController;
use App\Models\Entry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EntriesImport implements ToModel, WithHeadingRow
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function model(array $row)
    {
//        dd($row);
            $entryOptions = explode(" - ", $row['class']);

            if (is_numeric($row['membership_number_write_xxx_if_not_known_non_members_can_ride_1_event_per_year_without_membership_please_complete'])) {
                $licence = $row['membership_number_write_xxx_if_not_known_non_members_can_ride_1_event_per_year_without_membership_please_complete'];
            } else {
                $licence = "";
            }

            $xlDate = $row['date_of_birth'];
            $UNIX_DATE = ($xlDate - 25569) * 86400;
            $dob = (gmdate("Y-m-d", $UNIX_DATE));
            $rawName = $row['first_name'] . ' ' . $row['surname'];

            $utilityController= new UtilityController();
            $name = $utilityController->nameize($rawName);

            return new Entry([
                'trial_id' => 146,
                'created_at' => $row['timestamp'],
                'name' => $name,
                'make' => $row['bike_make_and_model'],
                'size' => $row['bike_engine_capacity_numbers_only_please'],
                'status' => 1,
                'dob' => $dob,
                'email' => $row['email_address'],
                'course' => ucfirst(strtolower(trim($entryOptions[0]))),
                'class' => ucfirst(strtolower(trim($entryOptions[1]))),
                'IPaddress' => '',
                'type' => '',
                'licence' => $licence,
            ]);
        }
//    }
}
