<?php




class PrepareWorkflowData {

    public function  __construct() {

    }

    /**
     * Set the startdate of the workflo, and set if the workflow will start at the moment
     *
     * @param array $date, POST data
     * @return array $result, correct startdate und starttedat flag
     */
    public function createStartDate($date) {
        $result = array();
        if($date == '') {
            $result['startworkflowat'] = time();
            $result['workflowisstarted'] = 1;
        }
        else {
            $dateStamp = array();
            $dateStamp = explode('-', $date);
            $result['startworkflowat'] = mktime(0,0,0,$dateStamp[1],$dateStamp[0],$dateStamp[2]);
            $result['workflowisstarted'] = 0;
        }
        return $result;
    }

    /**
     * Builds endreason out of the post data
     *
     * @param array $data, POST data
     * @return String $result;
     */
    public function createEndreason(array $data){
        $result = 0;
        foreach($data as $item) {
            $result += $item;
        }
        return $result;
    }


    /**
     * Create the correct contenttype and the correct content out of POST data
     *
     * @param array $data, post data
     * @return array $result, result with correct content and contenttype data
     */
    public function createContenttype(array $data) {
        $result = array();
        if(isset($data['createWorkflowFirstTab_contenttype'])) {
            $result['contenttype'] = $data['createWorkflowFirstTab_contenttype'];
            $result['content'] = $data['createWorkflowFirstTab_contenttype'] == 'html' ? $data['createWorkflowFirstTab_htmlarea'] : $data['createWorkflowFirstTab_textarea'];
        }
        elseif($data['createWorkflowFirstTab_additionaltext'] != '') {
            $contenttype = AdditionalTextTable::instance()->findSingleTextById($data['createWorkflowFirstTab_additionaltext'])->toArray();
            $result['contenttype'] = $contenttype[0]['contenttype'];
            $result['content'] = $contenttype[0]['contenttype'] == 'html' ? $data['createWorkflowFirstTab_htmlarea'] : $data['createWorkflowFirstTab_textarea'];
        }
        else {
            if (isset($data['createWorkflowFirstTab_htmlarea'])) {
                $result['contenttype'] = 'html';
                $result['content'] = $data['createWorkflowFirstTab_htmlarea'];
            }
            else {
                $result['contenttype'] = 'plain';
                $result['content'] = $data['createWorkflowFirstTab_textarea'];
            }
        }
        $result = $this->adjustHtmlContent($result);
        return $result;
    }



    public function adjustHtmlContent(array $result) {
        if($result['contenttype'] == 'html') {
            $firstChar = substr($result['content'], 0, 1);
            if($firstChar == '?') {
                $result['content'] = substr($result['content'], 1);
            }
        }
        return $result;
    }



}
?>