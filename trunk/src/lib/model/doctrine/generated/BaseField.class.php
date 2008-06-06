<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseField extends sfDoctrineRecord
{

  public function setTableDefinition()
  {
    $this->setTableName('cf_field');
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('description', 'string', null);
    $this->hasColumn('default_value', 'string', null);
    $this->hasColumn('type', 'integer', null);
    $this->hasColumn('is_read_only', 'integer', null);
    $this->hasColumn('created_at', 'timestamp', null);
    $this->hasColumn('updated_at', 'timestamp', null);
    $this->hasColumn('deleted_at', 'timestamp', null);
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('FieldDefinition as FieldDefinitions', array('local' => 'id',
                                                                'foreign' => 'field_id'));

    $this->hasMany('FieldValue as FieldValues', array('local' => 'id',
                                                      'foreign' => 'field_id'));

    $this->hasMany('Slot as Slots', array('refClass' => 'SlotField',
                                          'local' => 'field_id',
                                          'foreign' => 'slot_id'));

    $timestampable0 = new Doctrine_Template_Timestampable();
    $this->actAs($timestampable0);
  }

}
