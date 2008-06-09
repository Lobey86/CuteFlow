<?php

/**
 * workflow actions.
 *
 * @package    cuteflow
 * @subpackage workflow
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class workflowActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->forward('list');
  }
  
  public function executeStart()
  {
  	
  }
  
  public function executeList()
  {
  	// check filters first
  	
  	
  	
  }
  
  public function executeArchive()
  {
  	$workflow_id = $this->getRequestParameter('wid', null);
  	
  	assert(is_integer($workflow_id));
  	
  	$workflow = new Workflow();
  	$workflow->find($workflow_id);
  }
  
  public function executeDelete()
  {
  	
  }
  
  public function exeuteRestart()
  {
  	
  }
  
  public function executeShow()
  {
  	
  }
  
  public function executeDefine()
  {
  	
  }
  
  public function executeEdit()
  {
  	
  }
  
  public function executeStop()
  {
  	
  }
  
  public function executeSave()
  {
  	
  }
}
