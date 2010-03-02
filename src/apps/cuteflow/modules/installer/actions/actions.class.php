<?php

/**
 * installer actions.
 *
 * @package    cf
 * @subpackage installer
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class installerActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request) {
        return sfView::SUCCESS;
    }

        /**
     * Action CheckLogin
     */
    public function executeCheckLogin(sfWebRequest $request) {
        $loginObj = new Login();
        if ($loginObj->checkInstaller() == false) {
            $this->redirect('installer/index');
        }
        else {
            $this->redirect('login/index');
        }
        return sfView::NONE;
    }

    public function executeSaveData(sfWebRequest $request) {
        $sysObj = new SystemSetting();
        $installer = new Installer();
        $data = $request->getPostParameters();
        $installer->createConfigFile($data);
        $task = new sfDoctrineBuildAllReLoadTask(sfContext::getInstance()->getEventDispatcher(), new sfFormatter());
        chdir(sfConfig::get('sf_root_dir'));
        $task->run(array(),array('--no-confirmation', '--env=all', '--dir='.sfConfig::get('sf_root_dir').'/data/fixtures/'.$data['productive_data'].''));
        $data = $sysObj->buildEmailSetting($data);
        EmailConfigurationTable::instance()->updateEmailConfiguration($data);
        $taskCC = new sfCacheClearTask(sfContext::getInstance()->getEventDispatcher(), new sfFormatter());
        UserLoginTable::instance()->updateEmail($data['productive_emailadresse']);
        $taskCC->run(array(), array());
        $this->renderText('{success:true}');
        return sfView::NONE;
    }
}
