<?php

/**
 * Credential form base class.
 *
 * @package    form
 * @subpackage credential
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCredentialForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'usermodule' => new sfWidgetFormInput(),
      'usergroup'  => new sfWidgetFormInput(),
      'userright'  => new sfWidgetFormInput(),
      'roles_list' => new sfWidgetFormDoctrineChoiceMany(array('model' => 'Role')),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => 'Credential', 'column' => 'id', 'required' => false)),
      'usermodule' => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'usergroup'  => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'userright'  => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'roles_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'Role', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('credential[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Credential';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['roles_list']))
    {
      $this->setDefault('roles_list', $this->object->Roles->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveRolesList($con);
  }

  public function saveRolesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['roles_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Roles->getPrimaryKeys();
    $values = $this->getValue('roles_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Roles', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Roles', array_values($link));
    }
  }

}
