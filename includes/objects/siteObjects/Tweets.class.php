<?php 
/*
 *  Source http://net.tutsplus.com/tutorials/php/creating-a-twitter-oauth-application/
	
	API key Ab95HUFoLWIJvQG9ejq6TA
	Registered Callback URL
	
	http://jquerytoolkit.com
	The @Anywhere callback URL's domain & subdomain must match the location of @Anywhere integrations on your site.
	You can authorize additional domains if you need to integrate with more than one site.
	OAuth 1.0a Settings
	
	OAuth 1.0a integrations require more work.
	Consumer key Ab95HUFoLWIJvQG9ejq6TA
	
	Consumer secret c3h4PkTTsZuJyfNYa1s4TKP5HT5r0gyI6V4SKnXWh3o
	
	Request token URL https://api.twitter.com/oauth/request_token
	
	Access token URL https://api.twitter.com/oauth/access_token
	
	Authorize URL https://api.twitter.com/oauth/authorize
	
	We support hmac-sha1 signatures. We do not support the plaintext signature method.
	Registered OAuth Callback URL
	
	http://jquerytoolkit.com
	
 */
	require_once(LiteFrame::GetFileSystemPath()."includes/modules/twitter/tmhOAuth.php");
	require_once(LiteFrame::GetFileSystemPath()."includes/modules/twitter/TwitterApp.php");
	require_once(LiteFrame::GetFileSystemPath()."includes/modules/twitter/TwitterAvatars.php");

	// set the consumer key and secret
	define('CONSUMER_KEY',      'Ab95HUFoLWIJvQG9ejq6TA');
	define('CONSUMER_SECRET',   'c3h4PkTTsZuJyfNYa1s4TKP5HT5r0gyI6V4SKnXWh3o');	
	
	// I got all these four variables by doing $twitterApp->auth the first time.
	define('AUTH_TOKEN','X9brbnJITSi1WAq0usEIBH03lYFSrPT1oB3ujp0ExM');
	define('AUTH_SECRET','73p2xYXl59Gj5mm6Echh7szuVb6dJm0P7jYzqULPhbU');
	define('ACCESS_TOKEN','209298515-Tze0TEePpVXIBOeuhRYk6b8fYc2aFGXg6si1erNC');
	define('ACCESS_TOKEN_SECRET','2God3yx9HfVXTHIVyJVIaAwNjq94pOeqtFStWgFzT0');
	
	class Tweets extends SiteObject {
		
		public function __construct(){
			parent::__construct();
		}
		
		
		public function process(){
			
			$tweet = array('no tweet found');
		    $config = array(  
		         'consumer_key'      => CONSUMER_KEY,  
		        'consumer_secret'   => CONSUMER_SECRET  
		    );  
			if(!session_id()) {	session_start();	}
			
			// Sessions should be off for the first time running 
			$_SESSION['authtoken'] = AUTH_TOKEN;
			$_SESSION['authsecret'] = AUTH_SECRET;
			$_SESSION['authstate'] = 2;
			$_SESSION['access_token'] = ACCESS_TOKEN;
			$_SESSION['access_token_secret'] = ACCESS_TOKEN_SECRET;
			
			//setcookie("access_token", ACCESS_TOKEN, time()+3600);
			//setcookie("access_token_secret", ACCESS_TOKEN_SECRET, time()+3600);	    
			//print_r($_COOKIE);
			
			$twitterApp = new TwitterApp(new tmhOAuth($config));
			
			
			// you need to call $twitterApp->auth to authorize it first with your browser
			
			if($twitterApp->isAuthed()){
				$tweet = array();
				foreach( $twitterApp->retrieveTweets() as $row){
					$tweet[] = $row->text;
				}
			}else{
			//	$twitterApp->auth();
			}
			
	
			
			$this->results = array_slice($tweet, 0, 20);	
			
		}
	}


?>