<?php 


class Redirect {
	
	public static function Action( $action = 'homepage' ){
		
		$url = LiteFrame::BuildActionUrl( $action );
		self::Url( $url );
		
	}/* </ Action > */	

	
	
	public static function Url( $url ){
		
		header( 'Location: ' . $url );
		
	}/* </ Url > */	
	
}/* </ Redirect > */	

?>