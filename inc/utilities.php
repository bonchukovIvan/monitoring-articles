<?php

trait WbsmdUtilities {

    function geometric_average($a) {  
        foreach($a as $i=>$n) $mul = $i == 0 ? $n : $mul*$n;  
        return pow($mul,1/count($a));  
    }


    
    function wbsmd_convert_to_percents($f, $s) {
        return number_format((float)($f/$s)*100, 2, '.', '');
    }
    function wbsmd_calculate_number_of_months($f, $s) {
        return number_format((float)($f/$s)*100, 2, '.', '');
    }

    function wbsmd_dates_check($data) {
        if (!$data) {
            return 0;
        }
        $counter = 0;
        for($i = 0; $i < count($data); $i++ ) {
    
            if (!isset($data[$i+1])) {
                continue;
            }
            $date1 = new DateTime($data[$i]->created);
            $date2 = new DateTime($data[$i+1]->created);
        
            $interval = $date1->diff($date2);
            if ($interval->days >= 10) {
                $counter++;
            }
        }
        return $counter;
    }
}