<?php

echo $text['workflow'][0] . ' ' . $text['workflow'][1] . "\n";

echo $url = url_for('layout/linklogin',true).'/versionid/'.$workflowverion.'/workflowid/'.$workflow. '/userid/'.$userid.'/window/edit';
echo "\n\n";

foreach($slots as $slot) {

    $fields = $slot['fields'];
    echo $text['workflow'][2] . ': ' .  $slot['slotname'] . "\n\n";

    foreach($fields as $field) {
        echo $field['fieldname'] . ': ';


        if($field['type'] == 'COMBOBOX') {
            foreach($field['items'] as $item) {
                if($item['value'] == 1) {
                    echo $item['name'];
                }
            }
        }
        else if($field['type'] == 'RADIOGROUP') {
            foreach($field['items'] as $item) {
                if($item['value'] == 1) {
                    echo $item['name'];
                }
            }
        }
        else if ($field['type'] == 'CHECKBOXGROUP') {
            foreach($field['items'] as $item) {
                if($item['value'] == 1) {
                    echo $item['name'] . '; ';
                }
            }
        }
        else if ($field['type'] == 'FILE') {
            if($slot['isdisabled'] == 0) {
                echo $field['items']['plainurl'];
            }
        }
        else if ($field['type'] == 'CHECKBOX') {
            if($field['items']['value'] == 1) {
                echo $text['workflow'][3];
            }
            else {
                echo $text['workflow'][4];
            }
        }
        else if ($field['type'] == 'TEXTAREA'){
            if($field['items']['contenttype'] == 'html') {
                echo strip_tags($field['items']['value']);
            }
            else {
                echo $field['items']['value'];
            }

        }
        else {
            echo $field['items']['value'];
        }
        echo "\n";
    }


echo "\n\n\n";
}


?>