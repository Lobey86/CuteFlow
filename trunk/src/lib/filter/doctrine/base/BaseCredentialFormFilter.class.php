<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Credential filter form base class.
 *
 * @package    filters
 * @subpackage Credential *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCredentialFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'usermodule' => new sfWidgetFormFilterInput(),
      'usergroup'  => new sfWidgetFormFilterInput(),
      'userright'  => new sfWidgetFormFilterInput(),
      'roles_list' => new sfWidgetFormDoctrineChoiceMany(array('model' => 'Role')),
    ));

    $this->setValidators(array(
      'usermodule' => new sfValidatorPass(array('required' => false)),
      'usergroup'  => new sfValidatorPass(array('required' => false)),
      'userright'  => new sfValidatorPass(array('required' => false)),
      'roles_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'Role', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('credential_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addRolesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.CredentialRole CredentialRole')
          ->andWhereIn('CredentialRole.role_id', $values);
  }

  public function getModelName()
  {
    return 'Credential';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'usermodule' => 'Text',
      'usergroup'  => 'Text',
      'userright'  => 'Text',
      'roles_list' => 'ManyKey',
    );
  }
}