<?php



class WorkflowOverview {

    private $context;
    private $userId;
    private $culture;
    private $user;

    public function  __construct(sfContext $context_in, myUser $user) {
        sfLoader::loadHelpers('I18N');
        sfLoader::loadHelpers('Date');
        sfLoader::loadHelpers('CalculateDate');
        sfLoader::loadHelpers('ColorBuilder');
        $this->context = $context_in;
        $this->user = $user;
    }


    public function setUserId($id) {
        $this->userId = $id;
    }

    public function setCulture($culture) {
        $this->culture = $culture;
    }

    public function buildData(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        $userSettings = $this->user->getAttribute('userSettings');
        $openInPopUp = $userSettings['showcirculationinpopup'];
        foreach($data as $item) {
            $sender = UserLoginTable::instance()->findActiveUserById($item->getSenderId());
            $mailinglist = MailinglistTemplateTable::instance()->getMailinglistByVersionId($item->getMailinglisttemplateversionId());
            $inProgress = createDayOutOfDateSince($item->getVersioncreatedAt());
            $inProgress = addColor($inProgress, $userSettings['markred'],$userSettings['markorange'],$userSettings['markyellow']);
            $userdata = $sender[0]->getUserData()->toArray();
            $username = $sender[0]->getUsername() . ' (' . $userdata['firstname'] . ' ' . $userdata['lastname'] . ')';
            $result[$a]['#'] = $a+1;
            $result[$a]['id'] = $item->getId();
            $result[$a]['mailinglisttemplate_id'] = $item->getMailinglisttemplateversionId();
            $result[$a]['mailinglisttemplate'] = $mailinglist[0]->getName();
            $result[$a]['sender_id'] = $item->getSenderId();
            $result[$a]['sendername'] = $username;
            $result[$a]['name'] = $item->getName();
            $result[$a]['openinpopup'] = $openInPopUp;
            $result[$a]['isstopped'] = $item->getIsstopped();
            $result[$a]['process'] = $this->getProcess($item->getActiveversionId());
            if($item->getIscompleted() == 0 OR $item->getIscompleted() == '') {
                 $result[$a]['iscompleted'] = 0;
            }
            else {
                $result[$a]['iscompleted'] = 1;
                $result[$a]['process'] = '<div style="background-color:#00FF00; width:100px;">100 %'.'</div>';
            }

            $result[$a]['workflowisstarted'] = $item->getWorkflowisstarted();
            if($item->getIsstopped() == 1) {
                $result[$a]['currentstation'] = '<table><tr><td width="16"><img src="/images/icons/circ_stop.gif" /></td><td>'.$this->context->getI18N()->__('Workflow stopped' ,null,'workflowmanagement').'</td></tr></table>';
                $result[$a]['currentlyrunning'] = '-';
               // $result[$a]['process'] = '-';
            }
            elseif($item->getIscompleted() == 1) {
                $result[$a]['currentstation'] = '<table><tr><td width="16"><img src="/images/icons/circ_done.gif" /></td><td>'.$this->context->getI18N()->__('Workflow completed' ,null,'workflowmanagement').'</td></tr></table>';
                $result[$a]['currentlyrunning'] = '-';
            }
            elseif($item->getWorkflowisstarted() == 0) {
                $startdateofWorkflow = date('Y-m-d',$item->getStartworkflowAt());
                $startdateofWorkflow = format_date($startdateofWorkflow, 'p', $this->culture);
                $result[$a]['currentstation'] = '<table><tr><td width="16"><img src="/images/icons/circ_waiting.gif" /></td><td>'.$this->context->getI18N()->__('Startdate' ,null,'workflowmanagement').': '.$startdateofWorkflow.'</td></tr></table>';
                $result[$a]['currentlyrunning'] = '-';
            }
            else {
                $result[$a]['currentstation'] = $this->getCurrentStation($item->getActiveversionId(), $item->getSenderId());
                $result[$a]['currentlyrunning'] = '<table><tr><td width="20">' . $inProgress . ' </td><td>' . $this->context->getI18N()->__('Day(s)' ,null,'workflowmanagement') . '</td></tr></table>';
            }

            $result[$a]['versioncreated_at'] = format_date($item->getVersioncreatedAt(), 'g', $this->culture);
            
            $result[$a++]['activeversion_id'] = $item->getActiveversionId();
        }
        //print_r ($result);die;
        return $result;

    }



    public function getProcess($version_id) {
        $slots = WorkflowSlotTable::instance()->getSlotByVersionId($version_id);
        $alreadyCompleted = 0;
        $toComplete = 0;
        foreach($slots as $slot) {
            $users = WorkflowSlotUserTable::instance()->getUserBySlotId($slot->getId());
            $toComplete += count($users);
            foreach($users as $user) {
                $processUser = WorkflowProcessUserTable::instance()->getProcessUserByWorkflowSlotUserId($user->getId())->toArray();
                if(!empty($processUser)) {
                    foreach($processUser as $process) {
                        if($process['decissionstate'] != 'WAITING') {
                            $alreadyCompleted++;
                        }
                    }

                }
            }
        }
        
        $percentDone = ($toComplete/$toComplete);
        $fullPercentDone = 100 / $toComplete;
        $percentDone = $fullPercentDone * $alreadyCompleted;
        if($percentDone > 100) {
            $percentDone = 100;
        }
        $color = '';
        if($percentDone < 15) {

        }
        else if ($percentDone >= 15 AND $percentDone < 30) {
            $color = '#FF0000';
        }
        else if ($percentDone >= 30 AND $percentDone < 45) {
            $color = '#FF9933';
        }
        else if ($percentDone >= 45 AND $percentDone < 60) {
            $color = '#FFCC33';
        }
        else if ($percentDone >= 60 AND $percentDone < 75) {
            $color = '#FFFF33';
        }
        else if ($percentDone >= 75 AND $percentDone < 90) {
            $color = '#99FF99';
        }
        else {
            $color = '#00FF00';
        }
        return '<div style="background-color:'.$color.'; width:'.$percentDone.'px;">'.$percentDone . ' %'.'</div>';
    }


    public function getCurrentStation($activeversion_id, $sender_id) {
        $activeVersion = WorkflowProcessTable::instance()->getCurrentStation($activeversion_id);
        $user = $activeVersion[0]->getWorkflowProcessUser()->toArray();
        $workflowslot = $activeVersion[0]->getWorkflowSlot()->toArray();      
        $slot = DocumenttemplateSlotTable::instance()->getSlotById($workflowslot[0]['slot_id'])->toArray();
        $currentStation = $slot[0]['name'];
        $userdata = UserLoginTable::instance()->findActiveUserById($user['user_id'])->toArray();
        $username = $userdata[0]['username'];
        $currentStation .= ' <i>(' . $username . ')</i>';
        return $currentStation;
    }






}
?>