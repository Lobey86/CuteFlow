<?php

/**
 * Class is the MailDaemon that handles SMTP, Sendmail or mail sending action
 *
 * @author Manuel Schäfer
 */
class MailDaemon {

    private $mailType;
    private $mailObject;

    /**
     * When creating new MailDeamon instance, sending type is set.
     * @param String $mailTypeIn, Choose between SMTP, SENDMAIL and MAIL
     */
    public function __construct($mailTypeIn) {
        $this->mailType = $mailTypeIn;
        $this->setMailObject();
    }


    /**
     * Function checks sendingtype of email and creates the necessary
     * 
     */
    private function setMailObject() {
        if($this->mailType == 'SMTP') { // smtp
            $result = Doctrine_Query::create()
                ->select('ec.*')
                ->from('EmailConfiguration ec')
                ->execute();
            $this->mailObject =  new SwiftSmtp($result);
        }
        elseif($this->mailType == 'SENDMAIL') { // sendmail
            $result = Doctrine_Query::create()
                ->select('ec.*')
                ->from('EmailConfiguration ec')
                ->execute();
            $this->mailObject =  new SwiftSendmail($result);
        }
        else { // mail();
             $this->mailObject = new SwiftMail();
        }
    }

    /**
     * Function returns an Object
     *
     * @return object, returns SMTP, SENDMAIL or MAIL Object
     */
    public function getInstance() {
        return $this->mailObject;
    }



}
?>