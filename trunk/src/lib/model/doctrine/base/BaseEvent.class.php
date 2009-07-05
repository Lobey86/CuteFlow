<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseEvent extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cf_event');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('position', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('type', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('node_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('created_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('updated_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('action_type', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('script_url', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('dsn', 'string', 512, array(
             'type' => 'string',
             'length' => '512',
             ));
        $this->hasColumn('table_name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('username', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('password', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('trigger_id', 'integer', null, array(
             'type' => 'integer',
             ));

        $this->setSubClasses(array(
             'PhpEvent' => 
             array(
              'action_type' => '1',
             ),
             'OdbcEvent' => 
             array(
              'action_type' => '2',
             ),
             'PdoEvent' => 
             array(
              'action_type' => '3',
             ),
             'BreakpointEvent' => 
             array(
              'action_type' => '4',
             ),
             ));
    }

    public function setUp()
    {
        $this->hasOne('Node', array(
             'local' => 'node_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}