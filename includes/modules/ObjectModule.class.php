<?php


/**
 *
 * Description
 *
 *
 * @name			  PACKAGE NAME
 * @see				  PACKAGE REFFER URL
 * @package			PACKAGE
 * @subpackage	SUBPACKAGE
 * @author			Mahdi Pedramrazi
 * @category		backend
 * @access			Mexo Programming Team
 * @version			1.0
 * @since			  Dec 21, 2010
 * @copyright		Mexo LLC
 *
 * @example
 * <code>
 * <?php
 *
 * ?>
 * </code>
 *
 */
 
class ObjectModule {
	
	public static function GetArguments($args){
		
		if(count($args) == 1 && is_array($args[0])) {  $args = $args[0]; } 
		return $args;
	
	}
	
}

?>