<?php

/**
 * CredentialRole form base class.
 *
 * @package    form
 * @subpackage credential_role
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCredentialRoleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'role_id'       => new sfWidgetFormInputHidden(),
      'credential_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'role_id'       => new sfValidatorDoctrineChoice(array('model' => 'CredentialRole', 'column' => 'role_id', 'required' => false)),
      'credential_id' => new sfValidatorDoctrineChoice(array('model' => 'CredentialRole', 'column' => 'credential_id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('credential_role[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CredentialRole';
  }

}
