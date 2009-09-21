<?php

/**
 * Class is the MailDaemon that handles SMTP, Sendmail or mail sending action
 *
 * @author Manuel Schäfer
 */
class MailDaemon {

    private $swiftObject;
    private $messageObject;
    private $transportObject;
    private $emailSettings;

    /**
     * When creating new MailDeamon instance, sending type is set.
     * @param String $mailTypeIn, Choose between SMTP, SENDMAIL and MAIL
     */
    public function __construct(Doctrine_Collection $mailTypeIn) {
        $this->emailSettings = $mailTypeIn;
        $this->loadPath();
        $this->setMailObject();
        $this->setSwiftObject();
    }


    public  function loadPath() {
        require_once sfConfig::get('sf_lib_dir').'/org/mail/swift/lib/swift_init.php';
        return true;
    }
    

    /**
     * Function checks sendingtype of email and creates the necessary objects....
     *
     */
    private function setMailObject() {
        if($this->emailSettings[0]->getActivetype() == 'SMTP') { // smtp
            $this->transportObject = Swift_SmtpTransport::newInstance($this->emailSettings[0]->getSmtphost(), $this->emailSettings[0]->getSmtpport(), $this->emailSettings[0]->getSmtpencryption());
            if($this->emailSettings[0]->getSmtpuseauth() == 1) {
                $this->transportObject->setPassword($mailIn[0]->getSmtppassword());
                $this->transportObject->setUsername($mailIn[0]->getSmtpusername());
            }
        }
        elseif($mailIn[0]->getActivetype() == 'SENDMAIL') { // sendmail
  
            $this->transportObject =  Swift_SendmailTransport::newInstance($this->emailSettings[0]->getSendmailpath());
        }
        else { // mail();
             $this->transportObject = Swift_MailTransport::newInstance();
        }
    }

    /**
     * Function returns an emailObject
     *
     * @return object, returns SMTP, SENDMAIL or MAIL Object
     */
    public function getSwiftObject() {
        return $this->messageObject;
    }


    /**
     * Set necessarry email objects
     */
    public function setSwiftObject() {
        $this->swiftObject = Swift_Mailer::newInstance($this->transportObject);
        $this->messageObject = Swift_Message::newInstance();
    }


    /** 
     * Send function for emails
     */
    public function send() {
        $this->swiftObject->send($this->messageObject);
    }
   
    /**
     * Function builds receiver out of an array
     *
     * @param array $receiver, receiver of email
     * @return array $result, ordered array
     */
    public function buildReceiver(array $receiver)  {
        $result = array();
        foreach($receiver as $item) {
            $result[$item['email']] = $item['name'];
        }
        return $result;
    }


}
?>