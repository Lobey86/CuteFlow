<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MailinglistTemplateTable extends Doctrine_Table {

    /**
     * create new instance of FormTemplate
     * @return object FormTemplate
     */
    public static function instance() {
        return Doctrine::getTable('MailinglistTemplate');
    }


    /**
     * Get total sum of mailinglist templates
     *
     * @return Doctrine_Collection
     */
    public function getTotalSumOfMailingListTemplates() {
        return Doctrine_Query::create()
                ->select('COUNT(*) AS anzahl')
                ->from('MailinglistTemplate mlt')
                ->where('mlt.deleted = ?', 0)
                ->execute();
    }



     /**
     *
     * Load all forms with number of slots
     * return Doctrine_Collection
     * @param int $limit, limit of records
     * @param int $offset, offset
     * @param string $filter, needle to filter
     * @return Doctrine_Collection
     */
    public function getAllMailinglistTemplatesByFilter($limit, $offset, $filter) {
        $query = Doctrine_Query::create()
            ->select('mlt.*')
            ->from('MailinglistTemplate mlt')
            ->leftJoin('mlt.MailinglistVersion mlv')
            ->where ('mlt.deleted = ?',0)
            ->andWhere('mlt.name LIKE ?','%'.$filter.'%')
            ->andWhere('mlv.activeversion = ?', 1);
            if($limit != -1 AND $offset != -1) {
                $query->limit($limit)
                      ->offset($offset);
            }
            return $query->orderBy('mlt.id DESC')
            ->groupBy('mlt.id')
            ->execute();
    }

    /**
     * Get total sum of records by filter
     * @param string $filter, needle to filter
     * @return Doctrine_Collection
     */
    public function getTotalSumOfMailingListTemplatesByFilter($filter) {
        return Doctrine_Query::create()
                ->select('COUNT(*) AS anzahl')
                ->from('MailinglistTemplate mlt')
                ->where('mlt.deleted = ?', 0)
                ->andWhere('mlt.name LIKE ?','%'.$filter.'%')
                ->execute();
    }


     /**
     *
     * Load all forms with number of slots
     * return Doctrine_Collection
     * @param int $limit, limit of records
     * @param int $offset, offset
     * @return Doctrine_Collection
     */
    public function getAllMailinglistTemplates($limit, $offset) {
        $query = Doctrine_Query::create()
                ->select('mlt.*')
                ->from('MailinglistTemplate mlt')
                ->leftJoin('mlt.MailinglistVersion mlv')
                ->where ('mlt.deleted = ?',0)
                ->andWhere('mlv.activeversion = ?', 1);
		if($limit != -1 AND $offset != -1) {
                    $query->limit($limit)
                          ->offset($offset);
                }
		return $query->orderBy('mlt.id DESC')
		->groupBy('mlt.id')
		->execute();

    }


    /**
     * Get name of template by id of mailinglisteversion
     *
     * @param int $id, id of version
     * @return Doctrine_Collection
     */
    public function getMailinglistByVersionId($id) {
        return Doctrine_Query::create()
                    ->select('mlt.*')
                    ->from('MailinglistTemplate mlt')
                    ->leftJoin('mlt.MailinglistVersion mlv')
                    ->where('mlv.id = ?', $id)
                    ->execute();
        
    }

    /**
     * Set templates to disabled
     * @return true
     */
    public function setAllTemplatesDisabledById() {
        Doctrine_Query::create()
            ->update('MailinglistTemplate mlt')
            ->set('mlt.isactive','?', 0)
            ->execute();
        return true;
    }


    /**
     * Activate single template
     * @param int $id, id of the template
     * @return true
     */
    public function activateTemplateById($id) {
        Doctrine_Query::create()
            ->update('MailinglistTemplate mlt')
            ->set('mlt.isactive','?', 1)
            ->where('mlt.id = ?', $id)
            ->execute();
        return true;
    }

    /**
     * Delete Mailinglist by id
     * @param int $id
     * @return true
     */
    public function deleteMailinglistTemplateById($id) {
        Doctrine_Query::create()
            ->update('MailinglistTemplate mlt')
            ->set('mlt.deleted','?', 1)
            ->where('mlt.id = ?', $id)
            ->execute();
        return true;
    }




    /**
     *
     * @param int $id
     * @return Dorctrine_Collection
     */
    public function getMailinglistById($id) {
        return Doctrine_Query::create()
            ->select('mlt.*')
            ->from('MailinglistTemplate mlt')
            ->where('mlt.deleted = ?', 0)
            ->andWhere('mlt.id = ?', $id)
            ->execute();
    }


    /**
     *
     * @param int $id
     * @return Dorctrine_Collection
     */
    public function getMailinglistByDocumentTemplateId($id) {
        return Doctrine_Query::create()
            ->select('mlt.*')
            ->from('MailinglistTemplate mlt')
            ->where('mlt.deleted = ?', 0)
            ->andWhere('mlt.formtemplate_id = ?', $id)
            ->execute();
    }


}