<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UserSetting filter form base class.
 *
 * @package    filters
 * @subpackage UserSetting *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUserSettingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => 'User', 'add_empty' => true)),
      'menue1expand'  => new sfWidgetFormFilterInput(),
      'menue2expand'  => new sfWidgetFormFilterInput(),
      'menue3expand'  => new sfWidgetFormFilterInput(),
      'refreshtime'   => new sfWidgetFormFilterInput(),
      'displayeditem' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'User', 'column' => 'id')),
      'menue1expand'  => new sfValidatorPass(array('required' => false)),
      'menue2expand'  => new sfValidatorPass(array('required' => false)),
      'menue3expand'  => new sfValidatorPass(array('required' => false)),
      'refreshtime'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'displayeditem' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('user_setting_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserSetting';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'user_id'       => 'ForeignKey',
      'menue1expand'  => 'Text',
      'menue2expand'  => 'Text',
      'menue3expand'  => 'Text',
      'refreshtime'   => 'Number',
      'displayeditem' => 'Number',
    );
  }
}