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
 * @author 			Scott Haselton <shaselton@gmail.com>
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

 
class SiteModuleLoader {
		
	public static function Loader( $className ){
		
		$path = LiteFrame::GetFileSystemPath().'includes/modules/'.$className.'.class.php';
		
		if( !file_exists( $path ) ){ return false; }
			
		require_once ( $path );
		
	}/* </ Loader >*/	
	
}


spl_autoload_register( array( 'SiteModuleLoader', 'Loader' ) ); // As of PHP 5.3.0

?>