<?php #use_helper('Partial') ?>
<?php  #include_partial('javaScriptFiles'); ?>


<input type="hidden" id="version_id" value="<?php echo $version_id?>">
<input type="hidden" id="workflow_id" value="<?php echo $workflow_id?>">
<input type="hidden" id="window" value="<?php echo $window?>">
<?php
    if($theTheme != 'DEFAULT') {
        echo '<link rel="stylesheet" type="text/css" media="screen" href="/themes/'.$theTheme.'" />';
    }
 ?>

<script type="text/javascript" src="/js/i18n/<?php echo Login::buildExtjsLanguage($sf_user->getCulture());?>/ext-lang.js"/>