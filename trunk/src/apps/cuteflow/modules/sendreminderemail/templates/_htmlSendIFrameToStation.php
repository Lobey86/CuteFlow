<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head></head>
    <body>
    <iframe width="100%" frameborder="0" src="<?php echo url_for('workflowedit/saveIFrame',true) . '/versionid/'.$workflowverion.'/workflowid/'.$workflow. '/userid/'.$userid; ?>">
     <?php echo '<form action="' . $serverPath . '/workflowedit/saveIFrame/versionid/'.$workflowverion.'/workflowid/'.$workflow. '/userid/'.$userid .'" method="post">'; ?>
        <table width="823">
            <tr>
                <td colspan="2" width="587" height="35" style="background-color:#CCC;font-size:16px;font-family:Tahoma, Geneva, sans-serif;">&nbsp;<?php echo $text['workflow'][0] . ' <b>' . $text['workflow'][1] . '</b>';?></td>
            </tr>
            <tr>
                <?php
                    $url = $serverPath . '/layout/linklogin/versionid/'.$workflowverion.'/workflowid/'.$workflow. '/userid/'.$userid.'/window/edit';
                ?>
                <td colspan="2" style="font-size:16px;font-family:Tahoma, Geneva, sans-serif;"><a href="<?php echo $url; ?>" target="_blank"><?php echo $linkto;?></a></td>
            </tr>
            <tr>
                <td height="20"></td>
                <td height="20"></td>
            </tr>
            <tr>
                <td height="20" colspan="2">
                    <input type="radio" name="workfloweditAcceptWorkflow_decission" value="1" checked> <?php echo $text['workflow'][8]?><br />
                    <input type="radio" name="workfloweditAcceptWorkflow_decission" value="0"> <?php echo $text['workflow'][9]?><br />
                    <textarea name="workfloweditAcceptWorkflow_reason" cols=20" rows="8"></textarea>


                </td>
            </tr>
            <tr>
                <td height="20"></td>
                <td height="20"></td>
            </tr>
            <?php
            $buildIframe = new BuildIFrameEmail();
            $fieldcounter = 0;
            $slotcounter = 0;
            foreach($slots as $slot) {
                $fields = $slot['fields'];
                $leftString = '';
                $rightString = '';
                echo '<tr>';
                echo '<td colspan="2" height="20" style="background-color:#F90;font-size:15px;font-family:Tahoma, Geneva, sans-serif;">'.$text['workflow'][2] . ': ' .  $slot['slotname'].'</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td colspan="2" height="10"><input type="hidden" name="slot['.$slotcounter++.'][workflowslot_id]" value="'.$slot['workflowslot_id'].'"/></td>';
                echo '</tr>';
                foreach($fields as $field) {
                    $fieldHtml = $buildIframe->getField($field,$fieldcounter, $text['workflow'][7]);
                    $fieldcounter++;
                    if($field['column'] == 'LEFT') {
                        $leftString .= $fieldHtml . '<br />';
                    }
                    else {
                        $rightString .= $fieldHtml . '<br />';
                    }
                }
                echo '<tr><td>' . $leftString . '</td><td>' . $rightString . '</td></tr>';
                echo '<tr>';
                echo '</tr>';

            }
            ?>
            <tr>
                <td height="20" colspan="2"><input type="submit" value="<?php echo $text['workflow'][10]; ?>" /></td>
            </tr>
        </table> 
        <?php echo '</form>'?>
    </iframe>
    </body>
</html>