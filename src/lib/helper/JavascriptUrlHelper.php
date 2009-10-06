<?php
/**
 *
 *  URL Helper to get correct URL for EXTJS
 *
 * @param String $js, URL to call. e.g. controller/action
 * @return String, correct url
 */
function build_dynamic_javascript_url($js) {
    if(sfContext::getInstance()->getUser()->getAttribute('env') == '') {
        return sfContext::getInstance()->getUser()->getAttribute('env') . url_for($js);
    }
    else {
        return  '/' . sfContext::getInstance()->getUser()->getAttribute('env') . url_for($js);
    }
    /*$parts = explode('/', $js);
    $url = url_for('script/load?param='.$parts[0].'&filename='.$parts[1]);
    return '<script type="text/javascript" src="'.$url.'" />';*/
}
?>