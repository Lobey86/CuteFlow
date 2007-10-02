<?php
/*
 * Base class; DO NOT EDIT
 *
 * auto-generated by the sfDoctrine plugin
 */
class BaseNode extends sfDoctrineRecord
{
  
  
  public function setTableDefinition()
  {
    $this->setTableName('cf_node');

    $this->hasColumn('receiver_id', 'integer', 10, array ());
    $this->hasColumn('workflow_id', 'integer', 10, array ());
    $this->hasColumn('template_id', 'integer', 10, array ());
    $this->hasColumn('template_slot_id', 'integer', 10, array ());
    $this->hasColumn('description', 'string', 4000, array ());
  }
  

  
  public function setUp()
  {
    $this->hasOne('User as User', array('local' => 'receiver_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    $this->hasOne('Workflow as Workflow', array('local' => 'workflow_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    $this->hasOne('Template as Template', array('local' => 'template_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    $this->hasOne('TemplateSlot as TemplateSlot', array('local' => 'template_slot_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    $this->ownsMany('Event as Events', array('local' => 'id', 'foreign' => 'node_id'));
    $this->ownsMany('NodeCondition as NodeConditions', array('local' => 'id', 'foreign' => 'node_id'));
    $this->ownsMany('NodeCondition as NodeConditions', array('local' => 'id', 'foreign' => 'destination_node_id'));
    $this->ownsMany('Process as Processs', array('local' => 'id', 'foreign' => 'node_id'));
  }
  
}
