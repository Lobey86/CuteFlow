<?php

/**
 * file actions.
 *
 * @package    cf
 * @subpackage file
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class fileActions extends sfActions {
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }



    public function executeShowAttachment(sfWebRequest $request) {
        $versionid = $request->getParameter('versionid');
        $workflowid = $request->getParameter('workflowid');
        $attachmentid = $request->getParameter('attachmentid');
        $file = $request->getParameter('file');

        if ($file == 1) {
            $attachment = WorkflowSlotFieldFileTable::instance()->geFileById($attachmentid)->toArray();
        }
        else {
            $attachment = WorkflowVersionAttachmentTable::instance()->getAttachmentsById($attachmentid)->toArray();
        }
        $filepath = sfConfig::get('sf_upload_dir') . '/' . $workflowid . '/' . $versionid . '/' . $attachment[0]['hashname'];
        $file = new File();
        $filecontent = $file->getFileContent($filepath);
        $contenttyoe = $file->getContenttype($attachment[0]['hashname']);

        $response = $this->getResponse();
        $response->clearHttpHeaders();

        $response->setHttpHeader('Content-Type', 'application/octet-stream');
        $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=' . $attachment[0]['filename']);
        $response->setHttpHeader('Content-Length', filesize($filepath));
        $response->sendHttpHeaders(); // send the headers before the file
        $response->setContent($filecontent);
        return sfView::NONE;
    }










}
