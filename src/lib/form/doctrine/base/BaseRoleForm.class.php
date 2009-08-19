<?php

/**
 * Role form base class.
 *
 * @package    form
 * @subpackage role
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseRoleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'description'      => new sfWidgetFormInput(),
      'deleteable'       => new sfWidgetFormInput(),
      'editable'         => new sfWidgetFormInput(),
      'credentials_list' => new sfWidgetFormDoctrineChoiceMany(array('model' => 'Credential')),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => 'Role', 'column' => 'id', 'required' => false)),
      'description'      => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'deleteable'       => new sfValidatorInteger(array('required' => false)),
      'editable'         => new sfValidatorInteger(array('required' => false)),
      'credentials_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'Credential', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('role[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Role';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['credentials_list']))
    {
      $this->setDefault('credentials_list', $this->object->Credentials->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveCredentialsList($con);
  }

  public function saveCredentialsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['credentials_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Credentials->getPrimaryKeys();
    $values = $this->getValue('credentials_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Credentials', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Credentials', array_values($link));
    }
  }

}
