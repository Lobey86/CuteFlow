<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseFieldCombobox extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cf_fieldcombobox');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('field_id', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             ));
    }

    public function setUp()
    {
        $this->hasOne('Field', array(
             'local' => 'field_id',
             'foreign' => 'id'));
    }
}