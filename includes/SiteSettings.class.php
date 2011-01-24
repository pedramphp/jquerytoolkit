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


class SiteSettings {
	
	public static  $tools;
	public function __construct(){}
	
	public function setTemplate(){
		
		LiteFrame::SetTemplateFolderName("default");
		LiteFrame::SetTemplateColorName("dark");
		self::$tools= new Tools();
		
	}/* </ SetTemplate > */
	
	public function setTemplateColor(){
		
		//LiteFrame::IncludeStyle('reset.css','rules.css','default.css','footer.css');
		
	} /* </ SetTemplateColor > */
	
	public function setCoreJavascript(){
		/*
		LiteFrame::IncludeLibraryJavascript('jquery/jquery-1.4.4.min.js');
		LiteFrame::IncludeLibraryJavascript('plugins/jquery.gotop.js');
		LiteFrame::IncludeJavascript('default.js');
		if( SiteHelper::GetAction() === 'tools' ){
			LiteFrame::ExternalJavascript('http://connect.facebook.net/en_US/all.js#appId=133202353404448&&amp;xfbml=1');
		}else{
			LiteFrame::ExternalJavascript('http://connect.facebook.net/en_US/all.js#xfbml=1');
		}
		*/
		
	} /* </ SetCoreJavascript > */
	
} /* </ SiteSettings > */
	
?>