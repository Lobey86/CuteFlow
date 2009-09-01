<?php
function build_dynamic_javascript_url($js) {
    $parts = explode('/', $js);
    echo $url = url_for('script/load?param='.$parts[0].'&filename='.$parts[1]);die;
    return '<script type="text/javascript" src="'.$url.'" />';
    //$env = sfConfig::get('sf_environment');
    //return url_for($url);
}
?>