<?php

require_once 'C:\Programme\xampp\php\PEAR\symfony/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enableAllPluginsExcept(array('sfPropelPlugin', 'sfCompat10Plugin'));
  }
}
