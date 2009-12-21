<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class DocumenttemplateTemplateTable extends Doctrine_Table {

    /**
     * create new instance of FormTemplate
     * @return object FormTemplate
     */
    public static function instance() {
        return Doctrine::getTable('DocumenttemplateTemplate');
    }


    /**
     * Load Total sum by filter
     * @param string $filter, filter
     * @return Doctrine_Collection
     */
    public function getTotalSumOfDocumentTemplatesByFilter($filter) {
        return Doctrine_Query::create()
                ->select('COUNT(*) AS anzahl')
                ->from('DocumenttemplateVersion dtv')
                ->leftJoin('dtv.DocumenttemplateTemplate dtt')
                ->where('dtt.deleted_at IS NULL')
                ->andWhere('dtv.activeversion = ?', 1)
                ->andWhere('dtt.name LIKE ?','%'.$filter.'%')
                ->execute();
        
    }
    /**
     * Get total sum of templates
     *
     * @return Doctrine_Collection
     */
    public function getTotalSumOfDocumentTemplates() {
        return Doctrine_Query::create()
                ->select('COUNT(*) AS anzahl')
                ->from('DocumenttemplateVersion dtv')
                ->leftJoin('dtv.DocumenttemplateTemplate dtt')
                ->where('dtt.deleted_at IS NULL')
                ->andWhere('dtv.activeversion = ?', 1)
                ->execute();
    }

    /**
     * Get all Docuemtn templates
     * @param int $limit
     * @param int $offset
     * @return Doctrine_Collection $query
     */
    public function getAllDocumentTemplates($limit, $offset) {
        $query =  Doctrine_Query::create()
            ->select('dtt.*, count(dts.id) AS number, dtv.id as documenttemplate_id')
            ->from('DocumenttemplateTemplate dtt')
            ->leftJoin('dtt.DocumenttemplateVersion dtv')
            ->leftJoin('dtv.DocumenttemplateSlot dts')
            ->where('dtt.deleted_at IS NULL')
            ->andWhere('dtv.activeversion = ?', 1);
            if($limit != -1 AND $offset != -1) {
                    $query->limit($limit)
                    ->offset($offset);
            }
            return $query->groupBy('dtt.id')
                         ->orderBy('dtt.id desc')
                         ->execute();
    }

    /**
     * Get all Docuemnt templates
     * @param int $limit
     * @param int $offset
     * @param string $filter, needle to search
     * @return Doctrine_Collection $query
     */
    public function getAllDocumentTemplatesByFilter($limit, $offset, $filter){
        $query =  Doctrine_Query::create()
            ->select('dtt.*, count(dts.id) AS number, dtv.id as documenttemplate_id')
            ->from('DocumenttemplateTemplate dtt')
            ->leftJoin('dtt.DocumenttemplateVersion dtv')
            ->leftJoin('dtv.DocumenttemplateSlot dts')
            ->where('dtt.deleted_at IS NULL')
            ->andWhere('dtv.activeversion = ?', 1)
            ->andWhere('dtt.name LIKE ?','%'.$filter.'%');
            if($limit != -1 AND $offset != -1) {
                    $query->limit($limit)
                    ->offset($offset);
            }
            return $query->groupBy('dtt.id')
                         ->orderBy('dtt.id desc')
                         ->execute();
        
    }

    /**
     *Delete Template
     *
     * @param int $id, id of entry to delete
     * @return <type>
     */
    public function deleteDocumentTemplateById($id) {
        Doctrine_Query::create()
            ->update('DocumenttemplateTemplate dtt')
            ->set('dtt.deleted_at','?', date('Y-m-d'))
            ->where('dtt.id = ?', $id)
            ->execute();
        return true;
    }


    /**
     * Get a documenttemplate by its version id
     *
     * @param int $id, if of the document version
     * @return Doctrine_Collection
     */
    public function getDocumentTemplateById($id) {
        return Doctrine_Query::create()
            ->select('dtt.*')
            ->from('DocumenttemplateTemplate dtt')
            ->leftJoin('dtt.DocumenttemplateVersion dtv')
            ->where('dtv.id = ?', $id)
            ->execute();
    }
    
}