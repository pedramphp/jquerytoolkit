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


/*
 * Auto Loader ( Loads Site Objects , framework Modules ,site Modules ,core Objects Automatically when the system needs it)
 */

require_once(LiteFrame::GetFileSystemPath()."includes/GeneralModuleLoader.class.php");

/**
 * Configuration values
 */
require_once(LiteFrame::GetFileSystemPath()."configs/Configurations.php");

require_once(LiteFrame::GetFileSystemPath()."includes/SiteSettings.class.php");

require_once(LiteFrame::GetFileSystemPath()."includes/modules/debugging/Debug.class.php");

//Adding our Database Connection
require_once(LiteFrame::GetFileSystemPath()."includes/modules/database/DatabaseStatic.class.php");

class SiteHelper {
	
	static  $staticObjects = array('SocialLinks','MainNav','Tweets','Title');
	static  $tools;
	public  static $siteObjectsData = null;
	private $ajaxRequest = false;	
	static public  $debugger;
	
	public function __construct(){
	
		$this->siteSettings();
		SiteHelper::Debug('Site Object Loading Complete'); 
		SiteHelper::Debug("Session Data", $_SESSION); 
		SiteHelper::Debug("Cookie Data", $_COOKIE); 
		
	}	  /* </ __construct >  */
	
	public function siteSettings(){
	
		$siteSettings = new SiteSettings();
		$siteSettings->setTemplate();
		$siteSettings->setTemplateColor();
		$siteSettings->setCoreJavascript();
		
	}	/*  </ SiteSettings >  */
	
	public function setObjectsForTemplate(){
		
		LiteFrame::$yAction['SiteData'] =  self::$siteObjectsData;
		
		SiteHelper::Debug( LiteFrame::$yAction['SiteData'] );	
	} /* </ SetObjectsForTemplate >  */	
	
	public function setDebugger(){
		
		// the first parameter is if you want debugging on.
		// the second parameter is if you have firePHP installed on your browser.
		self::$debugger = Debug::getInstance(false,false);
		
	}  /* </ SetDebugger >  */
	
	
	public static function Debug($message,$variable = null , $printArray = false, $seperator = true){
		$seperate = "--------------------------------";
		if($printArray === true){
			
			echo ("<pre>".print_r($message,true)."</pre>");
			echo ("<pre>".print_r($variable,true)."</pre>");
			if( $seperator == true){
			//	echo ("<pre>".print_r($seperate,true)."</pre>");
			}
			return;
		}
		self::$debugger->debug($message, $variable);
		if( $seperator == true){ self::$debugger->debug($seperate); }
	}  /* </ Debug >  */
	
	
	public static function GetAction(){
		
		return LiteFrame::$yAction['_LITE_']['ACTION'];
	
	}/* </ GetAction >  */
	
} /* </ SiteHelper >  */	

SiteHelper::setDebugger();

?>