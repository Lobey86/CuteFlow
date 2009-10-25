<?php


class Mailinglist {

    public function  __construct() {

    }

    /**
     *
     * @param int $id, template id
     * @return true
     */
    public function createAuthorizationEntry($id) {
        $setting = array('admin','thesender','sender','senderwithrights','receiver');
        foreach($setting as $item) {
            $mailingauth = new MailinglistAuthorizationSetting();
            $mailingauth->setMailinglisttemplateId($id);
            $mailingauth->setType($item);
            $mailingauth->setDeleteworkflow(0);
            $mailingauth->setArchiveworkflow(0);
            $mailingauth->setStopneworkflow(0);
            $mailingauth->setDetailsworkflow(0);
            $mailingauth->save();
        }
        return true;
    }


    /**
     *
     * @param int $id, id of the mailinglist
     * @param array $data, data to activate
     */
    public function saveAuthorization($id, array $data) {
        foreach ($data as $item => $key) {
            $item_data = array();
            $item_data = explode('__', $item);
            MailinglistAuthorizationSettingTable::instance()->updateAuthorizationConfigurationById($id, $item_data[0],$item_data[1]);
        }
    }


    /**
     *
     * @param int $id, id of the mailinglist
     * @param array $data, user to store
     * @return true
     */
    public function saveUser($id, array $data) {
        $position = 1;
        foreach($data as $item) {
            $mailuser = new MailinglistAllowedSender();
            $mailuser->setMailinglisttemplateId($id);
            $mailuser->setUserId($item['id']);
            $mailuser->setPosition($position++);
            $mailuser->save();
        }
        return true;
    }


    /**
     * Creates all records for mailinglist grid
     * @param Doctrine_Collection $data, data
     * @return array $result
     */
    public function buildAllMailinglists(Doctrine_Collection $data) {
        $result = array();
        $a = 0;

        foreach($data as $item) {
            $form = $item->getFormTemplate()->toArray();
            $result[$a]['#'] = $a+1;
            $result[$a]['id'] = $item->getId();
            $result[$a]['formtemplate_id'] = $item->getFormtemplateId();
            $result[$a]['isactive'] = $item->getIsactive();
            $result[$a]['name'] = $item->getName();
            $result[$a++]['formtemplate_name'] = $form[0]['name'];
        }
        return $result;
    }
    

    /**
     * Add unique_id to allowedsender for an template
     * @param array $data, data
     * @return array $data, data
     */
    public function buildAllowedSender(array $data) {
        $result = array();
        $a = 0;
        for($a=0;$a<count($data);$a++) {
            $data[$a]['unique_id'] = $a+1;
        }
        return $data;
    }


    public function buildSingleMailinglist(Doctrine_Collection $data) {
        $test = $data->toArray();
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $slots = $item->getMailinglistSlot();
            $result['id'] = $item->getId();
            $result['title'] = $item->getName();
            $result['slot'] = $this->buildSlot($slots);
        }
        return $result;

    }


    public function buildSlot(Doctrine_Collection $slots) {
        $result = array();
        $a = 0;
        foreach($slots as $slot) {
            $slotname = $slot->getFormSlot();
            $result[$a]['id'] = $slot->getSlotId();
            $result[$a]['title'] = $slotname->getName();
            $result[$a++]['user'] = $this->buildUser($slot->getId());
        }
        return $result;
    }


    public function buildUser($id) {
        $result = array();
        $a = 0;
        $data = MailinglistUserTable::instance()->getAllUserBySlotId($id);
        foreach($data as $user) {
            $result[$a]['databaseId'] = $user->getId();
            $result[$a]['id'] = $user->getId();
            $result[$a]['user_id'] = $user->getUserId();
            $result[$a++]['name'] = $user->getName();
        }
        return $result;
        
    }



}

?>