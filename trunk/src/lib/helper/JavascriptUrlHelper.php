<?php
function build_dynamic_javascript_url($js) {
    $parts = explode('/', $js);
    $url = url_for('script/load?param='.$parts[0].'&filename='.$parts[1]);
    return '<script type="text/javascript" src="'.$url.'" />';
}
?>