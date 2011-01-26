<?php 

	class SocialLinks extends SiteObject {
		
		public function __construct(){
			parent::__construct();
		}
		
		
		public function process(){
			
			$socialLinks = array();
			$socialLinks['twitter'] = array( 'title'=>'follow us on twitter', 
											 'url'=>'http://twitter.com/css3designing');

			$socialLinks['facebook'] = array( 'title'=>'Be our facebook fan', 
											  'url'=>'http://www.facebook.com/pages/CSS3-Designer/156002434435899' );
			$this->results = $socialLinks;
			
		}
		
	}


?>