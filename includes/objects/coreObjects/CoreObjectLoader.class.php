<?php


/**
 *
 * Description
 *
 *
 * @name			PACKAGE NAME
 * @see				PACKAGE REFFER URL
 * @package			PACKAGE
 * @subpackage		SUBPACKAGE
 * @author			Mahdi Pedramrazi
 * @author			Scott Haselton
 * @category		backend
 * @access			Vortal Group Programming Team
 * @version			1.0
 * @since			May 28, 2010 - 2:53:08 PM
 * @example
 * <code>
 * <?php
 *
 *
 *
 * ?>
 * </code>
 *
 */


class CoreObjectLoader {

    public static function Loader( $className ) {

        $path = LiteFrame::GetFileSystemPath().'includes/objects/coreObjects/'.$className.'.class.php';

        if( file_exists( $path ) ) {
            require_once ( $path );
            return true;
        }

        return false;

    }/* </ Loader >*/

}


spl_autoload_register( array( 'CoreObjectLoader', 'Loader' ) ); // As of PHP 5.3.0


?>