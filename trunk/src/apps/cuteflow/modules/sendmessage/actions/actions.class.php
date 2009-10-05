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
    *
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    /**
     * Function sends systemmesage
     * 
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSendMail(sfWebRequest $request) {


        $user = UserLoginTable::instance()->getUserDataByReceiver($request->getPostParameter('receiver'));
        $recevier = $user->toArray();
        
        $email = EmailConfigurationTable::instance()->getEmailConfiguration();
        $mail = new MailDaemon($email);

        $mailObject = $mail->getSwiftObject();
        $mailObject->setCharset(sfConfig::get('sf_charset'));
        $mailObject->setContentType('text/' . $request->getPostParameter('type'));
        $mailObject->setSubject( $request->getPostParameter('subject'));
        $mailObject->setFrom(array($email[0]->getSystemreplyaddress() => $email[0]->getSystemreplyaddress()));
        $mailObject->setBody( $request->getPostParameter('description'));
        $mailObject->setTo($mail->buildReceiver($recevier));
        $mail->send();
        $this->renderText('{success:true}');
        return sfView::NONE;
    }

}
