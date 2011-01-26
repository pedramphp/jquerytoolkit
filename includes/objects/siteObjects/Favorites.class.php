<?php 

	class Favorites extends SiteObject {
		
		public function __construct(){
			parent::__construct();
		}
		
		
		public function process(){
			
			$fav = array();
			$fav['twitter'] = array( 'title'=>'follow us on twitter', 
											 'url'=>'http://twitter.com/css3designing');

			$fav['facebook'] = array( 'title'=>'Be our facebook fan', 
											  'url'=>'http://www.facebook.com/pages/CSS3-Designer/156002434435899' );
			$this->results = $fav;
			
		}
		
	}


?>