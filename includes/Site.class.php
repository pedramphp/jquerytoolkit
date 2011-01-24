<?php 


/**
* @name         Site Class 
*
* @uses          Site
* @Package       Site
* @subpackage    Site
* @author        Mahdi Pedram
* @author		 Scott Haselton
* @category      Backend
* @access        Mexo Programming Team
* @version       1.0
* @since         2010 - 12 - 21  2:36 AM ( Tuesday )
* @copyright     Mexo LLC
*
*
*
*
* Rules : 1 - Space between Each function					  :   4 Enter Breaks
*         2 - Space between First Line of From Starting Brace :   1 Enter Break
* 		    3 - Space between First Line of From Ending Brace   :   1 Enter Break
* 		    4 - Space is  :  TAB
*
*
*/


session_start(); // start up your PHP session! 

require_once(LiteFrame::GetFileSystemPath()."includes/SiteHelper.class.php");

class Site extends SiteHelper{
	
	public function __construct(){
			
		try{
			
			$this->process( func_get_args() );
			
		}catch ( SystemException $e ){			
			SiteHelper::Debug($e->__toString());
			LiteFrame::WriteLog( $e->__toString(), $e->getMessage() );
			exit();
			
		}catch( Exception $e){
			SiteHelper::Debug($e->__toString());
			LiteFrame::WriteLog( $e->__toString(), $e->getMessage() );
			exit();
			
		}
		
	} /* </ __construct >  */
  
	
	private function process( $args ){

			parent::__construct();
			$requiredObjects = ObjectModule::GetArguments( $args );
			
			if(!LiteFrame::IsAjax()) {  $this->loadObjects( self::$staticObjects ); }
			
			if( !empty( $requiredObjects ) ){
			
				$this->loadObjects( $requiredObjects );
			
			}		
			
     		$this->setObjectsForTemplate(); 
		
	}/* </ process > */
	
	
	private function loadObjects( $requiredObjects ){
   
      foreach($requiredObjects as $obj){ 
      	
      	$this->generateObject( $obj, lcfirst($obj) );  
      
      } 
		  
  } /* </ loadObjects >  */	
	
  
  private function generateObject($className, $field) {
		
		$this->siteObjects[$field] = new $className();
		
		self::$siteObjectsData[$field]   = $this->siteObjects[$field]->getResults();
		
	}/* </ generateObject >  */	
	
	
	
}  /* </Site> */


?>