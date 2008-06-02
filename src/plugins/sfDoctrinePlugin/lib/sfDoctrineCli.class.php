<?php
/*
 * This file is part of the sfDoctrinePlugin package.
 * (c) 2006-2007 Jonathan H. Wage <jwage@mac.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDoctrineCli
 *
 * @package    sfDoctrinePlugin
 * @author     Jonathan H. Wage <jwage@mac.com>
 * @version    SVN: $Id: sfDoctrineCli.class.php 5922 2007-11-08 21:51:02Z Jonathan.Wage $
 */
class sfDoctrineCli extends Doctrine_Cli
{
  /**
   * notify
   *
   * @param string $notification 
   * @param string $style 
   * @return void
   */
  public function notify($notification = null, $style = 'HEADER')
  {
    pake_echo_action('doctrine', $notification);
  }
  
  /**
   * notifyException
   *
   * @param string $exception 
   * @return void
   */
  public function notifyException($exception)
  {
    throw new sfException($exception->getMessage() . "\n\n" . $exception->getTraceAsString());
  }
}
