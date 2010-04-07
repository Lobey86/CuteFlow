<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class WorkflowTemplateTable extends Doctrine_Table {
    /**
     *
     * create new instance of AdditionalText
     * @return object UserLoginTable
     */
    public static function instance() {
        return Doctrine::getTable('WorkflowTemplate');
    }


    public function getWorkflowSender() {
        return Doctrine_Query::create()
            ->select('DISTINCT wft.sender_id as id')
            ->from('WorkflowTemplate wft')
            ->where('wft.deleted_at IS NULL')
            ->andWhere('wft.isarchived = ?', 0)
            ->andWhere('wft.isstopped = ?', 0)
            ->execute();
    }


    public function archiveWorkflow($id) {
        Doctrine_Query::create()
            ->update('WorkflowTemplate wft')
            ->set('wft.isarchived','?', 1)
            ->set('wft.isstopped','?',1)
            ->set('wft.stopped_at','?', time())
            ->set('wft.archived_at','?', time())
            ->where('wft.id = ?', $id)
            ->execute();
        return true;
    }


    public function removeFromArchive($id) {
        Doctrine_Query::create()
            ->update('WorkflowTemplate wft')
            ->set('wft.isarchived','?', 0)
            ->set('wft.archived_at', 'null')
            ->set('wft.archived_by', 'null')
            ->where('wft.id = ?', $id)
            ->execute();
        return true;
    }


    public function getAllWorkflowTemplates($limit, $offset) {
        $query =  Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('wft.*, wfv.id as activeversion_id,wfv.workflowisstarted as workflowisstarted,wfv.startworkflow_at as startworkflow_at, wfv.created_at as versioncreated_at, wft.iscompleted')
            ->leftJoin('wft.WorkflowVersion wfv');
        if($offset != -1 AND $limit != -1) {
            $query->limit($limit)
                  ->offset($offset);
        }
        return $query->where('wft.deleted_at IS NULL')
                     ->andWhere('wft.isarchived = ?', 0)
                     ->andWhere('wfv.activeversion = ?', 1)
                     ->orderBy('wft.id DESC')
                     ->execute();
    }


    public function getWorkflowTemplateById($id) {
        return Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('wft.*,')
            ->where('wft.id = ?' ,$id)
            ->execute();
    }

    public function updateEndaction($id, $reason) {
        
        Doctrine_Query::create()
            ->update('WorkflowTemplate wft')
            ->set('wft.endaction','?', $reason)
            ->where('wft.id = ?', $id)
            ->execute();
        return true;
    }

    public function getWorkflowTemplateByVersionId($id) {
        return Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('wft.*,')
            ->leftJoin('wft.WorkflowVersion wv')
            ->where('wv.id = ?' ,$id)
            ->execute();
    }


    public function deleteWorkflow($id) {
        date_default_timezone_set('Europe/Paris');
        $timestamp = time();
        $date = date("Y-m-d",$timestamp);
        $time = date("H:i:s",$timestamp);
        $stamp = $date . ' ' . $time;

        Doctrine_Query::create()
            ->update('WorkflowTemplate wft')
            ->set('wft.deleted_at','?', $stamp)
            ->where('wft.id = ?', $id)
            ->execute();
        return true;
    }


    public function stopWorkflow($id, $user_id) {
        Doctrine_Query::create()
            ->update('WorkflowTemplate wft')
            ->set('wft.isstopped','?',1)
            ->set('wft.stopped_at','?', time())
            ->set('wft.stopped_by','?', $user_id)
            ->where('wft.id = ?', $id)
            ->execute();
        return true;
        
    }


    public function deleteAndStopWorkflow($id, $user_id) {
        date_default_timezone_set('Europe/Paris');
        $timestamp = time();
        $date = date("Y-m-d",$timestamp);
        $time = date("H:i:s",$timestamp);
        $stamp = $date . ' ' . $time;
        Doctrine_Query::create()
            ->update('WorkflowTemplate wft')
            ->set('wft.isstopped','?',1)
            ->set('wft.stopped_at','?', time())
            ->set('wft.stopped_by','?', $user_id)
            ->set('wft.deleted_at','?', $stamp)
            ->where('wft.id = ?', $id)
            ->execute();
        return true;
    }


    public function archiveAndStopWorkflow($id, $user_id) {
         Doctrine_Query::create()
            ->update('WorkflowTemplate wft')
            ->set('wft.isstopped','?',1)
            ->set('wft.stopped_at','?', time())
            ->set('wft.stopped_by','?', $user_id)
            ->set('wft.isarchived','?', 1)
            ->set('wft.archived_at','?', time())
            ->set('wft.archived_by','?', $user_id)
            ->where('wft.id = ?', $id)
            ->execute();
        return true;
    }

    public function setWorkflowFinished($id) {
        Doctrine_Query::create()
            ->update('WorkflowTemplate wft')
            ->set('wft.iscompleted','?',1)
            ->set('wft.completed_at','?', time())
            ->where('wft.id = ?', $id)
            ->execute();
        return true;
        
    }



    public function getSumAllToDoWorkflowTemplates($user_id) {
        return Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('COUNT(*) AS anzahl')
            ->leftJoin('wft.WorkflowVersion wfv')
            ->leftJoin('wfv.WorkflowSlot wfs')
            ->leftJoin('wfs.WorkflowProcess wfp')
            ->leftJoin('wfp.WorkflowProcessUser wfpu')
            ->where('wft.deleted_at IS NULL')
            ->andWhere('wft.isarchived = ?', 0)
            ->andWhere('wft.isstopped = ?', 0)
            ->andWhere('wfv.activeversion = ?', 1)
            ->andWhere('wfv.workflowisstarted = ?', 1)
            ->andWhere('wfpu.user_id = ?', $user_id)
            ->andWhere('wfpu.decissionstate = ?', 'WAITING')
            ->orderBy('wft.id DESC')
            ->execute();
    }

    public function getAllToDoWorkflowTemplates($limit, $offset, $user_id) {
        $query = Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('wft.*, wfv.id as activeversion_id,wfv.workflowisstarted as workflowisstarted,wfv.startworkflow_at as startworkflow_at, wfv.created_at as versioncreated_at, wft.iscompleted')
            ->leftJoin('wft.WorkflowVersion wfv')
            ->leftJoin('wfv.WorkflowSlot wfs')
            ->leftJoin('wfs.WorkflowProcess wfp')
            ->leftJoin('wfp.WorkflowProcessUser wfpu');
        if($offset != -1 AND $limit != -1) {
            $query->limit($limit)
                  ->offset($offset);
        }
        return $query->where('wft.deleted_at IS NULL')
            ->andWhere('wft.isarchived = ?', 0)
            ->andWhere('wft.isstopped = ?', 0)
            ->andWhere('wfv.activeversion = ?', 1)
            ->andWhere('wfv.workflowisstarted = ?', 1)
            ->andWhere('wfpu.user_id = ?', $user_id)
            ->andWhere('wfpu.decissionstate = ?', 'WAITING')
            ->orderBy('wft.id DESC')
            ->execute();
    }



    public function getArchivedWorkflowTemplates($limit, $offset) {
        $query =  Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('wft.*, wfv.id as activeversion_id,wfv.workflowisstarted as workflowisstarted,wfv.startworkflow_at as startworkflow_at, wfv.created_at as versioncreated_at, wft.iscompleted')
            ->leftJoin('wft.WorkflowVersion wfv')
            ->leftJoin('wfv.WorkflowSlot wfs')
            ->leftJoin('wfs.WorkflowProcess wfp')
            ->leftJoin('wfp.WorkflowProcessUser wfpu');
        if($offset != -1 AND $limit != -1) {
            $query->limit($limit)
                  ->offset($offset);
        }
        $query->where('wft.deleted_at IS NULL')
            ->andWhere('wft.isarchived = ?', 1)
            ->andWhere('wft.isstopped = ?', 1)
            ->andWhere('wfv.activeversion = ?', 1)
            ->andWhere('wfv.workflowisstarted = ?', 1)
            ->orderBy('wft.id DESC');
            return $query->execute();
    }

    public function restartWorkflow($id) {
        Doctrine_Query::create()
            ->update('WorkflowTemplate wft')
            ->set('wft.stopped_at','?','')
            ->set('wft.isstopped','?', 0)
            ->where('wft.id = ?', $id)
            ->execute();
        
    }

    public function getAllRunningWorkflows() {
        return Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('wft.*, wfv.id')
            ->leftJoin('wft.WorkflowVersion wfv')
            ->where('wft.isstopped = ?', 0)
            ->andWhere('wft.iscompleted = ?', 0)
            ->andWhere('wft.isarchived = ?', 0)
            ->andWhere('wft.deleted_at IS NULL')
            ->andWhere('wfv.activeversion = ?', 1)
            ->andWhere('wfv.workflowisstarted = ?', 1)
            ->execute();
    }




    public function getAllToDoWorkflowTemplatesByFilter($limit, $offset, $user_id, array $filter) {
        $query = Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('wft.*, wfv.id as activeversion_id,wfv.workflowisstarted as workflowisstarted,wfv.startworkflow_at as startworkflow_at, wfv.created_at as versioncreated_at, wft.iscompleted')
            ->leftJoin('wft.WorkflowVersion wfv')
            ->leftJoin('wfv.WorkflowSlot wfs')
            ->leftJoin('wfs.WorkflowProcess wfp')
            ->leftJoin('wfp.WorkflowProcessUser wfpu');
        if($offset != -1 AND $limit != -1) {
            $query->limit($limit)
                  ->offset($offset);
        }
        $query->where('wft.deleted_at IS NULL')
            ->andWhere('wft.isarchived = ?', 0)
            ->andWhere('wft.isstopped = ?', 0)
            ->andWhere('wfv.activeversion = ?', 1)
            ->andWhere('wfv.workflowisstarted = ?', 1)
            ->andWhere('wfpu.user_id = ?', $user_id)
            ->andWhere('wfpu.decissionstate = ?', 'WAITING');

        if($filter['name'] != -1 AND $filter['name'] != '') {
            $query->andWhere('wft.name LIKE ?','%' . $filter['name'] . '%');
        }

        if($filter['sender'] != -1 AND $filter['sender'] != '') {
            $query->andWhere('wft.sender_id = ?', $filter['sender']);
        }

        if($filter['mailinglist'] != -1 AND $filter['mailinglist'] != '') {
            $query->andWhere('wft.mailinglisttemplateversion_id = ?', $filter['mailinglist']);
        }

        if($filter['documenttemplate'] != -1 AND $filter['documenttemplate'] != '') {
            $query->andWhere('wft.documenttemplateversion_id = ?', $filter['documenttemplate']);
        }

        if($filter['activestation'] != -1 AND $filter['activestation'] != '') {
            $query->andWhere('wfpu.user_id = ?', $filter['activestation']);
        }

        if($filter['daysfrom'] != -1 AND $filter['daysfrom'] != '' AND $filter['daysto'] != -1 AND $filter['daysto'] != '') {            
            $query->andWhere('DATEDIFF(CURDATE(), wfv.created_at) >= ?', $filter['daysfrom']);
            $query->andWhere('DATEDIFF(CURDATE(), wfv.created_at) <= ?', $filter['daysto']);
        }

        if($filter['sendetfrom'] != -1 AND $filter['sendetfrom'] != '' AND $filter['sendetto'] != -1 AND $filter['sendetto'] != '') {
            $query->andWhere('wfv.created_at BETWEEN ? AND ?', array($filter['sendetfrom'], $filter['sendetto']));
        }

        if($filter['fields'] != -1) {
            $query->leftJoin('wfs.WorkflowSlotField wfsf');
            foreach($filter['fields'] as $field) {
                switch ($field['type']) {
                    case 'TEXTFIELD':
                        $query->leftJoin('wfsf.WorkflowSlotFieldTextfield theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'CHECKBOX':
                          $query->leftJoin('wfsf.WorkflowSlotFieldCheckbox theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'NUMBER':
                          $query->leftJoin('wfsf.WorkflowSlotFieldNumber theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'DATE':
                          $query->leftJoin('wfsf.WorkflowSlotFieldDate theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'TEXTAREA':
                          $query->leftJoin('wfsf.WorkflowSlotFieldTextarea theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'RADIOGROUP':
                          $query->leftJoin('wfsf.WorkflowSlotFieldRadiogroup theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'CHECKBOXGROUP':
                          $query->leftJoin('wfsf.WorkflowSlotFieldCheckboxgroup theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'COMBOBOX':
                          $query->leftJoin('wfsf.WorkflowSlotFieldCombobox theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'FILE':
                          $query->leftJoin('wfsf.WorkflowSlotFieldFile theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                }
            }
        }
        return $query->orderBy('wft.id DESC')
                     ->execute();
    }

    public function getAllArchivedWorkflowTemplatesByFilter($limit, $offset, array $filter) {
        $query =  Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('wft.*, wfv.id as activeversion_id,wfv.workflowisstarted as workflowisstarted,wfv.startworkflow_at as startworkflow_at, wfv.created_at as versioncreated_at, wft.iscompleted')
            ->leftJoin('wft.WorkflowVersion wfv')
            ->leftJoin('wfv.WorkflowSlot wfs')
            ->leftJoin('wfs.WorkflowProcess wfp')
            ->leftJoin('wfp.WorkflowProcessUser wfpu');
        if($offset != -1 AND $limit != -1) {
            $query->limit($limit)
                  ->offset($offset);
        }
        $query->where('wft.deleted_at IS NULL')
            ->andWhere('wft.isarchived = ?', 1)
            ->andWhere('wft.isstopped = ?', 1)
            ->andWhere('wfv.activeversion = ?', 1)
            ->andWhere('wfv.workflowisstarted = ?', 1);
       if($filter['name'] != -1 AND $filter['name'] != '') {
            $query->andWhere('wft.name LIKE ?','%' . $filter['name'] . '%');
        }

        if($filter['sender'] != -1 AND $filter['sender'] != '') {
            $query->andWhere('wft.sender_id = ?', $filter['sender']);
        }

        if($filter['mailinglist'] != -1 AND $filter['mailinglist'] != '') {
            $query->andWhere('wft.mailinglisttemplateversion_id = ?', $filter['mailinglist']);
        }

        if($filter['documenttemplate'] != -1 AND $filter['documenttemplate'] != '') {
            $query->andWhere('wft.documenttemplateversion_id = ?', $filter['documenttemplate']);
        }

        if($filter['activestation'] != -1 AND $filter['activestation'] != '') {
            $query->andWhere('wfpu.user_id = ?', $filter['activestation']);
        }

        if($filter['daysfrom'] != -1 AND $filter['daysfrom'] != '' AND $filter['daysto'] != -1 AND $filter['daysto'] != '') {
            $query->andWhere('DATEDIFF(CURDATE(), wfv.created_at) >= ?', $filter['daysfrom']);
            $query->andWhere('DATEDIFF(CURDATE(), wfv.created_at) <= ?', $filter['daysto']);
        }

        if($filter['sendetfrom'] != -1 AND $filter['sendetfrom'] != '' AND $filter['sendetto'] != -1 AND $filter['sendetto'] != '') {
            $query->andWhere('wfv.created_at BETWEEN ? AND ?', array($filter['sendetfrom'], $filter['sendetto']));
        }

        if($filter['fields'] != -1) {
            $query->leftJoin('wfs.WorkflowSlotField wfsf');
            foreach($filter['fields'] as $field) {
                switch ($field['type']) {
                    case 'TEXTFIELD':
                        $query->leftJoin('wfsf.WorkflowSlotFieldTextfield theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'CHECKBOX':
                          $query->leftJoin('wfsf.WorkflowSlotFieldCheckbox theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'NUMBER':
                          $query->leftJoin('wfsf.WorkflowSlotFieldNumber theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'DATE':
                          $query->leftJoin('wfsf.WorkflowSlotFieldDate theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'TEXTAREA':
                          $query->leftJoin('wfsf.WorkflowSlotFieldTextarea theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'RADIOGROUP':
                          $query->leftJoin('wfsf.WorkflowSlotFieldRadiogroup theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'CHECKBOXGROUP':
                          $query->leftJoin('wfsf.WorkflowSlotFieldCheckboxgroup theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'COMBOBOX':
                          $query->leftJoin('wfsf.WorkflowSlotFieldCombobox theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'FILE':
                          $query->leftJoin('wfsf.WorkflowSlotFieldFile theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                }
            }
        }
        return $query->orderBy('wft.id DESC')
                     ->execute();
    }


    public function getAllWorkflowTemplatesByFilter($limit, $offset, array $filter){
        $query =  Doctrine_Query::create()
            ->from('WorkflowTemplate wft')
            ->select('wft.*, wfv.id as activeversion_id,wfv.workflowisstarted as workflowisstarted,wfv.startworkflow_at as startworkflow_at, wfv.created_at as versioncreated_at, wft.iscompleted')
            ->leftJoin('wft.WorkflowVersion wfv')
            ->leftJoin('wfv.WorkflowProcess wfp')
            ->leftJoin('wfp.WorkflowProcessUser wfpu');
        if($offset != -1 AND $limit != -1) {
            $query->limit($limit)
                  ->offset($offset);
        }
        $query->where('wft.deleted_at IS NULL')
              ->andWhere('wft.isarchived = ?', 0)
               ->andWhere('wfv.activeversion = ?', 1);

        if($filter['name'] != -1 AND $filter['name'] != '') {
            $query->andWhere('wft.name LIKE ?','%' . $filter['name'] . '%');
        }

        if($filter['sender'] != -1 AND $filter['sender'] != '') {
            $query->andWhere('wft.sender_id = ?', $filter['sender']);
        }

        if($filter['mailinglist'] != -1 AND $filter['mailinglist'] != '') {
            $query->andWhere('wft.mailinglisttemplateversion_id = ?', $filter['mailinglist']);
        }

        if($filter['documenttemplate'] != -1 AND $filter['documenttemplate'] != '') {
            $query->andWhere('wft.documenttemplateversion_id = ?', $filter['documenttemplate']);
        }

        if($filter['activestation'] != -1 AND $filter['activestation'] != '') {
            $query->andWhere('wfpu.user_id = ?', $filter['activestation']);
        }

        if($filter['daysfrom'] != -1 AND $filter['daysfrom'] != '' AND $filter['daysto'] != -1 AND $filter['daysto'] != '') {
            $query->andWhere('DATEDIFF(CURDATE(), wfv.created_at) >= ?', $filter['daysfrom']);
            $query->andWhere('DATEDIFF(CURDATE(), wfv.created_at) <= ?', $filter['daysto']);
        }

        if($filter['sendetfrom'] != -1 AND $filter['sendetfrom'] != '' AND $filter['sendetto'] != -1 AND $filter['sendetto'] != '') {
            $query->andWhere('wfv.created_at BETWEEN ? AND ?', array($filter['sendetfrom'], $filter['sendetto']));
        }

        if($filter['fields'] != -1) {
            $query->leftJoin('wfs.WorkflowSlotField wfsf');
            foreach($filter['fields'] as $field) {
                switch ($field['type']) {
                    case 'TEXTFIELD':
                        $query->leftJoin('wfsf.WorkflowSlotFieldTextfield theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'CHECKBOX':
                          $query->leftJoin('wfsf.WorkflowSlotFieldCheckbox theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'NUMBER':
                          $query->leftJoin('wfsf.WorkflowSlotFieldNumber theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'DATE':
                          $query->leftJoin('wfsf.WorkflowSlotFieldDate theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'TEXTAREA':
                          $query->leftJoin('wfsf.WorkflowSlotFieldTextarea theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'RADIOGROUP':
                          $query->leftJoin('wfsf.WorkflowSlotFieldRadiogroup theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'CHECKBOXGROUP':
                          $query->leftJoin('wfsf.WorkflowSlotFieldCheckboxgroup theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'COMBOBOX':
                          $query->leftJoin('wfsf.WorkflowSlotFieldCombobox theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                    case 'FILE':
                          $query->leftJoin('wfsf.WorkflowSlotFieldFile theTable')
                              ->andWhere('wfsf.field_id = ?', $field['field']);
                          if($field['operator'] == '~') {
                              $query->andWhere('theTable.value LIKE ?','%' . $field['value'] . '%');
                          }
                          else {
                              $query->andWhere('theTable.value '.$field['operator'] . '?',$field['value']);
                          }
                        break;
                }
            }
        }
        return $query->orderBy('wft.id DESC')
                     ->execute();
    }


}