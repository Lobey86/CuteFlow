<?php

class MergeAuthorization {

    public function  __construct() {

    }



    public function mergeRoles(array $allRoles, array $defaultAuth, array $auth) {
        foreach($allRoles as $singleRole) {
            $write = true;
            for($a=0;$a<count($auth);$a++) {
                $exists = $auth[$a];
                if($singleRole['description'] == $exists['type'] OR $singleRole['description'] == 'admin' OR $singleRole['description'] == 'sender' ) {
                    $write = false;
                }
            }
            if($write == true) {
                $lastArr = count($auth);
                $auth[$lastArr]['type'] = $singleRole['description'];
                $auth[$lastArr]['raw_type'] = $singleRole['description'];
                $auth[$lastArr]['id'] = -1;
                $auth[$lastArr]['isRole'] = true;
                $auth[$lastArr]['roleId'] = $singleRole['id'];
                $auth[$lastArr]['deleteworkflow'] = $defaultAuth[0]['deleteworkflow'];
                $auth[$lastArr]['archiveworkflow'] = $defaultAuth[0]['archiveworkflow'];
                $auth[$lastArr]['stopneworkflow'] = $defaultAuth[0]['stopneworkflow'];
                $auth[$lastArr]['detailsworkflow'] = $defaultAuth[0]['detailsworkflow'];
            }

        }
        return $auth;
    }

    


}
?>
