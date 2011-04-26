<?php 

	class PrimaryNav extends SiteObject {
		
		public function __construct(){
			parent::__construct();
		}
		
		
		public function process(){
			
			$links = array();
			$links[0] = array(
				'url' => 'http://jquery.com',  
				'value' => 'jQuery', 
				'hasClass' => false, 
				'title' => '',
				'active' => false  );
			
			$links[1] = array(
					'url' => '#',     
					'value' => 'Plugins' , 
					'hasClass' => false,
					'title' => '',
					'active' => false );
			
			$links[2] = array(
					'url' => '#', 
					'value' => 'TK' , 
					'hasClass' => true , 
					'title' => '',
					'active' => true );
			
			$links[3] = array(
					'url' => '#',     
					'value' => 'Blog', 
					'hasClass' => false,
					'title' => '',
					'active' => false );
			
			$links[4] = array(
					'url' => LiteFrame::GetApplicationPath().'about',   
					'value' => 'About',
					'hasClass' => false, 
					'title' => '',
					'active' => false  );
			
			$links[5] = array(
					'url' => '#',  
					'value' => 'Donate', 
					'hasClass' => false, 
					'title' => '',
					'active' => false  );
			
			$this->results = $links;
			
		}
		
	}


?>