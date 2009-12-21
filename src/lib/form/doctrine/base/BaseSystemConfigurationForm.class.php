<?php

/**
 * SystemConfiguration form base class.
 *
 * @method SystemConfiguration getObject() Returns the current form's model object
 *
 * @package    cf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseSystemConfigurationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'language'                => new sfWidgetFormInputText(),
      'showpositioninmail'      => new sfWidgetFormInputText(),
      'allowunencryptedrequest' => new sfWidgetFormInputText(),
      'sendreceivermail'        => new sfWidgetFormInputText(),
      'sendremindermail'        => new sfWidgetFormInputText(),
      'visibleslots'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'language'                => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'showpositioninmail'      => new sfValidatorInteger(array('required' => false)),
      'allowunencryptedrequest' => new sfValidatorInteger(array('required' => false)),
      'sendreceivermail'        => new sfValidatorInteger(array('required' => false)),
      'sendremindermail'        => new sfValidatorInteger(array('required' => false)),
      'visibleslots'            => new sfValidatorString(array('max_length' => 45, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('system_configuration[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SystemConfiguration';
  }

}
