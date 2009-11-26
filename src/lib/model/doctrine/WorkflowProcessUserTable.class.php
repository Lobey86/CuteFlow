<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class WorkflowProcessUserTable extends Doctrine_Table {


    /**
     *
     * create new instance of AdditionalText
     * @return object UserLoginTable
     */
    public static function instance() {
        return Doctrine::getTable('WorkflowProcessUser');
    }


    public function skipStation($id) {
         return Doctrine_Query::create()
                ->update('WorkflowProcessUser wpu')
                ->set('wpu.dateofdecission','?',time())
                ->set('wpu.decissionstate', '?', 'SKIPPED')
                ->where('wpu.id = ?', $id)
                ->execute();

    }


    public function getProcessUserByWorkflowSlotUserId($id) {
        return Doctrine_Query::create()
            ->select('wpu.*')
            ->from('WorkflowProcessUser wpu')
            ->where('wpu.workflowslotuser_id = ?', $id)
            ->orderBy('wpu.id ASC')
            ->execute();
    }


    

}