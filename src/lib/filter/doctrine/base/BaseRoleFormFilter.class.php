<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Role filter form base class.
 *
 * @package    filters
 * @subpackage Role *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseRoleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'description'      => new sfWidgetFormFilterInput(),
      'deleteable'       => new sfWidgetFormFilterInput(),
      'editable'         => new sfWidgetFormFilterInput(),
      'credentials_list' => new sfWidgetFormDoctrineChoiceMany(array('model' => 'Credential')),
    ));

    $this->setValidators(array(
      'description'      => new sfValidatorPass(array('required' => false)),
      'deleteable'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'editable'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'credentials_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'Credential', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('role_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addCredentialsListColumnQuery(Doctrine_Query $query, $field, $values)
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
          ->andWhereIn('CredentialRole.credential_id', $values);
  }

  public function getModelName()
  {
    return 'Role';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'description'      => 'Text',
      'deleteable'       => 'Number',
      'editable'         => 'Number',
      'credentials_list' => 'ManyKey',
    );
  }
}