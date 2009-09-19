<?php
/**
 * Class handles Mail() function , using Swiftmailer
 */

class SwiftMail extends Mail{

    public $swiftTransport;
    public $messageObject;
    public $swiftObject;


    public function __construct() {
        $this->loadPath();
        $this->configConnection();
        $this->setSwiftObject();
    }

    /**
     * Create MailTransport instance
     */
    public function configConnection() {
        $this->swiftTransport = Swift_MailTransport::newInstance();
    }







}
?>