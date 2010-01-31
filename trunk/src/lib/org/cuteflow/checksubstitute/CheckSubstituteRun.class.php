<?php
class CheckSubstituteRun {

    public $context;

    public function __construct(sfContext $context) {
        $this->context = $context;
    }

    public function checkRun($days, $from, $to) {
        $hour = date("H",time());
        $hour = $this->checkDate($hour);
        $from = $this->checkDate($from);
        $to = $this->checkDate($to);
        
        if($this->checkDays($days) == 1) {
            if($hour >= $from AND $hour <= $to) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function checkDays($days) {
        $result = array();
        $result = $this->getRunningDays($days, 7);
        $dateDay = getdate();
        $today = strtolower($dateDay['weekday']);
        $write = 0;
        switch($today) {
            case 'monday':
                if($result[6] == 1) {
                    $write = 1;
                }
                break;
            case 'tuesday':
                if($result[5] == 1) {
                    $write = 1;
                }
                break;
            case 'wednesday':
                if($result[4] == 1) {
                    $write = 1;
                }
                break;
            case 'thursday':
                if($result[3] == 1) {
                    $write = 1;
                }
                break;
            case 'friday':
                if($result[2] == 1) {
                    $write = 1;
                }
                break;
            case 'saturday':
                if($result[1] == 1) {
                    $write = 1;
                }
                break;
            case 'sunday':
                if($result[0] == 1) {
                    $write = 1;
                }
                break;
        }
        return $write;
    }

    public function checkDate($date) {
        $firstChar = substr($date, 0, 1);
        if($firstChar == 0) {
            return substr($date, 1, 2);
        }
        else {
            return $date;
        }
    }

    public function getRunningDays($number, $count = 5) {
        $bin =  decbin ($number);
        
	$a = strlen($bin);
	for($a = strlen($bin);$a<$count;$a++) {
		$bin = '0' . $bin;
	}
	$array = str_split ($bin);
	return $array;
    }



}
?>
