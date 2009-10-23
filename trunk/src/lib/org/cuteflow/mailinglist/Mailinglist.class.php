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
    


}

?>