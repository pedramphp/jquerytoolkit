<?php 

	class SecondaryNav extends SiteObject {
		
		public function __construct(){
			parent::__construct();
		}
		
		
		public function process(){
			
			$links = array();
			$links[0] = array('action' => 'download',  'value' => 'Download' );
			$links[1] = array('action' => 'demo_documentation',     'value' => 'Demos and Documentation');
			$links[2] = array('action' => 'tutorials', 'value' => 'Tutorials' );
			$links[3] = array('action' => 'development',     'value' => 'Development' );
			$links[4] = array('action' => 'contact',   'value' => 'Support' );
						
			$secondaryNav = array();
			foreach( $links as $link ){
				$action = ( $link['action'] == "homepage" ) ? "" : $link['action'];
				$secondaryNav[$link['action']] = array('url' => LiteFrame::GetApplicationPath().$action,
								  'title' => ucfirst(str_replace("_"," and ",$link['action'])),
								  'active' => SiteHelper::GetAction() == $link['action'],
								  'value' => $link['value']
				
				);
			}
			$this->results = $secondaryNav;
			
		}
		
	}


?>