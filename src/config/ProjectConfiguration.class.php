<?php
require_once dirname(__FILE__) . '/../lib/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // for compatibility / remove and enable only the plugins you want
    $this->enableAllPluginsExcept(array('sfPropelPlugin', 'sfCompat10Plugin'));
    date_default_timezone_set('UTC');
    $this->enablePlugins('sfPHPUnit2Plugin');
  }
}
