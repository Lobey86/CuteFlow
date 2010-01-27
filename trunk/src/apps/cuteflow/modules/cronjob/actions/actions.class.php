<?php

/**
 * cronjob actions.
 *
 * @package    cf
 * @subpackage cronjob
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class cronjobActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    public function executeStartWorkflow(sfWebRequest $request) {
        $context = sfContext::getInstance();
        $context->getConfiguration()->loadHelpers('Partial', 'I18N', 'Url', 'Date', 'CalculateDate', 'ColorBuilder', 'Icon');
        $versionId = 1;
        $templateId = 1;
        $user_id = 1;
        $test = new PrepareStationEmail($versionId, $templateId, $user_id, $context);


        #$process = WorkflowProcessUserTable::instance()->getWaitingProcess();


        #$sub = new CheckSubstitute($process);




        return sfView::NONE;
    }




}