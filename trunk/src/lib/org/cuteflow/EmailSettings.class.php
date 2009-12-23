<?php


class EmailSettings {



    public $sender;
    public $receiver;
    public $subject;
    public $body;
    public $contentType;

    public function __construct() {
        sfLoader::loadHelpers('I18N');
        sfLoader::loadHelpers('Partial');
        sfLoader::loadHelpers('Url');
    }

    public function setSender($sender) {
        $this->sender = $sender;
    }

    public function setReceiver($receiver) {
        $this->receiver = $receiver;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setContentType($type) {
        $this->contentType = $type;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function sendEmail() {
        $mailerObject = sfContext::getInstance()->getMailer();

        $message = Swift_Message::newInstance()
            ->setFrom($this->sender)
            ->setTo($this->receiver)
            ->setSubject($this->subject)
            ->setContentType($this->contentType)
            ->setBody($this->body);
            //->attach(Swift_Attachment::fromPath('/path/to/a/file.zip'))
        $mailerObject->send($message);
        
    }

}
?>