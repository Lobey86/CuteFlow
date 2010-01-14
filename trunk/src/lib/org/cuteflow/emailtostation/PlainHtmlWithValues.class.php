<?php
class PlainHtmlWithValues extends EmailSettings {

    public $data;

    public function __construct(PrepareStationEmail $data) {
        sfLoader::loadHelpers('Partial');
        $this->data = $data;
        $this->sendEmailToStation();
    }



    public function sendEmailToStation() {
        $sf_i18n = sfContext::getInstance()->getI18N();
        $sf_i18n->setCulture($this->data->userSettings->userSettings['language']);

        $subject = sfContext::getInstance()->getI18N()->__('CuteFlow: values to' ,null,'sendstationmail') . ' ' . $this->data->workflowDetailsData['workflow'];
        $linkTo = sfContext::getInstance()->getI18N()->__('Direct link to workflow' ,null,'sendstationmail');
        $content['workflow'][0] = sfContext::getInstance()->getI18N()->__('You have received values for the workflow' ,null,'sendstationmail');
        $content['workflow'][1] = $this->data->workflowDetailsData['workflow'];
        $content['workflow'][2] = sfContext::getInstance()->getI18N()->__('Slot' ,null,'sendstationmail');
        $content['workflow'][3] = sfContext::getInstance()->getI18N()->__('Yes' ,null,'sendstationmail');
        $content['workflow'][4] = sfContext::getInstance()->getI18N()->__('No' ,null,'sendstationmail');
        $content['workflow'][5] = sfContext::getInstance()->getI18N()->__('Field' ,null,'sendstationmail');
        $content['workflow'][6] = sfContext::getInstance()->getI18N()->__('Value' ,null,'sendstationmail');



        $this->setSender($this->data->userSettings->userSettings['systemreplyaddress']);
        $this->setReceiver(array ($this->data->userSettings->userData['email'] => $this->data->userSettings->userData['firstname'] . ' ' . $this->data->userSettings->userData['lastname']));
        $this->setSubject($subject);
        $this->setContentType('text/' . $this->data->userSettings->userSettings['emailformat']);
        $bodyData = array('text' => $content,
                          'userid' => $this->data->userSettings->userData['user_id'],
                          'workflowverion' => $this->data->versionId,
                          'workflow' => $this->data->templateId,
                          'slots' => $this->data->slots,
                          'linkto'  => $linkTo
                  );
        $this->setBody(get_partial('sendreminderemail/' . $this->data->userSettings->userSettings['emailformat'] . 'SendValuesToStation', $bodyData));
        $this->setAttachments($this->data->attachments);
        $this->sendEmail();
        print_r ($this->data->slots);die;
    }

}
?>