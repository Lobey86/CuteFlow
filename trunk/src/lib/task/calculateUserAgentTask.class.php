<?php
/**
 *
 * The Task can be called in DEV and PROD environment, by default productive system is loaded
 *
 * env: "" = Productive system,
 *      cuteflow_dev.php = DEV System
 *
 * call task: php symfony calculateUserAgent --env="" --host="http://cuteflow"
 *
 */
class calculateUserAgentTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'cuteflow'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', ''), // for dev, use cuteflow_dev.php
      new sfCommandOption('host', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'http://cuteflow'), // http://cuteflow is default
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = '';
    $this->name             = 'calculateUserAgent';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [calculateUserAgent|INFO] task does things.
Call it with:

  [php symfony calculateUserAgent|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
        $context = sfContext::createInstance($this->configuration);
        $context->getConfiguration()->loadHelpers('Partial', 'I18N', 'Url', 'Date', 'CalculateDate', 'ColorBuilder', 'Icon');
        if($options['env'] == '') {
            $serverUrl = $options['host'];
        }
        else {
            $serverUrl = $options['host'] . '/' . $options['env'];
        }
        #$process = WorkflowProcessUserTable::instance()->getWaitingProcess();
        #$sub = new CheckSubstitute($process, $context, $serverUrl);

        #die;
        $versionId = 1;
        $templateId = 1;
        $user_id = 1;

        $test = new PrepareStationEmail($versionId, $templateId, $user_id, $context, $serverUrl);
        die;
    }
}
