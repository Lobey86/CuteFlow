<?php

class WorkflowDetail {


    private $culture;

    public function  __construct() {
        sfLoader::loadHelpers('Date');
    }

    public function setCulture($culture_in) {
        $this->culture = $culture_in;
    }

    public function buildHeadLine(Doctrine_Collection $data) {
        $result = array();
        $workflowtemplate = WorkflowTemplateTable::instance()->getWorkflowTemplateById($data[0]->getWorkflowtemplateId());
        $mailinglist = $workflowtemplate[0]->getMailinglistTemplate()->toArray();
        $workflowtemplate = $workflowtemplate->toArray();
        $user = UserLoginTable::instance()->findActiveUserById($workflowtemplate[0]['sender_id']);
        $userdata = $user[0]->getUserData();

        $result['workflow'] = $workflowtemplate[0]['name'];
        $result['versionid'] = $data[0]->getId();
        $result['mailinglist'] = $mailinglist[0]['name'];
        $result['mailinglist_id'] = $workflowtemplate[0]['mailinglisttemplate_id'];
        $result['workflowtemplateid'] = $data[0]->getWorkflowtemplateId();
        //$result['content'] = $data[0]->getContent();
        $result['sender_id'] = $workflowtemplate[0]['sender_id'];
        $result['sender'] = $userdata->getFirstname() . ' ' . $userdata->getLastname() . ' <i>('.$user[0]->getUsername().')</i>';
        $result['version'] = $this->getVersion($data[0]->getWorkflowtemplateId());
        return $result;
    }

    public function getVersion($workflowtemplate_id) {
        $allVersions = WorkflowVersionTable::instance()->getAllVersionByWorkflowId($workflowtemplate_id);
        $result = array();
        $a = 0;
        foreach($allVersions as $version) {
            $result[$a]['versionid'] = $version->getId();
            $result[$a]['version'] = $version->getVersion();
            $result[$a]['activeversion'] = $version->getActiveversion();
            $result[$a++]['text'] = '#' . $version->getVersion() . ' - ' . format_date($version->getCreatedAt(), 'g', $this->culture);
        }
        return $result;
    }

}
?>