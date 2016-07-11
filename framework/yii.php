<?php
/**
 * Yii bootstrap file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id$
 * @package system
 * @since 1.0
 */

require(dirname(__FILE__).'/YiiBase.php');

/**
 * Yii is a helper class serving common framework functionalities.
 *
 * It encapsulates {@link YiiBase} which provides the actual implementation.
 * By writing your own Yii class, you can customize some functionalities of YiiBase.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system
 * @since 1.0
 */
class Yii extends YiiBase
{
}
function PTrack(){
	$args = func_get_args();
	$backtrace = debug_backtrace();
	$file = $backtrace[0]['file'];
	$line = $backtrace[0]['line'];
	echo "<pre>";
	echo "File: $file : $line<br/>";
	foreach ($args as $arg)
	{
		// print_r($arg);
		var_dump($arg);
		// $debug=var_export($arg,true);
	}
	echo "</pre>";
}