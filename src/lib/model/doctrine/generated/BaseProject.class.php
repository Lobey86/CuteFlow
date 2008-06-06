<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseProject extends sfDoctrineRecord
{

  public function setTableDefinition()
  {
    $this->setTableName('cf_project');
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 255);
    $this->hasColumn('description', 'string', 255);
    $this->hasColumn('created_at', 'timestamp', null);
    $this->hasColumn('updated_at', 'timestamp', null);
    $this->hasColumn('deleted_at', 'timestamp', null);
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('User as Users', array('refClass' => 'ProjectUser',
                                          'local' => 'project_id',
                                          'foreign' => 'user_id'));

    $timestampable0 = new Doctrine_Template_Timestampable();
    $this->actAs($timestampable0);
  }

}
