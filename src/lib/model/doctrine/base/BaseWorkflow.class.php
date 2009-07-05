<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseWorkflow extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cf_workflow');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('sender_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('end_action', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('template_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('created_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('updated_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('deleted_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('archived_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
    }

    public function setUp()
    {
        $this->hasOne('User as Sender', array(
             'local' => 'sender_id',
             'foreign' => 'id'));

        $this->hasOne('Template', array(
             'local' => 'template_id',
             'foreign' => 'id'));

        $this->hasMany('WorkflowProcess as WorkflowProcesses', array(
             'local' => 'id',
             'foreign' => 'workflow_id'));

        $this->hasMany('Node as Nodes', array(
             'local' => 'id',
             'foreign' => 'workflow_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}