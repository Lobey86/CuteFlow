<?php

/**
 * UserAgent form base class.
 *
 * @package    form
 * @subpackage user_agent
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUserAgentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'user_id'        => new sfWidgetFormDoctrineChoice(array('model' => 'User', 'add_empty' => true)),
      'useragent_id'   => new sfWidgetFormInput(),
      'durationtype'   => new sfWidgetFormInput(),
      'durationlength' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorDoctrineChoice(array('model' => 'UserAgent', 'column' => 'id', 'required' => false)),
      'user_id'        => new sfValidatorDoctrineChoice(array('model' => 'User', 'required' => false)),
      'useragent_id'   => new sfValidatorInteger(array('required' => false)),
      'durationtype'   => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'durationlength' => new sfValidatorString(array('max_length' => 100, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_agent[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserAgent';
  }

}
