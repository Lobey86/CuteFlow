<?php

/**
 * WorkflowSlot filter form base class.
 *
 * @package    cf
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseWorkflowSlotFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'workflowversion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('WorkflowVersion'), 'add_empty' => true)),
      'slot_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DocumenttemplateSlot'), 'add_empty' => true)),
      'position'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'workflowversion_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('WorkflowVersion'), 'column' => 'id')),
      'slot_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DocumenttemplateSlot'), 'column' => 'id')),
      'position'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('workflow_slot_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkflowSlot';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'workflowversion_id' => 'ForeignKey',
      'slot_id'            => 'ForeignKey',
      'position'           => 'Number',
    );
  }
}
