<?php
/**
 * This filter adds a timestamp to usertable in column lastaction,
 * to get information, if user is online or not
 *
 * filter is deactiveted for Login module.
 *
 */
class useractionFilter extends sfFilter {

    public function execute($filterChain) {
        $user_id =  sfContext::getInstance()->getUser()->getAttribute('id');
        $timestamp = time();
        $datum = date("Y-m-d",$timestamp);
        $uhrzeit = date("H:i:s",$timestamp);
        $date =  $datum . ' ' . $uhrzeit;
        
        Doctrine_Query::create()
            ->update('User u')
            ->set('u.lastaction','?',$date)
            ->where('u.id = ?', $user_id)
            ->execute();        
        $filterChain->execute();
    }

}

?>