<?php 

// Use FirePHP for debugging? http://www.firephp.org/HQ/Learn.htm
// Added 18 March, 2010
// Most debug calls do not yet utilize the power of FirePHP
// I'm experimenting to see if it's worth the trouble...

if(!defined('FIRE_PHP_FILE')) {
	$firePHPfile = false;
	if(file_exists(LiteFrame::GetFileSystemPath().'includes/modules/debugging/FirePHPCore_0.3.1/FirePHP.class.php')) {
		require_once(LiteFrame::GetFileSystemPath().'includes/modules/debugging/FirePHPCore_0.3.1/FirePHP.class.php');
		$firePHPfile = true;
	}
	define("FIRE_PHP_FILE", $firePHPfile);
}



 /**
 * Class defining all methods for interacting with FirePHP.
 * 
 * Provides generic, signature methods that are implemented for each specific conent.
 * 
 * @author     Scott Haselton
 * @category   Debugging
 * @since      18 March, 2010
 *
 */
class Debug{
	
	protected static $_instance;
	
	# we don't permit an explicit call of the constructor! (like $v = new Singleton())
    private function __construct() { }
 	
    # we don't permit cloning the singleton (like $x = clone $v)
    private function __clone() { }
	
	private function init($isOn, $hasFirePHP = false){
		
		define("DEBUG", (bool)$isOn);
		if ($hasFirePHP && FIRE_PHP_FILE){
			define("FIRE_PHP", true);
		}else{
			define("FIRE_PHP", false);
		}
		
	} 
	
    /**
	 * Get the instance of our Debug Class
	 * 
	 * @param $isOn, if you want debugging on or not.  pass in a boolean
	 * @param $hasFirePHP, this is to make sure that the user has firephp install on their browser. 
	 */
	public static function getInstance($isOn, $hasFirePHP = false) 
    {
	    if( self::$_instance === NULL ) {
		    self::$_instance = new self();
		    self::init($isOn, $hasFirePHP);
	    }
	    return self::$_instance;
    }
	
	
	/**
	 * function tbat will be logging all the debugging messages and variables.
	 * 
	 * @param (string) $msg, logs the message, depends if firephp is on on how the message is handled
	 * @param (mixed) $var, for firephp class
	 */
	public function debug($msg, $var = null)
	{
		if(DEBUG) {
			
			if(FIRE_PHP) {
				$firephp = FirePHP::getInstance(true);
				if($var !== null) {
					$firephp->log($var, $msg);
				}
				else {
					$firephp->log($msg);
				}
			}
			else {
				if($var !== null) {
					echo "<p>[debug] $msg<pre>".var_export($var, true)."</pre></p>\n";
				}
				else {
					echo "<p>[debug] $msg</p>\n";
				}
			}
			
		}
			
	}
	
}



?>