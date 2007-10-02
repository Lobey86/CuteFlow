<?php
/*
 * Base class; DO NOT EDIT
 *
 * auto-generated by the sfDoctrine plugin
 */
class BaseTriggerDatabaseValue extends sfDoctrineRecord
{
  
  
  public function setTableDefinition()
  {
    $this->setTableName('cf_trigger_database_value');

    $this->hasColumn('breakpoint_id', 'integer', 10, array ());
    $this->hasColumn('trigger_id', 'integer', 10, array ());
    $this->hasColumn('required_value', 'string', 4000, array ());
  }
  

  
  public function setUp()
  {
    $this->hasOne('EventActionBreakpoint as EventActionBreakpoint', array('local' => 'breakpoint_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    $this->hasOne('Trigger as Trigger', array('local' => 'trigger_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
  }
  
}
