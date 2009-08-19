<?php

/**
 * UserSetting form base class.
 *
 * @package    form
 * @subpackage user_setting
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUserSettingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => 'User', 'add_empty' => true)),
      'menue1expand'  => new sfWidgetFormInput(),
      'menue2expand'  => new sfWidgetFormInput(),
      'menue3expand'  => new sfWidgetFormInput(),
      'refreshtime'   => new sfWidgetFormInput(),
      'displayeditem' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => 'UserSetting', 'column' => 'id', 'required' => false)),
      'user_id'       => new sfValidatorDoctrineChoice(array('model' => 'User', 'required' => false)),
      'menue1expand'  => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'menue2expand'  => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'menue3expand'  => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'refreshtime'   => new sfValidatorInteger(array('required' => false)),
      'displayeditem' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_setting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserSetting';
  }

}
