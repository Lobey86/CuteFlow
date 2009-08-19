<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUser extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('cf_user');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('role_id', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             ));
        $this->hasColumn('firstname', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('lastname', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('email', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('username', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('password', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('street', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('zip', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('city', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('phone1', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('phone2', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('mobile', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('fax', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('organisation', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('department', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('burdenCenter', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('emailFormat', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('comment', 'object', null, array(
             'type' => 'object',
             ));
    }

    public function setUp()
    {
        $this->hasOne('Role', array(
             'local' => 'role_id',
             'foreign' => 'id'));

        $this->hasOne('UserSetting', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('UserAgent', array(
             'local' => 'id',
             'foreign' => 'user_id'));
    }
}