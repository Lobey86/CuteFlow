<?php
/*
 * Base class; DO NOT EDIT
 *
 * auto-generated by the sfDoctrine plugin
 */
class BaseUser extends sfDoctrineRecord
{
  
  
  public function setTableDefinition()
  {
    $this->setTableName('cf_user');

    $this->hasColumn('project_id', 'integer', 10, array ());
    $this->hasColumn('access_level', 'integer', 10, array ());
    $this->hasColumn('description', 'string', 4000, array ());
    $this->hasColumn('username', 'string', 4000, array ());
    $this->hasColumn('password', 'string', 4000, array ());
    $this->hasColumn('firstname', 'string', 4000, array ());
    $this->hasColumn('lastname', 'string', 4000, array ());
    $this->hasColumn('email', 'string', 4000, array ());
    $this->hasColumn('street', 'string', 4000, array ());
    $this->hasColumn('country', 'string', 4000, array ());
    $this->hasColumn('zipcode', 'integer', 10, array ());
    $this->hasColumn('city', 'string', 4000, array ());
    $this->hasColumn('telephone1', 'string', 4000, array ());
    $this->hasColumn('telephone2', 'string', 4000, array ());
    $this->hasColumn('mobilephone', 'string', 4000, array ());
    $this->hasColumn('fax', 'string', 4000, array ());
    $this->hasColumn('organisation', 'string', 4000, array ());
    $this->hasColumn('department', 'string', 4000, array ());
    $this->hasColumn('cost_center', 'string', 4000, array ());
    $this->hasColumn('userdefined1', 'string', 4000, array ());
    $this->hasColumn('userdefined2', 'string', 4000, array ());
    $this->hasColumn('created_at', 'integer', 10, array ());
    $this->hasColumn('updated_at', 'integer', 10, array ());
  }
  

  
  public function setUp()
  {
    $this->hasOne('Project as Project', array('local' => 'project_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    $this->hasMany('CrossProjectUser as CrossProjectUsers', array('local' => 'id', 'foreign' => 'user_id'));
    $this->hasMany('CrossTriggerUser as CrossTriggerUsers', array('local' => 'id', 'foreign' => 'user_id'));
    $this->ownsMany('Node as Nodes', array('local' => 'id', 'foreign' => 'receiver_id'));
    $this->ownsMany('Process as Processs', array('local' => 'id', 'foreign' => 'user_id'));
    $this->ownsMany('Process as Processs', array('local' => 'id', 'foreign' => 'substitute_id'));
    $this->ownsMany('UserFilter as UserFilters', array('local' => 'id', 'foreign' => 'user_id'));
    $this->ownsOne('UserIndex as UserIndexs', array('local' => 'id', 'foreign' => 'user_id'));
    $this->ownsMany('UserSubstitute as UserSubstitutes', array('local' => 'id', 'foreign' => 'user_id'));
    $this->ownsMany('UserSubstitute as UserSubstitutes', array('local' => 'id', 'foreign' => 'substitute_id'));
    $this->ownsMany('Workflow as Workflows', array('local' => 'id', 'foreign' => 'sender_id'));
  }
  
}
