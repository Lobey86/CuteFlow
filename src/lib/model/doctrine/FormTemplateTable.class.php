<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class FormTemplateTable extends Doctrine_Table {


    /**
     * create new instance of FormTemplate
     * @return object FormTemplate
     */
    public static function instance() {
        return Doctrine::getTable('FormTemplate');
    }


    /**
     * Load a Form template by its id
     *
     * @param int $id
     * @return Doctrine_Collection
     */
    public function getFormTemplateById($id) {
        return Doctrine_Query::create()
                    ->select('ft.*')
                    ->from('FormTemplate ft')
                    ->leftJoin('ft.FormSlot fs')
                    ->where('ft.deleted = ?', 0)
                    ->andWhere('ft.id = ?', $id)
                    ->orderBy('fs.position ASC')
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
    public function getAllFormTemplates($limit, $offset) {
        $query = Doctrine_Query::create()
                ->select('ft.*, count(fs.id) AS number')
                ->from('FormTemplate ft')
                ->leftJoin('ft.FormSlot fs')
                ->where ('ft.deleted = ?',0);
		if($limit != -1 AND $offset != -1) {
            $query->limit($limit)
                  ->offset($offset);
        }
		return $query->orderBy('ft.id DESC')
		->groupby('ft.id')
		->execute();
    }
	
    /**
     *
     * Load all forms with number of slots
     * return Doctrine_Collection
	 * @param int $limit, limit of records
	 * @param int $offset, offset
	 * @param string $name, searchneedle
	 * @return Doctrine_Collection
     */
    public function getAllFormTemplatesByFilter($limit, $offset, $name) {
        $query = Doctrine_Query::create()
                ->select('ft.*, count(fs.id) AS number')
                ->from('FormTemplate ft')
                ->where ('ft.deleted = ?',0)
				->leftJoin('ft.FormSlot fs')
				->andWhere('ft.name LIKE ?','%'.$name.'%');
		if($limit != -1 AND $offset != -1) {
            $query->limit($limit)
                  ->offset($offset);
        }
		return $query->orderBy('ft.id DESC')
		->groupby('ft.id')
		->execute();
    }


	/**
     * Get total sum of templates
     *
     * @return Doctrine_Collection
     */
    public function getTotalSumOfFormTemplates() {
        return Doctrine_Query::create()
                ->select('COUNT(*) AS anzahl')
                ->from('FormTemplate ft')
                ->where('ft.deleted = ?', 0)
                ->execute();
    }
	
	/**
	*
	* Get total sum of templates by filter
	*
	* @param string $name, needle to search
	* @return Doctrine_Collection
	*/
	public function getTotalSumOfFormTemplatesByFilter($name) {
	      return Doctrine_Query::create()
                ->select('COUNT(*) AS anzahl')
                ->from('FormTemplate ft')
                ->where('ft.deleted = ?', 0)
				->andWhere('ft.name LIKE ?','%'.$name.'%')
                ->execute();
	}
	
	
	
    /**
     * Delete an Form
     * @param int $id, id to delete
     * @return true;
     */
    public function deleteFormTemplate($id) {
        Doctrine_Query::create()
            ->update('FormTemplate ft')
            ->set('ft.deleted','?',1)
            ->where('ft.id = ?', $id)
            ->execute();
        return true;
    }


    /**
     *  Change the name of an template
     *
     * @param int $id, id of template
     * @param string $name, new name
     * @return true
     */
    public function updateFormTemplateNameById($id, $name) {
        Doctrine_Query::create()
            ->update('FormTemplate ft')
            ->set('ft.name','?',$name)
            ->where('ft.id = ?', $id)
            ->execute();
        return true;
    }


}