<?php

class WorkflowDetail {


    private $culture;
    private $user;
    private $context;

    public function  __construct() {
        sfLoader::loadHelpers('Date');
        sfLoader::loadHelpers('CalculateDate');
        sfLoader::loadHelpers('ColorBuilder');
        sfLoader::loadHelpers('I18N');
        sfLoader::loadHelpers('Icon');
    }

    
    public function setContext (sfContext $context_in) {
        $this->context = $context_in;
    }

    public function setCulture($culture_in) {
        $this->culture = $culture_in;
    }

    public function setUser(myUser $user_in) {
        $this->user = $user_in;
    }

    public function buildHeadLine(Doctrine_Collection $data) {
        $result = array();
        $workflowtemplate = WorkflowTemplateTable::instance()->getWorkflowTemplateById($data[0]->getWorkflowtemplateId());
        $mailinglist = $workflowtemplate[0]->getMailinglistTemplate()->toArray();
        $workflowtemplate = $workflowtemplate->toArray();
        $user = UserLoginTable::instance()->findActiveUserById($workflowtemplate[0]['sender_id']);
        $userdata = $user[0]->getUserData();

        $result['workflow'] = $workflowtemplate[0]['name'];
        $result['versionid'] = $data[0]->getId();
        $result['mailinglist'] = $mailinglist[0]['name'];
        $result['mailinglist_id'] = $workflowtemplate[0]['mailinglisttemplate_id'];
        $result['workflowtemplateid'] = $data[0]->getWorkflowtemplateId();
        $result['content'] = $data[0]->getContent();
        $result['sender_id'] = $workflowtemplate[0]['sender_id'];
        $result['sender'] = $userdata->getFirstname() . ' ' . $userdata->getLastname() . ' <i>('.$user[0]->getUsername().')</i>';
        $result['version'] = $this->getVersion($data[0]->getWorkflowtemplateId());
        return $result;
    }

    public function getVersion($workflowtemplate_id) {
        $allVersions = WorkflowVersionTable::instance()->getAllVersionByWorkflowId($workflowtemplate_id);
        $result = array();
        $a = 0;
        foreach($allVersions as $version) {
            $result[$a]['versionid'] = $version->getId();
            $result[$a]['version'] = $version->getVersion();
            $result[$a]['activeversion'] = $version->getActiveversion();
            $result[$a++]['text'] = '#' . $version->getVersion() . ' - ' . format_date($version->getCreatedAt(), 'g', $this->culture);
        }
        return $result;
    }







    public function buildUserData(Doctrine_Collection $data, $templateversion_id) {
        $result = array();
        $returnData = array();
        $a = 0;
        $slots = WorkflowSlotTable::instance()->getSlotByVersionId($data[0]->getId());
        foreach($slots as $slot) {
            $documenttemplateslot = $slot->getDocumenttemplateSlot()->toArray();
            $result[$a]['slotname'] = $documenttemplateslot[0]['name'];
            $result[$a]['workflowslot_id'] = $slot->getId();
            $result[$a]['sendtoallreceivers'] = $documenttemplateslot[0]['sendtoallreceivers'];
            $result[$a]['slot_id'] = $documenttemplateslot[0]['id'];
            $result[$a++]['user'] = $this->getUser($slot->getId(), $data[0]->getWorkflowtemplateId(), $documenttemplateslot[0]['name'], $a, $templateversion_id);
        }
        $returnData = $this->mergeArray($result);
        return $returnData;
    }


    public function getUser($slot_id, $workflowtemplate_id, $slotname, $slotcounter, $templateversion_id) {
        $users = WorkflowSlotUserTable::instance()->getUserBySlotId($slot_id);
        $result = array();
        $a = 0;
        foreach($users as $user) {
            $userlogin = array();
            $userlogin = $user->getUserLogin()->toArray();
            $result[$a]['user_id'] = $user->getUserId();
            $result[$a]['id'] = $user->getId();
            $result[$a]['username'] = '<table><tr><td width="16"><img src="/images/icons/user.png" /></td><td>' . $userlogin[0]['username'] . '</td></tr></table>';
            $result[$a]['slotgroup'] = '#' . $slotcounter . ' : ' . $slotname;
            $result[$a]['templateversion_id'] = $templateversion_id;
            $result[$a]['user'] = $this->getDecission($user);
            $a++;
        }
        
        return $result;
    }




    public function getDecission ($user) {
        $result = array();
        $a = 0;
        $processUsers = WorkflowProcessUserTable::instance()->getProcessUserByWorkflowSlotUserId($user->getId());
        foreach($processUsers as $processUser) {
            $result[$a] = $processUser->toArray();
            $result[$a]['received'] = format_date($result[$a]['inprogresssince'], 'g', $this->culture);
            $result[$a]['decission_id'] = $result[$a]['id'];
            $result[$a]['useragent_id'] = $result[$a]['user_id'];
            $result[$a]['isuseragentof'] = $result[$a]['isuseragentof'];
            $usersettings = $this->user->getAttribute('userSettings');
            $inProgress = createDayOutOfDateSince(date('Y-m-d', $result[$a]['inprogresssince']));
            $inProgress = addColor($inProgress, $usersettings['markred'],$usersettings['markorange'],$usersettings['markyellow']);
            $result[$a]['inprogresssince'] = '<table><tr><td width="20">' . $inProgress . ' </td><td>' . $this->context->getI18N()->__('Days' ,null,'workflowmanagement') . '</td></tr></table>';
            $result[$a]['decissioninwords'] = '<table><tr><td>'.AddStateIcon($result[$a]['decissionstate']).'</td><td>' . $this->context->getI18N()->__($result[$a]['decissionstate'],null,'workflowmanagement') . '</td></tr></table>';
            $a++;
        }
        return $result;
    }



    public function mergeArray(array $data) {
        $result = array();
        $slotcounter = 0;
        $userdata = array();
        $usercounter = 0;
        for($a=0;$a<count($data);$a++) {
            $result[$a]['slotname'] = $data[$a]['slotname'];
            $result[$a]['workflowslot_id'] = $data[$a]['workflowslot_id'];
            $result[$a]['sendtoallreceivers'] = $data[$a]['sendtoallreceivers'];
            $result[$a]['slot_id'] = $data[$a]['slot_id'];
            for($b=0;$b<count($data[$a]['user']);$b++) {
                $userData = $data[$a]['user'][$b];

                if(isset($userData['user'][0]) == true) {
                    for($d=0;$d<count($userData['user']);$d++) {
                        $decission = $userData['user'][$d];

                        $result[$a]['user'][$usercounter]['user_id'] = $userData['user_id'];
                        $result[$a]['user'][$usercounter]['id'] = $userData['id'];
                        $result[$a]['user'][$usercounter]['username'] = $userData['username'];
                        $result[$a]['user'][$usercounter]['slotgroup'] = $userData['slotgroup'];
                        $result[$a]['user'][$usercounter]['templateversion_id'] = $userData['templateversion_id'];

                        $result[$a]['user'][$usercounter]['useragent_id'] = $decission['useragent_id'];
                        $result[$a]['user'][$usercounter]['isuseragentof'] = $decission['isuseragentof'];
                        $result[$a]['user'][$usercounter]['workflowprocess_id'] = $decission['workflowprocess_id'];
                        $result[$a]['user'][$usercounter]['workflowslotuser_id'] = $decission['workflowslotuser_id'];
                        $result[$a]['user'][$usercounter]['inprogresssince'] = $decission['inprogresssince'];
                        $result[$a]['user'][$usercounter]['decissionstate'] = $decission['decissionstate'];
                        $result[$a]['user'][$usercounter]['dateofdecission'] = $decission['dateofdecission'];
                        $result[$a]['user'][$usercounter]['isuseragentof'] = $decission['isuseragentof'];
                        $result[$a]['user'][$usercounter]['received'] = $decission['received'];
                        $result[$a]['user'][$usercounter]['decission_id'] = $decission['decission_id'];
                        $result[$a]['user'][$usercounter]['decissioninwords'] = $decission['decissioninwords'];
                        if($result[$a]['user'][$usercounter]['isuseragentof'] != '') {
                            $userAgent = UserLoginTable::instance()->findActiveUserById($result[$a]['user'][$usercounter]['useragent_id'])->toArray();
                            $result[$a]['user'][$usercounter]['username'] = '<table><tr><td width="16">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="/images/icons/user_go.png" /></td><td>' . $userAgent[0]['username'] . '</td></tr></table>';
                        }
                        $usercounter++;
                    }
                }
                else {
                    $result[$a]['user'][$usercounter]['user_id'] = $userData['user_id'];
                    $result[$a]['user'][$usercounter]['id'] = $userData['id'];
                    $result[$a]['user'][$usercounter]['username'] = $userData['username'];
                    $result[$a]['user'][$usercounter]['slotgroup'] = $userData['slotgroup'];
                    $result[$a]['user'][$usercounter]['templateversion_id'] = $userData['templateversion_id'];

                    $result[$a]['user'][$usercounter]['useragent_id'] = '';
                    $result[$a]['user'][$usercounter]['isuseragentof'] = '';
                    $result[$a]['user'][$usercounter]['workflowprocess_id'] = '';
                    $result[$a]['user'][$usercounter]['workflowslotuser_id'] = '';
                    $result[$a]['user'][$usercounter]['inprogresssince'] = '';
                    $result[$a]['user'][$usercounter]['decissionstate'] = '';
                    $result[$a]['user'][$usercounter]['dateofdecission'] = '';
                    $result[$a]['user'][$usercounter]['isuseragentof'] = '';
                    $result[$a]['user'][$usercounter]['received'] = '';
                    $result[$a]['user'][$usercounter]['decission_id'] = '';
                    $result[$a]['user'][$usercounter]['decissioninwords'] = '';
                    $usercounter++;
                }
            }
            $usercounter = 0;

        }
        #print_r ($result);die;
        return $result;
    }





























}
?>