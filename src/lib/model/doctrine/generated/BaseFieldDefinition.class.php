<?php
/*
 * Base class; DO NOT EDIT
 *
 * auto-generated by the sfDoctrine plugin
 */
class BaseFieldDefinition extends sfDoctrineRecord
{
  
  
  public function setTableDefinition()
  {
    $this->setTableName('cf_field_definition');

    $this->hasColumn('field_id', 'integer', 10, array ());
    $this->hasColumn('definition_value', 'string', 4000, array ());
    $this->hasColumn('position', 'integer', 10, array ());
  }
  

  
  public function setUp()
  {
    $this->hasOne('Field as Field', array('local' => 'field_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
  }
  
}
