<?php
function use_dynamic_javascript($js) {
    $parts = explode('/', $js);
    $url = url_for('script/load?param='.$parts[0].'&filename='.$parts[1]);
    return '<script type="text/javascript" src="'.$url.'" />';
    //$env = sfConfig::get('sf_environment');
    //return url_for($url);
}
?>