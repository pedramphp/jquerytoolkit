<?php 

	class Title extends SiteObject {
		
		public function __construct(){
			parent::__construct();
		}
		
		
		public function process(){
			
			$title = "";
			switch(SiteHelper::GetAction()){
				case "homepage":
					$title = "jQuery Toolkit .com - Start Using Our jQuery Modules, Plugins, Widgets";
					break;				
				case "contact":
					$title = "Contact us - Contact jQuery toolkit team";
					break;		
			}
			$this->results = $title;
			
		}
		
	}


?>