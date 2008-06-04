<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseFieldValue extends sfDoctrineRecord
{

  public function setTableDefinition()
  {
    $this->setTableName('cf_field_value');
    $this->hasColumn('field_id', 'integer', 4);
    $this->hasColumn('revision_id', 'integer', 4);
    $this->hasColumn('value', 'clob', null);
    $this->hasColumn('created_at', 'timestamp', null);
    $this->hasColumn('updated_at', 'timestamp', null);
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('Revision', array('local' => 'revision_id',
                                    'foreign' => 'id'));

    $this->hasOne('Field', array('local' => 'field_id',
                                 'foreign' => 'id'));

    $timestampable0 = new Doctrine_Template_Timestampable();
    $this->actAs($timestampable0);
  }

}
