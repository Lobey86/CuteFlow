<?php
class BuildIFrameEmail {


    public function __construt() {

    }


    public function getField(array $field, $fieldcounter, $fileTranslation) {
        $hiddenField = $this->getHiddenField($field['type'], $fieldcounter);
        $hiddenIdField = '';
        $disabled = $field['writeprotected'] == 1 ? 'disabled' : '';
        $theField = '';
        switch ($field['type']) {
           case 'TEXTFIELD':
               $hiddenIdField = $this->getHiddenIdField($field['items']['id'], $fieldcounter);
               $theField = '<table><tr><td width="180">' . $field['fieldname'] . ': </td><td><input '.$disabled.' type="text" name="field['.$fieldcounter.'][value]" value="'.$field['items']['value'].'" /></td></tr></table>';
               break;
           case 'CHECKBOX':
               $hiddenIdField = $this->getHiddenIdField($field['items']['id'], $fieldcounter);
               $checked = $field['items']['value'] == 1 ? 'checked=checked' : '';
               $theField = '<table><tr><td width="180">' . $field['fieldname'] . ': </td><td><input '.$disabled.' type="checkbox" name="field['.$fieldcounter.'][value]" value="1" '.$checked.'/></td></tr></table>';
               break;
           case 'NUMBER':
               $hiddenIdField = $this->getHiddenIdField($field['items']['id'], $fieldcounter);
               $theField = '<table><tr><td width="180">' . $field['fieldname'] . ': </td><td><input '.$disabled.' type="text" name="field['.$fieldcounter.'][value]" value="'.$field['items']['value'].'" /></td></tr></table>';
               break;
           case 'DATE':
               $hiddenIdField = $this->getHiddenIdField($field['items']['id'], $fieldcounter);
               $theField = '<table><tr><td width="180">' . $field['fieldname'] . ': </td><td><input '.$disabled.' type="text" name="field['.$fieldcounter.'][value]" value="'.$field['items']['value'].'" /></td></tr></table>';
               break;
           case 'TEXTAREA':
               $hiddenIdField = $this->getHiddenIdField($field['items']['id'], $fieldcounter);
               $theField = '<table><tr><td width="180">' . $field['fieldname'] . ': </td><td><textarea '.$disabled.' name="field['.$fieldcounter.'][value]" cols="25" rows="6">'.strip_tags($field['items']['value']).'</textarea></td></tr></table>';
               break;
           case 'RADIOGROUP':
               $theBox = '';
               $itemCounter = 0;
               $hiddenIdField = $this->getHiddenIdField($field['items'][0]['id'], $fieldcounter);
               foreach($field['items'] as $singleItem) {
                   $checked = $singleItem['value'] == 1 ? 'checked=checked' : '';
                   $theBox .= '<input '.$disabled.' type="radio" name="field['.$fieldcounter.'][id]" value="'.$singleItem['id'] .'" '.$checked.'>' . $singleItem['name'] . '<br />';
                   $itemCounter++;
               }
               $theField = '<table><tr><td width="180">' . $field['fieldname'] . ': </td><td>'.$theBox.'</td></tr></table>';
               break;
           case 'CHECKBOXGROUP':
               $theBox = '';
               $itemCounter = 0;
               $hiddenIdField = $this->getHiddenIdField($field['items'][0]['id'], $fieldcounter);
               foreach($field['items'] as $singleItem) {
                   $checked = $singleItem['value'] == 1 ? 'checked=checked' : '';
                   $theBox .= '<input '.$disabled.' type="checkbox" name="field['.$fieldcounter.'][items]['.$itemCounter.'][id]" value="'.$singleItem['id'].'" '.$checked.'>' . $singleItem['name'] . '<br />';
                   $itemCounter++;
               }
               $theField = '<table><tr><td width="180">' . $field['fieldname'] . ': </td><td>'.$theBox.'</td></tr></table>';
               break;
           case 'COMBOBOX':
               $theBox = '';
               $itemCounter = 0;
               $theBox = '<select name="field['.$fieldcounter.'][id]"  '.$disabled.'>';
               $theBox .= '<option></option>';
               $hiddenIdField = $this->getHiddenIdField($field['items'][0]['id'], $fieldcounter);
               foreach($field['items'] as $singleItem) {
                   $checked = $singleItem['value'] == 1 ? 'selected=selected' : '';
                   $theBox .= '<option '.$disabled.' value="'.$singleItem['id'].'" '.$checked.'>' . $singleItem['name'];
                   $itemCounter++;
               }
               $theBox .= '</select>';
               $theField = '<table><tr><td width="180">' . $field['fieldname'] . ': </td><td>'.$theBox.'</td></tr></table>';
               break;
           case 'FILE':
               $theField = '<table><tr><td width="180">' . $fileTranslation . ': </td><td><a href="'.$field['items']['plainurl'].'">Blubb</a></td></tr></table>';
           break;
        }
        return $theField . $hiddenField . $hiddenIdField;

    }



    public function getHiddenField($type, $fieldcounter) {
        $fieldString = '<input type="hidden" name="field['.$fieldcounter.'][type]" value="'.$type.'" />';
        return $fieldString;
    }


    public function getHiddenIdField($id, $fieldcounter) {
        $fieldString = '<input type="hidden" name="field['.$fieldcounter.'][field_id]" value="'.$id.'" />';
        return $fieldString;
    }





    




}
?>
