<?php



class RestartWorkflow {



    public function  __construct() {
        
    }



    public function buildSelectStation(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $slotname = $item->getDocumenttemplateSlot()->toArray();
            $result[$a]['workflowslot_id'] = $item->getId();
            $result[$a]['workflowtemplate_id'] = $item->getWorkflowversionId();
            $result[$a]['slotname'] = $slotname[0]['name'];
            $result[$a]['sendtoallreceivers'] = $slotname[0]['sendtoallreceivers'];
            $result[$a++]['user'] = $this->getUser($slotname[0]['name'], $item->getId(), $a+1);


        }
        $result = $this->mergeArray($result);
        return $result;
    }



    public function getUser($slotname, $workflowslot_id, $slotcounter) {
        $users = WorkflowSlotUserTable::instance()->getUserBySlotId($workflowslot_id);
        $result = array();
        $a = 0;
        foreach($users as $user) {
            $userLogin = UserLoginTable::instance()->findActiveUserById($user->getUserId());
            $userData = $userLogin[0]->getUserData()->toArray();
            $result[$a]['workflowslotuser_id'] = $user->getId();
            $result[$a]['user_id'] = $user->getUserId();
            $result[$a]['slotgroup'] = '#' . $slotcounter . ' : ' . $slotname;
            $result[$a]['plainusername'] = $userData['firstname'] . ' ' . $userData['lastname'];
            $result[$a]['username'] = $userData['firstname'] . ' ' . $userData['lastname'] . ' <i>'.$userLogin[0]->getUsername().'</i>';
            $a++;
        }
        return $result;
    }



    public function mergeArray(array $data) {
        $result = array();
        $c = 0;
        for($a=0;$a<count($data);$a++) {

            for($b=0;$b<count($data[$a]['user']);$b++) {
                $user = $data[$a]['user'][$b];
                $result[$c] = $user;
                $result[$c]['workflowslot_id'] = $data[$a]['workflowslot_id'];
                $result[$c]['workflowtemplate_id'] = $data[$a]['workflowtemplate_id'];
                $result[$c]['sendtoallreceivers'] = $data[$a]['sendtoallreceivers'];
                $result[$c++]['slotname'] = $data[$a]['slotname'];
            }
            
        }
        return $result;
    }















}










?>