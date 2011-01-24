<?php

/**
 * DatabaseStatic is a static wrapper class for the DatabaseManager as well as ExtendedRecordLoader.
 *
 * It provides access to the functionality of both through a single entry-point.
 *
 * To access DatabaseManager functions, call it directly through DatabaseStatic with a static
 * context.
 *
 * <code><?php
 * $result = DatabaseStatic::Query('Select Now()');
 * $row = DatabaseStatic::FetchAssoc($result);
 * ?> </code>
 *
 * Inside the DatabaseStatic class also includes an ExtendedRecordLoader for each database in the system.
 * To access a loader: (assume there is a database named "MyDatabase" inside the system, and inside of that
 * contains a table called "MyTable"
 *
 *
 * <code><?php
 * $records = DatabaseStatic::$MyDatabase->Load_MyTable();
 * ?></code>
 *
 * @category	Modules
 * @package 	Database
 * @subpackage 	Core
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 19, 2010 8:36:49 AM
 * @copyright 	Vortal Group
 *
 * 
 * @internal This class is intended to use __callStatic and __getStatic to work correctly. To make it fully
 * 				backwards compatible with php, the class is sometimes dynamically generated.
 */


require_once('ExtendedRecord.class.php');
require_once('SQLBuilder.class.php');

/**
 * PHP version when __getStatic is implemented
 * @var string
 */
define("PHP_GETSTATIC_SUPPORT", '10.0.0');

/**
 * PHP version when __callStatic is implemented
 * @var string
 */
define("PHP_CALLSTATIC_SUPPORT", '5.3.0');



/**
 * <------------------------------------------------------
 * PHP VERSION THAT SUPPORTS __getStatic and __callStatic
 * ------------------------------------------------------>
 */
if (version_compare(PHP_VERSION, PHP_GETSTATIC_SUPPORT, '>=')) {


	/**
	 * DatabaseStatic is a wrapper for both DatabaseManager as well as ExtendedRecordLoader
	 *
	 * A user can use it to call all the functions defined in DatabaseInterface, as well
	 * as statically access ExtendedRecordLoaders for each database that exists in the system.
	 *
	 * @package 	Database
	 * @subpackage 	Core
	 * @category	backend
	 * @author 		Juan Caldera
	 * @version 	1.0
	 * @since 		May 4, 2010 1:44:02 PM
	 * @copyright 	Vortal Group
	 *
	 */
	class DatabaseStatic {

		/**
		 * An array of ExtendedDatabaseLoaders
		 * @var array
		 */
		static private $loaders;

		/**
		 * Instance of DatabaseInterface
		 * @var DatabaseInterface
		 */
		static private $db;



		/**
		 * Redirects all static function calls to the DtabaseInterface object
		 * @return
		 */
		static public function __callStatic($fname, $params) {
			return call_user_func_array(array(self::$db, $fname), $params);
		}



		/**
		 * Redirects all static member accesses to the contents of DatabaseStatic::$loaders
		 *
		 * @return ExtendedDatabaseRecord
		 */
		static public function __getStatic($database) {
			return (empty(self::$loaders[$database])) ? false : self::$loaders[$database];
		}
		
		
		
		static public function Select() { return new SelectBuilder(); }
		
		
		/**
		 * Redirect the SetDebug function to DatabaseManager
		 *
		 */
		static public function SetDebug($mode) { DatabaseManager::SetDebug(); }



		/**
		 * Initialize the entire package
		 */
		static public function Initialize($param = DATABASE_DEFAULT_CONFIG) {

			
			
			if ( $param instanceof DatabaseInterface ) { self::$db = $param; } 
			else { 
				$reflectionObj = (new ReflectionClass('DatabaseManager'));
				self::$db = $reflectionObj->newInstanceArgs(func_get_args());
			}

			self::$db->SetErrorHandler(new DatabaseStaticErrorHandler());
			
			$results = self::$db->FetchAllRowsAssoc('SHOW DATABASES');
			foreach($results as $result) {
				self::$loaders[ $result['Database'] ] = new ExtendedRecordLoader( $result['Database'], self::$db);
			}
		}
	}
} elseif (version_compare(PHP_VERSION, PHP_CALLSTATIC_SUPPORT, '>=')) { new DatabaseStaticBuilder(true);
} else { new DatabaseStaticBuilder(false); }




/** 
 * Error Handler for DatabaseStatic
 * 
 * @package 	Database
 * @subpackage 	ErrorHandling
 * @category	backend
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 14, 2010 9:20:21 AM
 * @copyright 	Vortal Group
 *
 */
class DatabaseStaticErrorHandler implements DatabaseErrorHandler {
	
	/**
	 * DatabaseManager implements DatabaseErrorHandler and is by default it's own ErrorHandler.
	 * 
	 * This function kills the script, and outputs an error.
	 * 
	 * @param $message
	 * @param $errorNo
	 * @param $e
	 */
	public function HandleError($message, $errorNo, Exception $e) {
		
		$trace = $e->GetTrace();
		$appTrace = $trace[0];	// default app trace
		
		/**
		 * @internal 	the goal is to find an element of $trace, whose file doesn't originate from
		 * 				this file or DatabaseManager.
		 */ 
		$found = false; // this flag will be set to true once a trace originating from this file is found
		for($i=0; $i < count($trace)-1; $i++) { 
			if ($found === false && isset($trace[$i]['file']) && strpos($trace[$i]['file'], __FILE__) !== false) { $found = true; }
			elseif ( $found === true && isset($trace[$i]['file']) && strpos($trace[$i]['file'], __FILE__) === false) { $appTrace = $trace[$i]; }  
		}
		//$connection = $this->GetPreferredConnection($this->lastConnectionType);
		
		// clean the output
		@ob_end_clean();
		
		print( sprintf(					"<pre>\n" .
										"****************************************************************************************************************************\n" .
										"Failed to execute your Query\n" .
										"Mysql Error: 		%s\n" .
										"Mysql Error Number:	%s\n" .
										"File:			%s\n" .
										"Line:			%s\n" .
										"Query:" .
										"%s\n" .
										"****************************************************************************************************************************\n" .
										"</pre>", 
										wordwrap($message, 100, "\n			"), 
										mysql_errno(), 
										$appTrace['file'], 
										$appTrace['line'], 
										$e->GetMessage()));

		exit();
		
	}
	
}
























/** 
 * For lesser versions of PHP, this manually defines StaticDatabase dynamically.
 * 
 * The goal of this object is to dynamically define DatabaseStatic such that it implements
 * all features of DatabaseStatic for PHP versions that do not support magic calls
 * needed by the default definition.
 * 
 * 
 * @package 	DatabaseStatic
 * @subpackage 	Database
 * @category	backend
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 4, 2010 2:26:11 PM
 * @copyright 	Vortal Group
 *
 */
class DatabaseStaticBuilder {

	private $db;
	private $class 			= "";
	private $members 		= "";
	private $initialize 	= "";
	private $functions 		= "";

	private $indent = "\t";
	private $indent2 = "\t\t";


	
	public function __construct($implementCallStatic) {
		
		$this->db = new DatabaseManager();

		$this->ClassBluePrint();
		$this->BuildMembers();
		$this->BuildFunctions($implementCallStatic);

		$class = sprintf($this->class, $this->members, $this->functions, $this->initialize);
		
		eval($class);
	}


	private function ClassBluePrint() {

		$this->class = '
		
class DatabaseStatic {
			
	static private $db;
		
%s
%s



	static private function InitializeLoaders() {

%s
	}
	
	
	static public function Select() { return SQLBuilder::Select(); }
	
	
	
	static public function SetDebug($mode) { DatabaseManager::SetDebug($mode); }
	
	
	
	static public function Initialize($param = DATABASE_DEFAULT_CONFIG) {

		if ( $param instanceof DatabaseInterface ) { self::$db = $param; } 
		else { 
				$reflectionObj = (new ReflectionClass("DatabaseManager"));
				self::$db = self::$db = $reflectionObj->newInstanceArgs(func_get_args());
				
			}
		self::$db->SetErrorHandler(new DatabaseStaticErrorHandler());
		self::InitializeLoaders();
	}
		
}
		
		';

	}



	private function BuildMembers() {

		$results = $this->db->FetchAllRowsAssoc('SHOW DATABASES');

		$members = array();
		$initialize = array();

		foreach($results as $result) {

			$members[] = sprintf("%sstatic public \$%s;", $this->indent, $result['Database']);
			$initialize[] = sprintf("%sself::\$%s = new ExtendedRecordLoader('%s', self::\$db);",
			$this->indent2, $result['Database'], $result['Database']);

		}

		$this->members = implode("\n", $members);
		$this->initialize = implode("\n", $initialize);
	}



	private function BuildFunctions($implementCallStatic) {

		if ($implementCallStatic) { $this->BuildFunctionsWithCallStaticSupport(); }
		else { $this->BuildFunctionsWithoutCallStaticSupport(); }

	}



	private function BuildFunctionsWithCallStaticSupport() {

		$this->functions = '
		
		
		
	static public function __callStatic($fname, $params) {
		if (empty(self::$db)) { exit("You need to run DatabaseStatic::Initialize()"); }
		return call_user_func_array(array(self::$db, $fname), $params);
	}';

	}


	private function BuildFunctionsWithoutCallStaticSupport() {

		$methods = get_class_methods('DatabaseInterface');
		$methodList = array();
		foreach($methods as $method) {
			$methodList[] = sprintf('
			
			
			
	static public function %s() { 
		
		if (empty(self::$db)) { exit("You need to run DatabaseStatic::Initialize()"); }
	
		$args = func_get_args(); 
		return call_user_func_array(array(self::$db, "%s"), $args); 
	}', $method, $method);

		}

		$this->functions = implode("\n\n		", $methodList);

	}
}









if (!defined('DATABASESTATIC_NOAUTOLOAD')) { DatabaseStatic::Initialize(); }


?>