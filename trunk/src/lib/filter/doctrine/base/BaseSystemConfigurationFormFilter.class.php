<?php

/**
 * SystemConfiguration filter form base class.
 *
 * @package    cf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseSystemConfigurationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'language'                => new sfWidgetFormFilterInput(),
      'showpositioninmail'      => new sfWidgetFormFilterInput(),
      'allowunencryptedrequest' => new sfWidgetFormFilterInput(),
      'sendreceivermail'        => new sfWidgetFormFilterInput(),
      'sendremindermail'        => new sfWidgetFormFilterInput(),
      'visibleslots'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'language'                => new sfValidatorPass(array('required' => false)),
      'showpositioninmail'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'allowunencryptedrequest' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sendreceivermail'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sendremindermail'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'visibleslots'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('system_configuration_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SystemConfiguration';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'language'                => 'Text',
      'showpositioninmail'      => 'Number',
      'allowunencryptedrequest' => 'Number',
      'sendreceivermail'        => 'Number',
      'sendremindermail'        => 'Number',
      'visibleslots'            => 'Text',
    );
  }
}
