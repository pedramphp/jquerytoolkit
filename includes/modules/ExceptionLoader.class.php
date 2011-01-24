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
class ExceptionLoader {
		
	public static function loader( $className ){
		
		$path = null;
		$types = array("abstract","interface","class");
		
		foreach( $types as $type ){
		  $myPath = LiteFrame::GetFileSystemPath().'includes/modules/exception/'.$className.'.'.$type.'.php';
		  if( file_exists( $myPath ) ){ $path = $myPath; break; }
		}
		
		if( !empty($path) ){
		  require_once ( $path );
		}
	}/* </ Loader >*/	
	
}

spl_autoload_register( array( 'ExceptionLoader', 'loader' ) ); // As of PHP 5.3.0

?>