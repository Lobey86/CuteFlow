<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MailinglistVersionTable extends Doctrine_Table {


        /**
     * create new instance of MailinglistAuthorizationSetting
     * @return object MailinglistAuthorizationSetting
     */
    public static function instance() {
        return Doctrine::getTable('MailinglistVersion');
    }


    public function getVersionById($id) {
        return Doctrine_Query::create()
                    ->select('mlv.*')
                    ->from('MailinglistVersion mlv')
                    ->where('mlv.id = ?', $id)
                    ->execute();
    }

    public function setMailinglistInactiveById($id) {
        Doctrine_Query::create()
            ->update('MailinglistVersion mlv')
            ->set('mlv.activeversion','?', 0)
            ->where('mlv.id = ?', $id)
            ->execute();
    }

    public function getActiveVersionById($id) {
        return Doctrine_Query::create()
            ->select('mlv.*')
            ->from('MailinglistVersion mlv')
            ->where('mlv.mailinglisttemplate_id = ?', $id)
            ->andWhere('mlv.activeversion = ?', 1)
            ->execute();

    }


    
}