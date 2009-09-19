<?php

/**
 * systemsetting actions.
 *
 * @package    cf
 * @subpackage systemsetting
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class systemsettingActions extends sfActions {
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
     * Loads Systems settings
     *
     * @param sfRequest $request A request object
     */
    public function executeLoadSystem(sfWebRequest $request) {
        $email = Doctrine_Query::create()
                ->select('ec.*')
                ->from('EmailConfiguration ec')
                ->fetchArray();
        $email[0]['smtpencryption'] =  $email[0]['smtpencryption'] == '' ? 'NONE' :  $email[0]['smtpencryption'];
        $this->renderText('{"email":'.json_encode($email[0]).'}');
        return sfView::NONE;
    }


    /**
     *
     * Saves SystemSettings to additional Tables in database
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSaveSystem(sfWebRequest $request) {
        $data = $request->getPostParameters();


        if (isset($data['productive_checkbox'])) {

        }

        if (isset($data['development_checkbox'])) {

        }
        
        if (isset($data['emailtab_emailtype'])) { // store Email tab
        
        $data['emailtab_encryption'] = $data['emailtab_encryption'] == 'NONE' ? '' : $data['emailtab_encryption'];
        Doctrine_Query::create()
            ->update('EmailConfiguration ec')
            ->set('ec.systemreplyaddress', '?', $data['emailtab_systemreplyaddress'])
            ->set('ec.activetype', '?', $data['emailtab_emailtype'])
            ->set('ec.smtphost', '?', $data['email_smtp_server'])
            ->set('ec.smtpport', '?', $data['email_smtp_port'])
            ->set('ec.smtpusername', '?', $data['email_smtp_username'])
            ->set('ec.smtppassword', '?', $data['email_smtp_password'])
            ->set('ec.smtpencryption', '?', $data['emailtab_encryption'])
            ->set('ec.sendmailpath', '?', $data['email_sendmail'])
            ->where('ec.id = ?',1)
            ->execute();
        }
        $this->renderText('{success:true}');
        return sfView::NONE;
    }

}
