<?php

/**
 * FieldCheckboxgroup filter form base class.
 *
 * @package    cf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseFieldCheckboxgroupFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'field_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Field'), 'add_empty' => true)),
      'value'    => new sfWidgetFormFilterInput(),
      'isactive' => new sfWidgetFormFilterInput(),
      'position' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'field_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Field'), 'column' => 'id')),
      'value'    => new sfValidatorPass(array('required' => false)),
      'isactive' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('field_checkboxgroup_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FieldCheckboxgroup';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'field_id' => 'ForeignKey',
      'value'    => 'Text',
      'isactive' => 'Number',
      'position' => 'Number',
    );
  }
}
