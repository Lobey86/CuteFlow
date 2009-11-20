<?php



class WorkflowOverview {

    private $context;
    private $userId;
    private $culture;

    public function  __construct(sfContext $context_in) {
        sfLoader::loadHelpers('I18N');
        sfLoader::loadHelpers('Date');
        sfLoader::loadHelpers('CalculateDate');
        sfLoader::loadHelpers('ColorBuilder');
        $this->context = $context_in;
    }


    public function setUserId($id) {
        $this->userId = $id;
    }

    public function setCulture($culture) {
        $this->culture = $culture;

    }

    public function buildData(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        $colorSettings = UserSettingTable::instance()->getUserSettingById($this->userId)->toArray();
        foreach($data as $item) {
            $sender = UserLoginTable::instance()->findActiveUserById($item->getSenderId());
            $mailinglist = $item->getMailinglistTemplate();
            $inProgress = createDayOutOfDateSince($item->getVersioncreatedAt());
            $inProgress = addColor($inProgress, $colorSettings[0]['markred'],$colorSettings[0]['markorange'],$colorSettings[0]['markyellow']);
            $userdata = $sender[0]->getUserData()->toArray();
            $username = $sender[0]->getUsername() . ' (' . $userdata['firstname'] . ' ' . $userdata['lastname'] . ')';
            
            $result[$a]['#'] = $a+1;
            $result[$a]['id'] = $item->getId();
            $result[$a]['mailinglisttemplate_id'] = $item->getMailinglisttemplateId();
            $result[$a]['mailinglisttemplate'] = $mailinglist[0]->getName();
            $result[$a]['sender_id'] = $item->getSenderId();
            $result[$a]['sendername'] = $username;
            $result[$a]['name'] = $item->getName();
            $result[$a]['isstopped'] = $item->getIsstopped();
            $result[$a]['currentlyrunning'] = '<table><tr><td width="20">' . $inProgress . ' </td><td>' . $this->context->getI18N()->__('Days' ,null,'workflowmanagement') . '</td></tr></table>';
            $result[$a]['versioncreated_at'] = format_date($item->getVersioncreatedAt(), 'g', $this->culture);
            $result[$a++]['activeversion_id'] = $item->getActiveversionId();
        }
        return $result;

    }


}
?>