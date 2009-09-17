<?php


class MailDaemon {

    private $mailType;
    private $mailObject;

    public function __construct($mailTypeIn) {
        $this->mailType = $mailTypeIn;
        $this->setMailObject();
    }



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

    public function getInstance() {
        return $this->mailObject;
    }



}
?>