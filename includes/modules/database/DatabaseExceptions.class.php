<?php 
/** 
 * @category	Modules
 * @package 	Database
 * @subpackage 	Exceptions
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 19, 2010 8:36:49 AM
 * @copyright 	Vortal Group
 *
 */


class DatabaseInvalidInputException extends InvalidArgumentException {}
class DatabaseInvalidQuery extends BadFunctionCallException {}
class DatabaseInvalidSetup extends  InvalidArgumentException {}
?>