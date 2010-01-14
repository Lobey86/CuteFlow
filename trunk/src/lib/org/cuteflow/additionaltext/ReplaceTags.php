<?php

class ReplaceTags {

    public $workflow;
    public $theSender;
    public $newText;
    public $culture;
    public $workflowVersion;
    
    public function __construct($versionId, $text, $culture) {
        sfLoader::loadHelpers('Date');
        $this->setWorkflow($versionId);
        $this->setWorkflowVersion($versionId);
        $this->culture = $culture;
        $this->theSender = new UserMailSettings($this->workflow['sender_id']);
        $this->newText = $this->replacePlaceholder($text);
    }

    public function getText() {
        return $this->newText;
    }

    public function setWorkflow($versionId) {
        $data = WorkflowTemplateTable::instance()->getWorkflowTemplateByVersionId($versionId)->toArray();
        $this->workflow = $data[0];
    }

    public function setWorkflowVersion($versionId) {
        $data = WorkflowVersionTable::instance()->getWorkflowVersionById($versionId)->toArray();
        $this->workflowVersion = $data[0];
    }

    public function replacePlaceholder($text) {
        $date = format_date(time(), 'g', $this->culture);
        $text = str_replace('{%CIRCULATION_TITLE%}', $this->workflow['name'], $text);
        $text = str_replace('{%SENDER_USERNAME%}', $this->theSender->userData['username'], $text);
        $text = str_replace('{%SENDER_FULLNAME%}', $this->theSender->userData['firstname'] . ' ' . $this->theSender->userData['lastname'], $text);
        $text = str_replace('{%TIME%}', format_date(time(), 'g', $this->culture), $text);
        $text = str_replace('{%DATE_SENDING%}', format_date($this->workflowVersion['startworkflow_at'], 'g', $this->culture), $text);
        return $text;
    }






}


?>