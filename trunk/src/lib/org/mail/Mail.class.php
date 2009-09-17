<?php

class Mail {

    public $swiftTransport;
    public $messageObject;
    public $swiftObject;
    

    public  function loadPath() {
        require_once sfConfig::get('sf_lib_dir').'/org/mail/swift/lib/swift_init.php';
        return true;
    }

    
    public function setSwiftObject() {
        $this->swiftObject = Swift_Mailer::newInstance($this->swiftTransport);
        $this->messageObject = Swift_Message::newInstance();
    }

    public function setCharset($charset) {
        $this->messageObject->setCharset($charset);
    }

    public function setContentType($contentType) {
        $this->messageObject->setContentType($contentType);
    }

    public function setSubject($subject) {
        $this->messageObject->setSubject($subject);
    }

    public function setBody($body) {
        $this->messageObject->setBody($body);
    }

    public function setTo(array $receiver)  {
        $result = array();
        foreach($receiver as $item) {
            $result[$item['email']] = $item['name'];
        }
        $this->messageObject->setTo($result);
    }

    public function setFrom(array $from) {
       $setFrom[$from['email']] = $from['displayname'];
       $this->messageObject->setFrom($setFrom);
    }


    public function setAttachment() {


    }

    public function send() {
        $this->swiftObject->send($this->messageObject);
    }
    

}
?>