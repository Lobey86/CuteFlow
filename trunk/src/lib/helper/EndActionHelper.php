<?php

    function getEndAction($number) {
	$bin =  decbin ($number);
	$a = strlen($bin);
	for($a = strlen($bin);$a<5;$a++) {
		$bin .= 0;
	}
	$array = str_split ($bin);
	return $array;
    }
?>
