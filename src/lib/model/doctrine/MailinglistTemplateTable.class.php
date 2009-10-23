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
     * @return Doctrine_Collection
     */
    public function getAllMailinglistTemplates($limit, $offset) {
        $query = Doctrine_Query::create()
                ->select('mlt.*')
                ->from('MailinglistTemplate mlt')
                ->where ('mlt.deleted = ?',0);
		if($limit != -1 AND $offset != -1) {
                    $query->limit($limit)
                          ->offset($offset);
                }
		return $query->orderBy('mlt.id DESC')
		->groupby('mlt.id')
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
     * Load all Mailinglists by filter
     *
     * @param int $limit
     * @param int $offset
     * @param Sring $search, search needle
     * @return Doctrine_Collection
     */
    public function getAllMailinglistTemplatesByFilter($limit, $offset, $search) {
        $query = Doctrine_Query::create()
            ->select('mlt.*')
            ->from('MailinglistTemplate mlt')
            ->where ('mlt.deleted = ?',0)
            ->andWhere('mlt.name LIKE ?','%'.$search.'%');
            if($limit != -1 AND $offset != -1) {
                $query->limit($limit)
                      ->offset($offset);
            }
            return $query->orderBy('mlt.id DESC')
            ->groupby('mlt.id')
            ->execute();
    }

    /**
     * Get number of records by filter
     * @param String $search, search needle
     * @return Doctrine_Collection
     */
    public function getTotalSumOfMailingListTemplatesByFilter($search) {
         return Doctrine_Query::create()
                    ->select('COUNT(*) AS anzahl')
                    ->from('MailinglistTemplate mlt')
                    ->where('mlt.deleted = ?', 0)
                    ->andWhere('mlt.name LIKE ?','%'.$search.'%')
                    ->execute();
    }

}