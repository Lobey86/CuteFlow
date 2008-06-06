<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNode extends sfDoctrineRecord
{

  public function setTableDefinition()
  {
    $this->setTableName('cf_node');
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 255);
    $this->hasColumn('receiver_id', 'integer', 4);
    $this->hasColumn('workflow_id', 'integer', 4);
    $this->hasColumn('template_id', 'integer', 4);
    $this->hasColumn('template_slot_id', 'integer', 4);
    $this->hasColumn('created_at', 'timestamp', null);
    $this->hasColumn('updated_at', 'timestamp', null);
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('Workflow', array('local' => 'workflow_id',
                                    'foreign' => 'id'));

    $this->hasOne('Template', array('local' => 'template_id',
                                    'foreign' => 'id'));

    $this->hasOne('Slot', array('local' => 'template_slot_id',
                                'foreign' => 'id'));

    $this->hasOne('User as Receiver', array('local' => 'receiver_id',
                                            'foreign' => 'id'));

    $this->hasMany('WorkflowProcess as WorkflowProcesses', array('local' => 'id',
                                                                 'foreign' => 'node_id'));

    $this->hasMany('Event as Events', array('local' => 'id',
                                            'foreign' => 'node_id'));

    $this->hasMany('NodeCondition as Conditions', array('local' => 'id',
                                                        'foreign' => 'node_id'));

    $this->hasMany('NodeCondition as DependendNodes', array('local' => 'id',
                                                            'foreign' => 'destination_node_id'));

    $timestampable0 = new Doctrine_Template_Timestampable();
    $this->actAs($timestampable0);
  }

}
