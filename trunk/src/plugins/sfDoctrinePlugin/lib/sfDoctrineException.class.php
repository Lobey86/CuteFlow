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
 * @version    SVN: $Id: sfDoctrineException.class.php 5639 2007-10-23 14:27:18Z Eric.Fredj $
 */
class sfDoctrineException extends sfException
{
  /**
   * Class constructor.
   *
   * @param string The error message.
   * @param int    The error code.
   */
  public function __construct ($message = null, $code = 0)
  {
    $this->setName('sfDoctrineException');
    parent::__construct($message, $code);
  }
}
