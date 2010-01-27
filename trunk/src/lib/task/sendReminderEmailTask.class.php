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
class sendReminderEmailTask extends sfBaseTask {

    protected function configure() {
    
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'cuteflow'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', ''),
      new sfCommandOption('host', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'http://cuteflow'), // http://cuteflow is default
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = '';
    $this->name             = 'sendReminderEmail';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [sendReminderEmail|INFO] task does things.
Call it with:

  [php symfony sendReminderEmail|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array()) {
    // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
        $context = sfContext::createInstance($this->configuration);
        sfProjectConfiguration::getActive()->loadHelpers('Partial', 'I18N', 'Url');
        if($options['env'] == '') {
            $serverUrl = $options['host'];
        }
        else {
            $serverUrl = $options['host'] . '/' . $options['env'];
        }
        $wfSettings = SystemConfigurationTable::instance()->getSystemConfiguration()->toArray();
        if($wfSettings[0]['sendremindermail'] == 1) {
            
            $sendMail = new PrepareReminderEmail();
            $stillOpenWorkflows = array();
            $a = 0;
            $openWorkflows = WorkflowTemplateTable::instance()->getAllRunningWorkflows();
            foreach($openWorkflows as $workflow) {
                $openStations = WorkflowProcessUserTable::instance()->getWaitingStationByVersionId($workflow['WorkflowVersion']['id'])->toArray();
                $data = $sendMail->prepareData($openStations);
                $stillOpenWorkflows[$a]['workflow_id'] = $workflow['id'];
                $stillOpenWorkflows[$a]['name'] = $workflow['name'];
                $stillOpenWorkflows[$a]['workflowversion_id'] = $workflow['WorkflowVersion']['id'];
                $stillOpenWorkflows[$a++]['users'] = $data;
            }
            $stillOpenWorkflows = $sendMail->sortByUser($stillOpenWorkflows);
            foreach($stillOpenWorkflows as $userToSend) {
                $umSettings = new UserMailSettings($userToSend['user_id']);
                $reminder = new SendReminderEmail($umSettings,$context, $userToSend, $serverUrl);
            }

        }


        

    // add your code here
    }
}
