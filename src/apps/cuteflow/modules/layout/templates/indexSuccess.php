
<?php
    if($theTheme != 'DEFAULT') {
        echo '<link rel="stylesheet" type="text/css" media="screen" href="/themes/'.$theTheme.'" />';
    }
 ?>

<script type="text/javascript" src="/js/i18n/<?php echo Login::buildExtjsLanguage($sf_user->getCulture());?>/ext-lang.js"/>