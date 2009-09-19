<?php
/**
 * Parent Class for Email, that handles sending all functionality 
 *
 */
class Mail {

    public $swiftTransport;
    public $messageObject;
    public $swiftObject;
    
    /**
     * Load the path of Swift
     * @return true
     */
    public  function loadPath() {
        require_once sfConfig::get('sf_lib_dir').'/org/mail/swift/lib/swift_init.php';
        return true;
    }

    /**
     * Set Mailer object und Message Object
     */
    public function setSwiftObject() {
        $this->swiftObject = Swift_Mailer::newInstance($this->swiftTransport);
        $this->messageObject = Swift_Message::newInstance();
    }

    /**
     *
     * Function sets the charst of the email
     * @param String $charset, ste Charset of the email
     */
    public function setCharset($charset) {
        $this->messageObject->setCharset($charset);
    }

    /**
     * Set content type of email to html or plain
     * @param String $contentType , set content type of email
     */
    public function setContentType($contentType) {
        $this->messageObject->setContentType($contentType);
    }

    /**
     * Function sets subject of email
     *
     * @param String $subject , set subject to email
     */
    public function setSubject($subject) {
        $this->messageObject->setSubject($subject);
    }

    /**
     * Function sets body to email
     * @param String $body , body of email
     */
    public function setBody($body) {
        $this->messageObject->setBody($body);
    }

    /**
     * Function builds out of an array the recevier of the mail
     * 
     *
     * @param array $receiver, receiver of mail
     *
     */
    public function setTo(array $receiver)  {
        $result = array();
        foreach($receiver as $item) {
            $result[$item['email']] = $item['name'];
        }
        $this->messageObject->setTo($result);
    }

    /**
     * Function sets senser of the email
     * @param array $from, sender of the email
     */
    public function setFrom(array $from) {
       $setFrom[$from['email']] = $from['displayname'];
       $this->messageObject->setFrom($setFrom);
    }

    /**
     * Set attachments to email
     */
    public function setAttachment() {


    }

    /**
     * send emails
     */
    public function send() {
        $this->swiftObject->send($this->messageObject);
    }
    

}
?>