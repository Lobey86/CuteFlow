<?php

class SwiftSmtp extends Mail{

    public $swiftTransport;
    public $messageObject;
    public $swiftObject;

    public function __construct(Doctrine_Collection $smtpConfig_in) {
        $this->loadPath();
        $this->configConnection($smtpConfig_in);
        $this->setSwiftObject();
    }


    private function configConnection(Doctrine_Collection $smtpConfig) {
        $this->swiftTransport = Swift_SmtpTransport::newInstance($smtpConfig[0]->getSmtphost(), $smtpConfig[0]->getSmtpport(), $smtpConfig[0]->getSmtpencryption());
        $this->swiftTransport->setPassword($smtpConfig[0]->getSmtppassword());
        $this->swiftTransport->setUsername($smtpConfig[0]->getSmtpusername());
    }




}

?>