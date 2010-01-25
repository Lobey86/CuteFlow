<?php
class PrepareStationEmail {

    public $versionId;
    public $workflowDetailsObj;
    public $workflowDetailsData;
    public $workflowSettings;
    public $attachments;
    public $workflowEditObj;
    public $userSettings;
    public $slots;
    public $templateId;

    public function __construct($versionId, $templateId, $userId) {
        $this->userId = $userId;
        $this->userSettings = new UserMailSettings($userId);
        $this->versionId = $versionId;
        $this->templateId = $templateId;
        $this->workflowSettings = WorkflowVersionTable::instance()->getWorkflowVersionById($versionId);
        $this->workflowDetailsObj = new WorkflowDetail();
        $this->workflowDetailsObj->setCulture($this->userSettings->userSettings['language']);
        $this->setWorkflowDetailData();
        $this->setAttachments();
        $this->workflowEditObj = new WorkflowEdit();
        $this->workflowEditObj->setContext(sfContext::getInstance());
        $this->workflowEditObj->setCulture($this->userSettings->userSettings['language']);
        $this->workflowEditObj->setUserId($this->userId);
        $this->slots = $this->workflowEditObj->buildSlots($this->workflowSettings , $this->versionId);
        $this->makeDecission();
    }


    public function setWorkflowDetailData() {
        $this->workflowDetailsData = $this->workflowDetailsObj->buildHeadLine($this->workflowSettings);
    }


    public function setAttachments() {
        $this->attachments = $this->workflowDetailsObj->buildAttachments($this->workflowSettings, $this->versionId);
    }


    public function makeDecission() {
        $userSettings = $this->userSettings->userSettings;
        if($userSettings['emailformat'] == 'plain') {
            $sendMail = new PlainHtmlWithValues($this);
        }
        else {
            if($userSettings['emailtype'] == 'VALUES') {
                $sendMail = new PlainHtmlWithValues($this);
            }
            else {
                $sendMail = new HtmlWithIFrame($this);
            }
        }
    }

    
    
}
?>
