<?php

namespace App\Imports;

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
            $name = $this->nameize($rawName);

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

    function nameize($str, $a_char = array("'", "-", " "))
    {
        //$str contains the complete raw name string
        //$a_char is an array containing the characters we use as separators for capitalization. If you don't pass anything, there are three in there as default.
        $string = strtolower($str);
        foreach ($a_char as $temp) {
            $pos = strpos($string, $temp);
            if ($pos) {
                //we are in the loop because we found one of the special characters in the array, so lets split it up into chunks and capitalize each one.
                $mend = '';
                $a_split = explode($temp, $string);
                foreach ($a_split as $temp2) {
                    //capitalize each portion of the string which was separated at a special character
                    $mend .= ucfirst($temp2) . $temp;
                }
                $string = substr($mend, 0, -1);
            }
        }
        return ucfirst($string);
    }
//    }
}
