<?php

/**
 * script actions.
 *
 * @package    cf
 * @subpackage script
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class scriptActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }

  /**
   * Routing, to get JS Files compiled
   *
   *
   * @param sfWebRequest $request
   * @return <type>
   */
    public function executeLoad(sfWebRequest $request) {
        $path = $request->getPathInfo();
        $path = str_replace('/djs', '', $path);
        $path = str_replace($request->getParameter('filename') . '.js', '', $path);

        if($path == '/cache/') {
            $template = sfConfig::get('sf_app_dir') . '/cache/' . $request->getParameter('filename');
        }
        else {
            $template = sfConfig::get('sf_app_template_dir') . $path . $request->getParameter('filename');
        }
        $this->getResponse()->setContentType('text/javascript');
	$this->setLayout(false);
	$this->setTemplate($template); // the real path to JS File -.-
	return '.js' . chr(0);
    }


}
