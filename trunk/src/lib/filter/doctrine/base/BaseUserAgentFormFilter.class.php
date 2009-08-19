<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UserAgent filter form base class.
 *
 * @package    filters
 * @subpackage UserAgent *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUserAgentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'        => new sfWidgetFormDoctrineChoice(array('model' => 'User', 'add_empty' => true)),
      'useragent_id'   => new sfWidgetFormFilterInput(),
      'durationtype'   => new sfWidgetFormFilterInput(),
      'durationlength' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'User', 'column' => 'id')),
      'useragent_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'durationtype'   => new sfValidatorPass(array('required' => false)),
      'durationlength' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_agent_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserAgent';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'user_id'        => 'ForeignKey',
      'useragent_id'   => 'Number',
      'durationtype'   => 'Text',
      'durationlength' => 'Text',
    );
  }
}