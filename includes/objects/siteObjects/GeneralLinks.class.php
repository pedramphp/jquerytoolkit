<?php 

	class GeneralLinks extends SiteObject {
		
		public function __construct(){
			parent::__construct();
		}
		
		
		public function process(){
			
			$links = array();
			$links['home'] = LiteFrame::GetApplicationPath();
			$this->results = $links;
			
		}
		
	}


?>