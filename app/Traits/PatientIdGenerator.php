<?php
namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait PatientIdGenerator
{
    public function generate($patient_id)
    {
        // format must-d-{000ID}
        $id = $patient_id;
        $count = 1;
        $initialLimit = 999;
        $finalLimit = $initialLimit;

        do { // Reset receipt id after it exceed six digits number
            $reset = false;

            if ($id > $initialLimit) { // check if id exceed limit
                $id = $patient_id - $finalLimit;

                if ($id > $initialLimit) { // check if substracted id still exceed the limit number
                    $count = $count+1;
                    $finalLimit = $count*$initialLimit;
                    $reset = true;
                }
            } else {
                $id = $patient_id;
            }
        } while ($reset);

        $date = now()->format('yz');
        $length = strlen($initialLimit);
        $string = substr(str_repeat(0, $length).$id, - $length);

        $patient_id = 'must-d-'.$date.$string;
        return $patient_id;
    }
}
