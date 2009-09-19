<?php
/**
 * Class handles Sendmail() function , using Swiftmailer
 */
class SwiftSendmail extends Mail {


    public $swiftTransport;
    public $messageObject;
    public $swiftObject;


    public function __construct(Doctrine_Collection $sendmailConfig_in) {
        $this->loadPath();
        $this->configConnection($sendmailConfig_in);
        $this->setSwiftObject();
    }

    /**
     * Create SendmailTransport instance
     * @param Doctrine_Collection $sendmailConfig, has absolute path of sendmail
     *
     */
    public function configConnection(Doctrine_Collection $sendmailConfig) {
        $this->swiftTransport = Swift_SendmailTransport::newInstance($sendmailConfig[0]->getSendmailpath());
    }



}

?>