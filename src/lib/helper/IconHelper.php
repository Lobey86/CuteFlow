<?php

    function AddStateIcon($state) {
        switch ($state) {
            case 'WAITING':
                return '<img src="/images/icons/hourglass.png" />';
            break;
            case 'SKIPPED':
                return '<img src="/images/icons/state_skip.png" />';
            break;
            case 'USERAGENTSET':
                return '<img src="/images/icons/state_skip.png" />';
            break;
        }

    }

?>
