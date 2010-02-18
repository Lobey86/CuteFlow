

<div id="loading">
    <div id="loading-message" style="border:solid;"><table><tr><td><img src="/images/icons/loading.gif" /></td><td> <?php echo __('Loading CuteFlow. Please wait...','','layout');?></td></tr></table></div>
</div>




<script type="text/javascript" src="/djs/layout/main.js"></script>
<script type="text/javascript" src="/djs/layout/layout.js"></script>
<script type="text/javascript" src="/djs/layout/regionWest.js"></script>
<script type="text/javascript" src="/djs/layout/regionCenter.js"></script>
<script type="text/javascript" src="/djs/layout/regionNorth.js"></script>


<?php
    $test = new JavaScriptLoader();
    $files = $test->getAllFiles();
    $data = $files['djs'];
    foreach($data as $item) {
        echo ' <script type="text/javascript" src="'.$item.'"></script>' . "\n";
    }
?>

<input type="hidden" id="version_id" value="<?php echo $version_id?>">
<input type="hidden" id="workflow_id" value="<?php echo $workflow_id?>">
<input type="hidden" id="window" value="<?php echo $window?>">
<?php
    if($theTheme != 'DEFAULT') {
        echo '<link rel="stylesheet" type="text/css" media="screen" href="/themes/'.$theTheme.'" />';
    }
 ?>
<script type="text/javascript" src="/js/i18n/<?php echo Login::buildExtjsLanguage($sf_user->getCulture());?>/ext-lang.js"/>


