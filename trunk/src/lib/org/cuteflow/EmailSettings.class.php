<?php


class EmailSettings {



    public $sender;
    public $receiver;
    public $subject;
    public $body;
    public $contentType;
    public $attachments;

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


    public function setAttachments($items) {
        $a = 0;
        foreach($items as $attachment) {
            $this->attachments[$a]['filepath'] = $attachment['filepath'];
            $this->attachments[$a++]['filename'] = $attachment['filename'];
        }
    }


    public function sendEmail() {
        $mailerObject = sfContext::getInstance()->getMailer();
        $message = Swift_Message::newInstance()
            ->setFrom($this->sender)
            ->setTo($this->receiver)
            ->setSubject($this->subject)
            ->setContentType($this->contentType)
            ->setBody($this->body);
        if(isset($this->attachments)) {
            foreach($this->attachments as $file) {
                $fileObj = new File();
                $filecontent = $fileObj->getFileContent($file['filepath']);
                $message->attach(Swift_Attachment::newInstance($filecontent, $file['filename']));
            }
        }
        try {
            $sendingright = EmailConfigurationTable::instance()->getEmailConfiguration()->toArray();
            if($sendingright[0]['allowemailtransport'] == 1) {
                $mailerObject->send($message);
            }
        }
        catch (Exception $e) {
            
        }
        
    }

}
?>