<?php

class SwiftSendmail extends Mail {


    public $swiftTransport;
    public $messageObject;
    public $swiftObject;


    public function __construct(Doctrine_Collection $sendmailConfig_in) {
        $this->loadPath();
        $this->configConnection($sendmailConfig_in);
        $this->setSwiftObject();
    }


    public function configConnection(Doctrine_Collection $sendmailConfig) {
        $this->swiftTransport = Swift_SendmailTransport::newInstance($sendmailConfig[0]->getSendmailpath());
    }



}

?>