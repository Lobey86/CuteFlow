<?php


class Mailinglist {

    public function  __construct() {
       sfLoader::loadHelpers('Date');
       sfLoader::loadHelpers('i18n');
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
            $mailingauth->setMailinglistversionId($id);
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
     */
    public function addNameToTemplateVersion(array $result, Doctrine_Collection $data) {
        $result['documenttemplate_name'] = $data[0]->getName();
        $result['documenttemplate_id'] = $data[0]->getId();
        return $result;
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
            $mailuser->setMailinglistversionId($id);
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
            $documenttemplate = $item->getDocumenttemplateTemplate()->toArray();
            $activeversion = MailinglistVersionTable::instance()->getActiveVersionById($item->getId())->toArray();
            $result[$a]['#'] = $a+1;
            $result[$a]['id'] = $item->getId();
            $result[$a]['activeversion'] = $activeversion[0]['id'];
            $result[$a]['isactive'] = $item->getIsactive();
            $result[$a]['name'] = $item->getName();
            $result[$a++]['formtemplate_name'] = $documenttemplate[0]['name'];
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


    /**
     * Function builds a single Mailinglist template, with all slots and users
     * calls build slot function to get all slots
     *
     * @param Doctrine_Collection $data
     * @return array $result, content of mailinglist
     */
    public function buildSingleMailinglist(Doctrine_Collection $data, $id) {
        $result = array();
        $a = 0;

        foreach($data as $item) {
            $documenttemplate = $item->getDocumenttemplateTemplate()->toArray();
            $result['documenttemplate_id'] = $documenttemplate[0]['id'];
            $result['documenttemplate_name'] = $documenttemplate[0]['name'];
            $result['id'] = $item->getId();
            $result['name'] = $item->getName();
            $result['slots'] = $this->buildSlot($id);
        }
        return $result;

    }

    /**
     * Function adds slots to a mailinglist
     *
     * @param Doctrine_Collection $slots, slots to a correspondending mailinglist
     * @return array $result
     */
    private function buildSlot($mailinglist_id) {
        $result = array();
        $a = 0;
        $slots = MailinglistSlotTable::instance()->getSlotsByVersionId($mailinglist_id);
        foreach($slots as $slot) {
            $slotname = $slot->getDocumenttemplateSlot()->toArray();
            $result[$a]['slot_id'] = $slotname[0]['id'];
            $result[$a]['name'] = $slotname[0]['name'];
            $result[$a++]['users'] = $this->buildUser($slot->getId());
        }
        return $result;
    }

    /**
     * Function adds all users to a slot
     *
     * @param int $id, Slot id, to get all users
     * @return array $result, users for the slot
     */
    private function buildUser($id) {
        $result = array();
        $a = 0;
        $data = MailinglistUserTable::instance()->getAllUserBySlotId($id);
        foreach($data as $user) {
            $result[$a]['id'] = $user->getId();
            $result[$a]['user_id'] = $user->getUserId();
            $result[$a++]['name'] = $user->getName();
        }
        return $result;
    }


    /**
     * Build all Version for undo operation for an template
     * @param Doctrine_Collection $data, data
     * @param String $culture, culture of the user
     * @param sfContext $context
     * @return array $result
     */
    public function buildAllVersion(Doctrine_Collection $data, $culture, sfContext $context) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $template = $item->getMailinglistTemplate();
            $result[$a]['#'] = $a+1;
            $result[$a]['id'] = $item->getId();
            $result[$a]['activeversion'] = $item->getActiveversion() == 1 ? '<font color="green">' . $context->getI18N()->__('Yes' ,null,'documenttemplate') . '</font>' : '<font color="red">' . $context->getI18N()->__('No',null,'documenttemplate') . '</font>';
            $result[$a]['created_at'] = format_date($item->getCreatedAt(), 'g', $culture);
            $result[$a]['name'] = $template[0]->getName();
            $result[$a++]['mailinglisttemplate_id'] = $item->getMailinglisttemplateId();
        }
        return $result;
    }


    /**
     * Save a new Version of an record
     *
     * @param int $mailinglisttemplate_id, id of the template
     * @param int $version, current version to save
     * @return int $mailinglistversion_id, version id
     */
    public function storeVersion($mailinglisttemplate_id, $version) {
        $mailinglistversion = new MailinglistVersion();
        $mailinglistversion->setMailinglisttemplateId($mailinglisttemplate_id);
        $mailinglistversion->setVersion($version);
        $mailinglistversion->setActiveversion(1);
        $mailinglistversion->save();
        $mailinglistversion_id = $mailinglistversion->getId();
        return $mailinglistversion_id;
    }

    /**
     * Save Slots and Users to database
     *
     * @param array $slots, array with slots and users
     * @param int $mailinglistversion_id, mailinglistversion id
     * @return true;
     */
    public function storeMailinglist(array $slots,$mailinglistversion_id) {
        $slotposition = 1;
        foreach ($slots as $slot) {
            $mailinglistslot = new MailinglistSlot();
            $mailinglistslot->setMailinglistversionId($mailinglistversion_id);
            $mailinglistslot->setSlotId($slot['slot_id']);
            $mailinglistslot->setPosition($slotposition++);
            $mailinglistslot->save();
            $mailinglistslot_id = $mailinglistslot->getId();
            $records = isset($slot['grid']) ? $slot['grid'] : array();
            $userposition = 1;
            foreach($records as $row) {
                $mailinglistuser = new MailinglistUser();
                $mailinglistuser->setMailinglistslotId($mailinglistslot_id);
                $mailinglistuser->setUserId($row['id']);
                $mailinglistuser->setPosition($userposition++);
                $mailinglistuser->save();
            }
        }
        return true;
    }


}

?>