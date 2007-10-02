<?php
/*
 * Base class; DO NOT EDIT
 *
 * auto-generated by the sfDoctrine plugin
 */
class BaseUserIndex extends sfDoctrineRecord
{
  
  
  public function setTableDefinition()
  {
    $this->setTableName('cf_user_index');

    $this->hasColumn('user_id', 'integer', 10, array (  'unique' => true,));
    $this->hasColumn('user_index', 'string', 4000, array ());
  }
  

  
  public function setUp()
  {
    $this->ownsOne('User as User', array('local' => 'user_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
  }
  
}
