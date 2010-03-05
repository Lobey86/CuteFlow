<?php
    $ccCache = new TemplateCaching();
    $ccCache->setFiles();
    $lastModified = $ccCache->getLastModifiedFile();
    $cacheCreated = $ccCache->getCurrentCacheStamp();
    //echo $lastModified . ' ' . $cacheCreated;die;
    if($lastModified > $cacheCreated OR $cacheCreated == '') {
        if($cacheCreated == '') {
            $cacheCreated = $lastModified;
        }
        $ccCache->createCache($lastModified, $cacheCreated);
    }
?>

<div id="loading">
    <div id="loading-message" style="border:solid;"><table><tr><td><img src="/images/icons/loading.gif" /></td><td> <?php echo __('Loading CuteFlow. Please wait...','','layout');?></td></tr></table></div>
</div>


<?php
    $dir = array_diff(scandir(sfConfig::get('sf_app_dir') . '/cache'), Array( ".", "..",".svn"));
    echo ' <script type="text/javascript" src="/djs/cache/'.$dir[2].'"></script>' . "\n";
    
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


