<?php

abstract class SiteObject {
	
	/**
	 * Custom error messages can be set here, indexed by the error tag
	 * @var array
	 */
	protected $errorMessages = array() ;
	
	/**
	 * Object results are stored here
	 * @var unknown_type
	 */
	protected $results = array();
	
	public function __construct() {
    try {
			
    	$this->process();
			
		} catch (UserFriendyException $e) {
	  	
			$this->results['error'] = ExceptionRules::GetMessage( $e, $this->errorMessages );
			SiteHelper::Debug( "SiteObject Exception: " , $this->results); 
		
		}
	}
	
	public function getResults() { return $this->results; }

	abstract protected function process();
	
}

?>