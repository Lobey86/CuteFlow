<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseWorkflowProcess extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cf_workflow_process');
        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'primary' => true, 'autoincrement' => true));
        $this->hasColumn('workflow_id', 'integer', 4, array('type' => 'integer', 'length' => 4));
        $this->hasColumn('revision_id', 'integer', 4, array('type' => 'integer', 'length' => 4));
        $this->hasColumn('node_id', 'integer', 4, array('type' => 'integer', 'length' => 4));
        $this->hasColumn('user_id', 'integer', 4, array('type' => 'integer', 'length' => 4));
        $this->hasColumn('subsititute_id', 'integer', 4, array('type' => 'integer', 'length' => 4));
        $this->hasColumn('decission_state', 'integer', 4, array('type' => 'integer', 'length' => 4));
        $this->hasColumn('decission_at', 'timestamp', null, array('type' => 'timestamp'));
        $this->hasColumn('resend_count', 'integer', 4, array('type' => 'integer', 'length' => 4));
        $this->hasColumn('created_at', 'timestamp', null, array('type' => 'timestamp'));
        $this->hasColumn('updated_at', 'timestamp', null, array('type' => 'timestamp'));
    }

    public function setUp()
    {
        $this->hasOne('User', array('local' => 'user_id',
                                    'foreign' => 'id'));

        $this->hasOne('Workflow', array('local' => 'workflow_id',
                                        'foreign' => 'id'));

        $this->hasOne('Revision', array('local' => 'revision_id',
                                        'foreign' => 'id'));

        $this->hasOne('SubstituteInformation', array('local' => 'substitute_id',
                                                     'foreign' => 'id'));

        $this->hasOne('Node', array('local' => 'node_id',
                                    'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_TimeStampable();
        $this->actAs($timestampable0);
    }
}