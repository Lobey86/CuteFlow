<?php

/**
 * sendmessage actions.
 *
 * @package    cf
 * @subpackage sendmessage
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class sendmessageActions extends sfActions {
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    public function executeSendMail(sfWebRequest $request) {
        $sendmail = new SendMessage();

        $user = new Doctrine_Query();
        $user->from('User u')
             ->select('u.*');
        
        if($request->getPostParameter('receiver') == 'ALL') {
            // do nothing
        }
        elseif($request->getPostParameter('receiver') == 'SENDER') {
            
        }
        else {
            
        }
        $result = $user->execute();
        $recevier = $sendmail->buildReceiver($result);

        $mail = new MailDaemon('SMTP');
        $mailObject = $mail->getInstance();
        $mailObject->setCharset(sfConfig::get('sf_charset'));
        $mailObject->setContentType('text/' . $request->getPostParameter('type'));
        $mailObject->setSubject( $request->getPostParameter('subject'));
        $mailObject->setFrom(array('displayname'=>'Manuel','email'=>'mschaefer1982@gmx.de'));
        $mailObject->setBody( $request->getPostParameter('description'));
        $mailObject->setTo($recevier);
        $mailObject->send();

        
        $this->renderText('{success:true}');
        return sfView::NONE;
    }

}
