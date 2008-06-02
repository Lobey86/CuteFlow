<?php
/*
 * This file is part of the sfDoctrinePlugin package.
 * (c) 2006-2007 Jonathan H. Wage <jwage@mac.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package    sfDoctrinePlugin
 * @author     Jonathan H. Wage <jwage@mac.com>
 * @version    SVN: $Id: sfDoctrineAdminGenerator.class.php 8066 2008-03-24 23:54:01Z Jonathan.Wage $
 */
class sfDoctrineAdminGenerator extends sfAdminGenerator
{
  protected $table;

  public function initialize($generatorManager)
  {
    // otherwise the class never gets loaded... don't ask me why...
    include_once(sfConfig::get('sf_symfony_lib_dir').'/vendor/creole/CreoleTypes.php');
    parent::initialize($generatorManager);

    $this->setGeneratorClass('sfDoctrineAdmin');
  }

  protected function loadMapBuilderClasses()
  {
    $this->table = Doctrine::getTable($this->getClassName());
  }

  protected function getTable()
  {
    return $this->table;
  }

  protected function loadPrimaryKeys()
  {
    $identifier = $this->getTable()->getIdentifier();
    if (is_array($identifier))
    {
      foreach ($identifier as $_key)
      {
        $this->primaryKey[] = new sfDoctrineAdminColumn($_key);
      }
    }
    else
    {
      $this->primaryKey[] = new sfDoctrineAdminColumn($identifier);
    }
    // FIXME: check that there is at least one primary key [ and if there is not, what to do???? ]
  }

  public function getColumns($paramName, $category='NONE')
  {

    $columns = parent::getColumns($paramName, $category);

    // set the foreign key indicator
    $relations = $this->getTable()->getRelations();

    $cols = $this->getTable()->getColumns();

    foreach ($columns as $index => $column)
    {
      if (isset($relations[$column->getName()]))
      {
        $fkcolumn = $relations[$column->getName()];
        $columnName = $relations[$column->getName()]->getLocal();
        if ($columnName != 'id') // i don't know why this is necessary
        {
          $column->setRelatedClassName($fkcolumn->getTable()->getComponentName());
          $column->setColumnName($columnName);

          if (isset($cols[$columnName])) // if it is not a many2many
            $column->setColumnInfo($cols[$columnName]);

          $columns[$index] = $column;
        }
      }
    }

    return $columns;
  }

  function getAllColumns()
  {
    $cols = $this->getTable()->getColumns();
    $rels = $this->getTable()->getRelations();
    $columns = array();
    foreach ($cols as $name => $col)
    {
      // we set out to replace the foreign key to their corresponding aliases
      $found = null;
      foreach ($rels as $alias=>$rel)
      {
        $relType = $rel->getType();
        if ($rel->getLocal() == $name && $relType != Doctrine_Relation::MANY_AGGREGATE && $relType != Doctrine_Relation::MANY_COMPOSITE)
          $found = $alias;
      }
      if ($found)
      {
        $name = $found;
      }
      $columns[] = new sfDoctrineAdminColumn($name, $col);
    }
    return $columns;
  }

  function getAdminColumnForField($field, $flag = null)
  {
    $cols = $this->getTable()->getColumns(); // put this in an internal variable?
    $col = isset($cols[$field]) ? $cols[$field]:null;
    
    // This is a hack to make sure we get the column info for the relation
    // For lists. The admin generator is just not built to handle fields which are a part of a relationship
    // This makes it work for lists only
    if ( ! $col)
    {
      $e = explode('.', $field);
      if (isset($e[0]) && isset($e[1]))
      {
        if ($this->getTable()->hasRelation($e[0]))
        {
          $relation = $this->getTable()->getRelation($e[0]);
          $cols = $relation->getTable()->getColumns();
          if (isset($e[1]) && isset($cols[$e[1]]))
          {
            $col = $cols[$e[1]];
          }
        }
      }
    }
    
    return  new sfDoctrineAdminColumn($field, $col, $flag);
  }


  function getPHPObjectHelper($helperName, $column, $params, $localParams = array())
  {
    $params = $this->getObjectTagParams($params, $localParams);

    // special treatment for object_select_tag:
    if ($helperName == 'select_tag')
    {
      $column = new sfDoctrineAdminColumn($column->getColumnName(), null, null);
    }
    return sprintf ('object_%s($%s, %s, %s)', $helperName, $this->getSingularName(), var_export($this->getColumnGetter($column), true), $params);
  }

  function getColumnGetter($column, $developed = false, $prefix = '')
  {
    if ($developed)
    {
      $getter = sprintf("$%s%s->%s", $prefix, $this->getSingularName(), str_replace('.', '->', $column->getName()));
      return $getter;
    }
    
    // no parenthesis, we return a method+parameters array
    return array('get', array($column->getName()));
  }

  function getColumnSetter($column, $value, $singleQuotes = false, $prefix = 'this->')
  {
    if ($singleQuotes)
      $value = sprintf("'%s'", $value);
    return sprintf('$%s%s->set(\'%s\', %s)', $prefix, $this->getSingularName(), $column->getName(), $value);
  }

  function getRelatedClassName($column)
  {
    return $column->getRelatedClassName();
  }

  public function getColumnEditTag($column, $params = array())
  {
    if ($column->getDoctrineType() == 'enum' && !$column->isComponent() && !$column->isPartial())
    {
      // FIXME: this is called already in the sfAdminGenerator class!!!
      $params = array_merge(array('control_name' => $this->getSingularName().'['.$column->getName().']'), $params);

      $values = $this->getTable()->getEnumValues($column->getName());
      $params = array_merge(array('enumValues'=>$values), $params);
      return $this->getPHPObjectHelper('enum_tag', $column, $params);
    }
    return parent::getColumnEditTag($column, $params);
  }

  public function getColumnFilterTag($column, $params = array())
  {
    if ($column->getDoctrineType() == 'enum' && !$column->isComponent() && !$column->isPartial())
    {
      $default_value = "isset(\$filters['".$column->getName()."']) ? \$filters['".$column->getName()."'] : null";
      $unquotedName = 'filters['.$column->getName().']';
      $name = "'$unquotedName'";
      
      $option_params = $this->getObjectTagParams($params, array('include_blank' => true));
      $params = $this->getObjectTagParams($params);

      /** @todo There has got to be a better way to do this **/
      $values = $this->getTable()->getEnumValues($column->getName());
      foreach($values as $value) { $display_values[$value] = $value; }
      $display_values = var_export($display_values, 1);
      
      $options = "options_for_select($display_values, $default_value, $option_params)";

      return "select_tag($name, $options, $params)";
    }
    return parent::getColumnFilterTag($column, $params);
  }
}