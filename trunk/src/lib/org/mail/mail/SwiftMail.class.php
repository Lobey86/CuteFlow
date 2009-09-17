<?php


class SwiftMail extends Mail{

    public $swiftTransport;
    public $messageObject;
    public $swiftObject;


    public function __construct() {
        $this->loadPath();
        $this->configConnection();
        $this->setSwiftObject();
    }


    public function configConnection() {
        $this->swiftTransport = Swift_MailTransport::newInstance();
    }







}
?>