<?php

class CreateWorkflowAuthorizationRights {

    public $defaultRole;
    public $userroleName;
    public $userId;

    public function  __construct() {
        
    }


    public function setDefaultRole() {
        $roles = AuthorizationConfigurationTable::instance()->getAllRoles()->toArray();
        $this->defaultRole = $roles[0];
    }

    public function setUserRole($userId) {
        $role = RoleTable::instance()->getRoleByUserId($userId)->toArray();
        $this->userroleName = $role[0]['description'];
        $this->userId = $userId;

        
    }



    public function getRights($mailinglistVersionId, $workflowversionid) {
        $roleCheck = $this->checkRole($mailinglistVersionId); // checks if the role of the user, is appearing in the mailinglist auth settings. if not, default settings are loaded
        $allowedSenderCheck = $this->checkAllowedSender($mailinglistVersionId);
        $sendingRight = $this->checkSendingRight($mailinglistVersionId);
        $receiver = $this->checkReceiver($workflowversionid, $mailinglistVersionId);
        $result['deleteworkflow'] = $this->mergeRights($roleCheck, $allowedSenderCheck, $sendingRight, $receiver, 'deleteworkflow');
        $result['archiveworkflow'] = $this->mergeRights($roleCheck, $allowedSenderCheck, $sendingRight, $receiver, 'archiveworkflow');
        $result['stopneworkflow'] = $this->mergeRights($roleCheck, $allowedSenderCheck, $sendingRight, $receiver, 'stopneworkflow');
        $result['detailsworkflow'] = $this->mergeRights($roleCheck, $allowedSenderCheck, $sendingRight, $receiver, 'detailsworkflow');
        return $result;
    }


    public function mergeRights(array $roles, array $allowedsender, array $sendingRight, array $receiver, $offset) {
        $result = array();
        
        $value = $roles[$offset];
        if($allowedsender['allowedtosend'] == 1) {
            if($value != 1) {
                $value = $allowedsender[$offset];
            }

        }
        if($sendingRight['allowedtosend'] == 1) {
            if($value != 1) {
                $value = $sendingRight[$offset];
            }
        }
        if($receiver['allowedtosend'] == 1) {
            if($value != 1) {
                $value = $receiver[$offset];
            }
        }
       return $value;
    }

    public function checkReceiver($workflowversionId, $mailinglistVersionId) {
        $receiver = WorkflowSlotUserTable::instance()->getUserByWorkflowVersionId($this->userId, $workflowversionId)->toArray();
        if(empty($receiver) == true) {
            $result['allowedtosend'] = 0;
            return $result;
        }
        else {
            $sender = MailinglistAuthorizationSettingTable::instance()->getSettingsByType('receiver', $mailinglistVersionId)->toArray();
            $sender[0]['allowedtosend'] = 1;
            return $sender[0];
        }
        
    }

    public function checkSendingRight($mailinglistVersionId) {
        
        $credentialId = CredentialTable::instance()->getCredentialIdByRight('workflow','workflowmanagement','sendWorkflow')->toArray();
        $rightCheck = RoleTable::instance()->getRoleByRightAndRoleName($credentialId[0]['id'], $this->userroleName)->toArray();
        if(empty($rightCheck) == true) {
            $result['allowedtosend'] = 0;
            return $result;
        }
        else {
            $sender = MailinglistAuthorizationSettingTable::instance()->getSettingsByType('senderwithrights', $mailinglistVersionId)->toArray();
            $sender[0]['allowedtosend'] = 1;
            return $sender[0];
        }
        
    }

    public function checkAllowedSender($mailinglistVersionId) {
        $allowedsender = MailinglistAllowedSenderTable::instance()->getAllowedSenderByMailinglistIdAndUserId($this->userId, $mailinglistVersionId)->toArray();
        if(empty($allowedsender) == true) {
            $result['allowedtosend'] = 0;
            return $result;
        }
        else {
            $sender = MailinglistAuthorizationSettingTable::instance()->getSettingsByType('allowedsender', $mailinglistVersionId)->toArray();
            $sender[0]['allowedtosend'] = 1;
            return $sender[0];
        }
    }



    public function checkRole($mailinglistVersionId) {
        $rights = MailinglistAuthorizationSettingTable::instance()->getSettingsByType($this->userroleName, $mailinglistVersionId)->toArray();
        if(empty($rights) == true) {
            return $this->defaultRole;
        }
        else {
            return $rights[0];
        }
    }


    






}
?>
