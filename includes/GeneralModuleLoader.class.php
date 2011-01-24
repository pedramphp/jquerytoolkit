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
 * @author			Scott Haselton
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



/**
 *
 *	Auto Site Object Loader :
 *	Auto Loader ( Loads site objects that have not been loaded when they are called )
 *
 */
require_once(LiteFrame::GetFileSystemPath()."includes/objects/siteObjects/SiteObjectLoader.class.php");





/**
 *
 *	Core Module Loader :
 *	Auto Loader ( Loads site modules that have not been loaded when they are called )
 *
 *	Users
 *
 */
require_once(LiteFrame::GetFileSystemPath()."includes/objects/coreObjects/CoreObjectLoader.class.php");




/**
 *
 *	Framework Module Loader :
 *	Auto Loader ( Loads site modules that have not been loaded when they are called )
 *
 *	Redirect
 *
 */
require_once(LiteFrame::GetFileSystemPath()."frameworkModules/FrameworkModuleLoader.class.php");



/**
 * 
 *	Site Module Loader : 
 *	Auto Loader ( Loads site modules that have not been loaded when they are called )
 *  
 */
require_once(LiteFrame::GetFileSystemPath()."includes/modules/SiteModuleLoader.class.php");



/**
 * 
 *	Site Module Loader : 
 *	Auto Loader ( Loads exception modules that have not been loaded when they are called )
 *  
 */
require_once(LiteFrame::GetFileSystemPath()."includes/modules/ExceptionLoader.class.php");


?>