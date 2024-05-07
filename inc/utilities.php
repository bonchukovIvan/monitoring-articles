<?php

trait WbsmdUtilities {
    function wbsmd_choice_item_class($result) {

        if ($result <= 10) {
            return 'item--green';
        }
    
        elseif ($result > 10 && $result <= 40) {
            return 'item--orange';
        }
    
        elseif ($result > 40) {
            return 'item--red';
        }
    
        return null;
    }
    
    function wbsmd_convert_to_percents($f, $s) {
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