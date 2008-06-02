<?php
ini_set('session.save_path', sys_get_temp_dir());

/*
 * This file is part of the sfDoctrinePlugin package.
 * (c) 2006-2007 Jonathan H. Wage <jwage@mac.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDoctrinePlugin Command Line Interface Tasks
 *
 * This file contains all the symfony command line tasks. Some of these tasks are custom to symfony
 * but a majority of them are a wrapper for the already existing Doctrine command line interface.
 *
 * @package    sfDoctrinePlugin
 * @author     Jonathan H. Wage <jwage@mac.com>
 * @version    SVN: $Id: sfPakeDoctrine.php 8771 2008-05-04 23:24:23Z Jonathan.Wage $
 */
pake_desc('Export sql for doctrine schemas to data/sql');
pake_task('doctrine-build-sql', 'project_exists');

pake_desc('Insert sql for doctrine schemas in to database');
pake_task('doctrine-insert-sql', 'project_exists');

pake_desc('Build all Doctrine records');
pake_task('doctrine-build-model', 'project_exists');

pake_desc('Creates Doctrine CRUD Module');
pake_task('doctrine-generate-crud', 'app_exists');

pake_desc('Initialize a new doctrine admin module');
pake_task('doctrine-init-admin', 'app_exists');

pake_desc('Dump data to yaml fixtures file');
pake_task('doctrine-dump-data', 'project_exists');

pake_desc('Load data from yaml fixtures file');
pake_task('doctrine-load-data', 'project_exists');

pake_desc('Create database, generate models, insert sql');
pake_task('doctrine-build-all', 'project_exists');

pake_desc('Create database, generate models, insert sql, and load data from fixtures.');
pake_task('doctrine-build-all-load', 'project_exists');

pake_desc('Drops database, creates database, generate models, and loads data from fixtures.');
pake_task('doctrine-build-all-reload', 'project_exists');

pake_desc('Drops database, creates database, generate models, loads data from fixtures, and runs all tests.');
pake_task('doctrine-build-all-reload-test-all', 'project_exists');

pake_desc('Build yaml schema from an existing database');
pake_task('doctrine-build-schema', 'project_exists');

pake_desc('Create database');
pake_task('doctrine-build-db', 'project_exists');

pake_desc('Drop database');
pake_task('doctrine-drop-db', 'project_exists');

pake_desc('Drop database and rebuild it');
pake_task('doctrine-rebuild-db', 'project_exists');

pake_desc('Migrate database to current version or a specified version.');
pake_task('doctrine-migrate', 'project_exists');

pake_desc('Generate migration class template');
pake_task('doctrine-generate-migration', 'project_exists');

pake_desc('Generate migration classes from models');
pake_task('doctrine-generate-migrations-models', 'project_exists');

pake_desc('Generate migration classes from databases');
pake_task('doctrine-generate-migrations-db', 'project_exists');

pake_desc('Execute dql from the command line');
pake_task('doctrine-dql', 'project_exists');

pake_desc('Convert 0.1 schema to new Doctrine schema syntax');
pake_task('doctrine-convert-schema', 'project_exists');

/**
 * run_doctrine_convert_schema
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_convert_schema($task, $args)
{
    _load_doctrine();
    
    $directories = glob(sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'doctrine');
    $directories[] = sfConfig::get('sf_config_dir') . DIRECTORY_SEPARATOR . 'doctrine';
    
    $schemas = pakeFinder::type('file')->ignore_version_control()->name('*.yml')->in($directories);
    
    foreach ($schemas as $key => $schema)
    {
        $schemaArray = Doctrine_Parser::load($schema, 'yml');
        
        $schemaArray = _convert_schema($schemaArray);
        
        Doctrine_Parser::dump($schemaArray, 'yml', $schema);
    }
}

/**
 * _convert_schema
 *
 * @param string $schemaArray 
 * @return void
 * @author Jonathan H. Wage
 */

function _convert_schema($schemaArray)
{
    foreach ($schemaArray as $key => &$model)
    {
        $model['relations'] = array();
        foreach ($model['columns'] as $columnName => &$column)
        {
            $relation = _convert_column_to_relation($columnName, $column);
            
            if (!empty($relation)) {
                $model['relations'][$relation['alias']] = $relation;
            }
        }
    }
    
    return $schemaArray;
}

/**
 * _convert_column_to_relation
 *
 * @param string $columnName 
 * @param string $column 
 * @return void
 */
function _convert_column_to_relation($columnName, &$column)
{
    if (is_string($column) || ! $column)
    {
        return;
    }
    
    $map = array('foreignClass'     =>  'class:%s',
                 'foreignReference' =>  'foreign:%s',
                 'localName'        =>  'foreignAlias:%s',
                 'foreignName'      =>  'alias:%s',
                 'cascadeDelete'    =>  'onDelete:CASCADE',
                 'onDelete'         =>  'onDelete:%s');
    
    $relation = array();
    foreach ($column as $key => $value)
    {
        if (in_array($key, array_keys($map)))
        {
            unset($column[$key]);
            $new = sprintf($map[$key], $value);
            $e = explode(':', $new);
            
            $relation[$e[0]] = $e[1];
        }
    }
    
    // If no alias then set it to the class
    if (!empty($relation) && !isset($relation['alias']))
    {
        $relation['alias'] = $relation['class'];
    }
    
    if (!empty($relation))
    {
        $relation['local'] = $columnName;
    }
    
    // Unset b/c this is not needed and does not translate to anything
    // in the doctrine schema syntax
    unset($column['counterpart']);
    
    return $relation;
}

/**
 * run_doctrine_load_data
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_load_data($task, $args)
{
  _bootstrap_symfony();
  
  $pluginDirs = glob(sfConfig::get('sf_root_dir').'/plugins/*/data');
  $fixtures = pakeFinder::type('dir')->name('fixtures')->in(array_merge($pluginDirs, array(sfConfig::get('sf_data_dir'))));
  
  $config = array('data_fixtures_path' => $fixtures);
  
  _call_doctrine_cli('load-data', $config);
}

/**
 * run_doctrine_insert_sql
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_insert_sql($task, $args)
{
  _bootstrap_symfony();
  
  _call_doctrine_cli('create-tables');
}

/**
 * run_doctrine_build_sql
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_build_sql($task,$args)
{
  _bootstrap_symfony();
  
  _call_doctrine_cli('generate-sql');
}

/**
 * run_doctrine_build_model
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_build_model($task, $args)
{
  _load_doctrine();
  
  $config = _get_cli_config();
  
 	$pluginSchemaDirectories = glob(sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . '*' .DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'doctrine'); 
 	
 	$pluginSchemas = pakeFinder::type('file')->name('*.yml')->in($pluginSchemaDirectories);

 	$tmpPath = sfConfig::get('sf_cache_dir') . DIRECTORY_SEPARATOR . 'tmp';
 	
 	if ( ! file_exists($tmpPath))
 	{
 	  Doctrine_Lib::makeDirectories($tmpPath);
 	}

 	foreach ($pluginSchemas as $schema)
 	{
 	  $plugin = str_replace(sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR, '', $schema);
 	  $e = explode(DIRECTORY_SEPARATOR, $plugin);
 	  $plugin = $e[0];
 	  $name = basename($schema);
 	  
 	  $tmpSchemaPath = $tmpPath . DIRECTORY_SEPARATOR . $plugin . '-' . $name;

 	  $models = Doctrine_Parser::load($schema, 'yml');
 	  $models['package'] = $plugin . '.lib.model.doctrine';
 	  
 	  Doctrine_Parser::dump($models, 'yml', $tmpSchemaPath);
 	}
 	
  // Build models now
  $import = new Doctrine_Import_Schema();
  $import->setOption('generateBaseClasses', true);
  $import->setOption('generateTableClasses', true);
  $import->setOption('packagesPath', sfConfig::get('sf_plugins_dir'));
  $import->setOption('packagesPrefix', 'Plugin');
  $import->setOption('suffix', '.class.php');
  $import->setOption('baseClassesDirectory', 'generated');
  $import->setOption('baseClassName', 'sfDoctrineRecord');
  
  $import->importSchema(array($tmpPath, $config['yaml_schema_path']), 'yml', $config['models_path']);
  
  pake_echo_action('doctrine', 'Generated models successfully');
}

/**
 * run_doctrine_build_all
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_build_all($task, $args)
{
  run_doctrine_build_db($task, $args);
  run_doctrine_build_model($task, $args);
  run_doctrine_insert_sql($task, $args);
}

/**
 * run_doctrine_build_all_load
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_build_all_load($task, $args)
{
  run_doctrine_build_all($task, $args);
  run_doctrine_load_data($task, $args);
}

/**
 * run_doctrine_build_all_reload
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_build_all_reload($task, $args)
{
  run_doctrine_drop_db($task, $args);
  run_doctrine_build_db($task, $args);
  run_doctrine_build_model($task, $args);
  run_doctrine_insert_sql($task, $args);
  run_doctrine_load_data($task, $args);
}

/**
 * run_doctrine_build_all_reload_test_all
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_build_all_reload_test_all($task, $args)
{
  run_doctrine_build_all_reload($task, $args);
  run_test_all($task, $args);
}

/**
 * run_doctrine_build_schema
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_build_schema($task, $args)
{
  _bootstrap_symfony();
  
  _call_doctrine_cli('generate-yaml-db');
}

/**
 * run_doctrine_drop_db
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_drop_db($task, $args)
{
  _bootstrap_symfony();
  
  $force = isset($args[0]) ? 'force':null;
  _call_doctrine_cli('drop-db', array('force' => $force));
}

/**
 * run_doctrine_rebuild_db
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_rebuild_db($task, $args)
{
  _bootstrap_symfony();
  
  run_doctrine_drop_db($task, $args);
  run_doctrine_build_db($task, $args);
  run_doctrine_build_model($task, $args);
  run_doctrine_insert_sql($task, $args);
}

/**
 * run_doctrine_build_db
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_build_db($task, $args)
{
  _bootstrap_symfony();
  
  _call_doctrine_cli('create-db');
}

/**
 * run_doctrine_dump_data
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_dump_data($task, $args)
{  
  _bootstrap_symfony();
  
  $arguments = array();
  
  $dir = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'fixtures';
  if(!is_dir($dir))
    pake_mkdirs($dir);

  if (isset($args[0]))
  {
    $filename = $args[0];

    if ( ! sfToolkit::isPathAbsolute($filename))
    {
      $filename = $dir . DIRECTORY_SEPARATOR . $filename;
    }
    
    $arguments = array('data_fixtures_path' => $filename);
  }
  
  _call_doctrine_cli('dump-data', $arguments);
}

/**
 * run_doctrine_migrate
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_migrate($task, $args)
{
  _bootstrap_symfony();
  
  $to = isset($args[0]) ? $args[0]:null;
  
  _call_doctrine_cli('migrate', array('version' => $to));
}

/**
 * run_doctrine_generate_migration
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_generate_migration($task, $args)
{
  if ( ! isset($args[0])) {
    throw new sfException('You must specify the name of the migration class to generate.');
  }
  
  _bootstrap_symfony();
  
  _call_doctrine_cli('generate-migration', array('class_name' => $args[0]));
}

/**
 * run_doctrine_generate_migrations_models
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_generate_migrations_models($task, $args)
{
  _bootstrap_symfony();

  _call_doctrine_cli('generate-migrations-models');
}

/**
 * run_doctrine_generate_migrations_db
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_generate_migrations_db($task, $args)
{
  _bootstrap_symfony();

  _call_doctrine_cli('generate-migrations-db');
}

/**
 * run_doctrine_dql
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_dql($task, $args)
{ 
  if ( ! isset($args[0])) {
    throw new sfException('You must specify the DQL query to execute');
  }
  
  _bootstrap_symfony();
  
  _call_doctrine_cli('dql', array('dql_query' => $args[0]));
}

/**
 * run_doctrine_build_db
 * FIXME: has to be rewritten to avoid code duplication
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_generate_crud($task,$args)
{
  if (count($args) < 2)
  {
    throw new Exception('You must provide your module name.');
  }

  if (count($args) < 3)
  {
    throw new Exception('You must provide your model class name.');
  }

  $app         = $args[0];
  $module      = $args[1];
  $model_class = $args[2];
  $theme = isset($args[3]) ? $args[3] : 'crud';

  _bootstrap_symfony(array($app));

  // function variables
  $doctrineModelDir = sfConfig::get('sf_lib_dir').DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'doctrine'.DIRECTORY_SEPARATOR;
  $sf_root_dir = sfConfig::get('sf_root_dir');
  $sf_symfony_lib_dir = sfConfig::get('sf_symfony_lib_dir');
  $pluginDir = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..');
  $doctrineLibDir =$pluginDir.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'doctrine'.DIRECTORY_SEPARATOR.'Doctrine'.DIRECTORY_SEPARATOR;
  $tmp_dir = $sf_root_dir.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.md5(uniqid(rand(), true));

  sfConfig::set('sf_module_cache_dir', $tmp_dir);
  sfConfig::set('sf_app_dir', $tmp_dir);
  // add classes to autoload function
  pake_echo_action('PluginDir', $pluginDir);

  simpleAutoloader::registerCallable(array('Doctrine','autoload'));

  // generate module
  $generator_manager = new sfGeneratorManager();
  $generator_manager->initialize();
  $generator_manager->generate('sfDoctrineAdminGenerator', array('model_class' => $model_class, 'moduleName' => $module, 'theme' => $theme));
  $moduleDir = $sf_root_dir.'/'.sfConfig::get('sf_apps_dir_name').'/'.$app.'/'.sfConfig::get('sf_app_module_dir_name').'/'.$module;
  
  // copy our generated module
  $finder = pakeFinder::type('any');
  pake_mirror($finder, $tmp_dir.'/auto'.ucfirst($module), $moduleDir);

  // change module name
  pake_replace_tokens($moduleDir.'/actions/actions.class.php', getcwd(), '', '', array('auto'.ucfirst($module) => $module));

  try
  {
    $author_name = $task->get_property('author', 'symfony');
  } catch (pakeException $e) {
    $author_name = 'Your name here';
  }

  $constants = array(
    'PROJECT_NAME' => $task->get_property('name', 'symfony'),
    'APP_NAME'     => $app,
    'MODULE_NAME'  => $module,
    'MODEL_CLASS'  => $model_class,
    'AUTHOR_NAME'  => $author_name,
  );

  // customize php files
  $finder = pakeFinder::type('file')->name('*.php');
  pake_replace_tokens($finder, $moduleDir, '##', '##', $constants);

  // delete temp files
  $finder = pakeFinder::type('any');
  pake_remove($finder, $tmp_dir);

  // for some reason the above does not remove the tmp dir as it should.
  // delete temp dir
  @rmdir($tmp_dir);
  
  // delete cache/tmp
  @rmdir(sfConfig::get('sf_cache_dir').'tmp');
}

/**
 * run_doctrine_init_admin
 * FIXME: has to be rewritten to avoid code duplication
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function run_doctrine_init_admin($task, $args)
{
  if (count($args) < 2)
  {
    throw new Exception('You must provide your module name.');
  }

  if (count($args) < 3)
  {
    throw new Exception('You must provide your model class name.');
  }
    
  $app         = $args[0];
  $module      = $args[1];
  $model_class = $args[2];
  $theme       = isset($args[3]) ? $args[3] : 'default';

  try
  {
    $author_name = $task->get_property('author', 'symfony');
  } catch (pakeException $e) {
    $author_name = 'Your name here';
  }

  $constants = array(
    'PROJECT_NAME' => $task->get_property('name', 'symfony'),
    'APP_NAME'     => $app,
    'MODULE_NAME'  => $module,
    'MODEL_CLASS'  => $model_class,
    'AUTHOR_NAME'  => $author_name,
    'THEME'        => $theme,
  );

  $moduleDir = sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.sfConfig::get('sf_apps_dir_name').DIRECTORY_SEPARATOR.$app.DIRECTORY_SEPARATOR.sfConfig::get('sf_app_module_dir_name').DIRECTORY_SEPARATOR.$module;
  
  // create module structure
  $finder = pakeFinder::type('any')->ignore_version_control()->discard('.sf');
  $dirs = sfLoader::getGeneratorSkeletonDirs('sfDoctrineAdmin', $theme);
  foreach($dirs as $dir)
  {
    if (is_dir($dir))
    {
      pake_mirror($finder, $dir, $moduleDir);
      break;
    }
  }

  // customize php and yml files
  $finder = pakeFinder::type('file')->name('*.php', '*.yml');
  pake_replace_tokens($finder, $moduleDir, '##', '##', $constants);
}

/**
 * _bootstrap_symfony
 *
 * @param string $args 
 * @return void
 */
function _bootstrap_symfony($args = array())
{
  if (defined('SF_ROOT_DIR')) {
    return;
  }
  
  if ( ! count($args))
  {
    $applications = sfFinder::type('dir')->maxdepth(0)->ignore_version_control()->in(sfConfig::get('sf_root_dir') . DIRECTORY_SEPARATOR . 'apps');
    
    if (isset($applications[0])) {
      $app = basename($applications[0]);
    } else {
      throw new Exception('You must have at least one application');
    }
  } else {
    $app = $args[0];
  }
  
  if ( ! is_dir(sfConfig::get('sf_app_dir').DIRECTORY_SEPARATOR.$app))
  {
    throw new Exception('The app "'.$app.'" does not exist.');
  }
  
  $env = empty($args[1]) ? 'cli' : $args[1];
  
  // define constants
  define('SF_ROOT_DIR',    sfConfig::get('sf_root_dir'));
  define('SF_APP',         $app);
  define('SF_ENVIRONMENT', $env);
  define('SF_DEBUG',       true);

  // get configuration
  require_once SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';
  
  sfContext::getInstance();
}

/**
 * _load_doctrine
 *
 * @return void
 */
function _load_doctrine()
{
  spl_autoload_register(array('Doctrine', 'autoload'));
}

/**
 * _call_doctrine_cli
 *
 * @param string $task 
 * @param string $args 
 * @return void
 */
function _call_doctrine_cli($task, $args = array())
{
  $config = _get_cli_config();
  
  $arguments = array('./symfony', $task);
  
  foreach ($args as $key => $arg)
  {
    if (isset($config[$key]))
    {
      $config[$key] = $arg;
    } else {
      $arguments[] = $arg;
    }
  }
  
  $cli = new sfDoctrineCli($config);
  $cli->run($arguments);
}

function _get_cli_config()
{
  $fixtures = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'fixtures';
  $models = sfConfig::get('sf_model_lib_dir') . DIRECTORY_SEPARATOR . 'doctrine';
  $migrations = sfConfig::get('sf_lib_dir') . DIRECTORY_SEPARATOR . 'migration' . DIRECTORY_SEPARATOR . 'doctrine';
  $sql = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'sql';
  $yaml = sfConfig::get('sf_config_dir') . DIRECTORY_SEPARATOR . 'doctrine';
  
  $config = array('data_fixtures_path'  =>  $fixtures,
                  'models_path'         =>  $models,
                  'migrations_path'     =>  $migrations,
                  'sql_path'            =>  $sql,
                  'yaml_schema_path'    =>  $yaml);
  
  return $config;
}