<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseFieldDefinition extends sfDoctrineRecord
{

  public function setTableDefinition()
  {
    $this->setTableName('cf_field_definition');
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('field_id', 'integer', 4);
    $this->hasColumn('definition_value', 'string', null);
    $this->hasColumn('position', 'integer', null);
    $this->hasColumn('created_at', 'timestamp', null);
    $this->hasColumn('updated_at', 'timestamp', null);
    $this->hasColumn('deleted_at', 'timestamp', null);
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('Field', array('local' => 'field_id',
                                 'foreign' => 'id'));

    $timestampable0 = new Doctrine_Template_Timestampable();
    $this->actAs($timestampable0);
  }

}
