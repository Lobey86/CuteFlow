<?php echo $text . "\n"?>

<?php
    foreach ($workflow as $wf) {
        echo $wf['name'] . ': ' . url_for('layout/linklogin',true).'/versionid/'.$wf['workflowversion_id'].'/workflowid/'.$wf['workflow_id'] . '/userid/'.$userid. "\n";
    }

?>