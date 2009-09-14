<?php
function build_dynamic_javascript_url($js) {

    //echo (sfContext::getInstance()->getUser()->getAttribute('env'));die;
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