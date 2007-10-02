<?php
/*
 * Base class; DO NOT EDIT
 *
 * auto-generated by the sfDoctrine plugin
 */
class BaseTriggerTime extends sfDoctrineRecord
{
  
  
  public function setTableDefinition()
  {
    $this->setTableName('cf_trigger_time');

    $this->hasColumn('breakpoint_id', 'integer', 10, array ());
    $this->hasColumn('time_value', 'string', 4000, array ());
  }
  

  
  public function setUp()
  {
    $this->hasOne('EventActionBreakpoint as EventActionBreakpoint', array('local' => 'breakpoint_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
  }
  
}
