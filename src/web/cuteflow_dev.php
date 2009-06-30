<?php

##IP_CHECK##
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('cuteflow', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
