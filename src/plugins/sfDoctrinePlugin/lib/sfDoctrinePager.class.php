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
 * @version    SVN: $Id: sfDoctrinePager.class.php 8888 2008-05-10 01:50:47Z Jonathan.Wage $
 */
class sfDoctrinePager extends sfPager implements Serializable
{
  protected $query;

  /**
   * __construct
   *
   * @package default
   */
  public function __construct($class, $defaultMaxPerPage = 10)
  {
    parent::__construct($class, $defaultMaxPerPage);

    $this->setQuery(Doctrine_Query::create()->from($class));
  }

  /**
   * serialize
   *
   * @return void
   */
  public function serialize()
  {
    $vars = get_object_vars($this);
    unset($vars['query']);
    return serialize($vars);
  }

  /**
   * unserialize
   *
   * @param string $serialized 
   * @return void
   */
  public function unserialize($serialized)
  {
    $array = unserialize($serialized);

    foreach($array as $name => $values)
    {
        $this->$name = $values;
    }
  }

  /**
   * init
   *
   * @return void
   */
  public function init()
  {
    $count = $this->getQuery()->offset(0)->limit(0)->count();

    $this->setNbResults($count);

    $p = $this->getQuery();
    $p->offset(0);
    $p->limit(0);
    if ($this->getPage() == 0 || $this->getMaxPerPage() == 0)
    {
      $this->setLastPage(0);
    } else {
      $offset = ($this->getPage() - 1) * $this->getMaxPerPage();

      $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));

      $p->offset($offset);
      $p->limit($this->getMaxPerPage());
    }
  }

  /**
   * getQuery
   *
   * @return void
   */
  public function getQuery()
  {
    return $this->query;
  }

  /**
   * setQuery
   *
   * @param string $query 
   * @return void
   */
  public function setQuery($query)
  {
    $this->query = $query;
  }

  /**
   * retrieveObject
   *
   * @param string $offset 
   * @return void
   */
  protected function retrieveObject($offset)
  {
    $cForRetrieve = clone $this->getQuery();
    $cForRetrieve->offset($offset - 1);
    $cForRetrieve->limit(1);

    $results = $cForRetrieve->execute();

    return $results[0];
  }

  /**
   * getResults
   *
   * @param string $fetchtype 
   * @return void
   */
  public function getResults($fetchtype = null)
  {
    $p = $this->getQuery();

    if ($fetchtype == 'array')
    {
      return $p->execute(array(), Doctrine::FETCH_ARRAY);
    }

    return $p->execute();
  }
}
