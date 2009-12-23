<?php

class SendReminderEmail extends EmailSettings{


    

    public function  __construct(UserMailSettings $userSettings, cronjobActions $controller, array $openWorkflows) {
        $content['text'] = $controller->getContext()->getI18N()->__('You need to complete the following workflows' ,null,'sendreminderemail');
        $this->setSender($userSettings->userSettings['systemreplyaddress']);
        $this->setReceiver(array ($userSettings->userData['email'] => $userSettings->userData['firstname'] . ' ' . $userSettings->userData['lastname']));
        $sf_i18n = $controller->getContext()->getI18N();
        $sf_i18n->setCulture($userSettings->userSettings['language']);
        $subject = $controller->getContext()->getI18N()->__('CuteFlow: open workflows' ,null,'sendreminderemail');
        $worfklowname = $controller->getContext()->getI18N()->__('Workflowname' ,null,'sendreminderemail');
        $linkTo = $controller->getContext()->getI18N()->__('Direct link to workflow' ,null,'sendreminderemail');
        $this->setSubject($subject);
        $this->setContentType('text/' . $userSettings->userSettings['emailformat']);
        $bodyData = array('text' => $content['text'], 
                          'workflow' => $openWorkflows['workflows'],
                          'workflowname' => $worfklowname,
                          'userid' => $userSettings->userData['user_id'],
                          'linkto' => $linkTo
                          );
        $this->setBody($controller->getPartial('sendreminderemail/' . $userSettings->userSettings['emailformat'], $bodyData));
        $this->sendEmail();
    }
}
?>
