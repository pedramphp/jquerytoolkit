<?php

/**
 * @category	Modules
 * @package 	Database
 * @subpackage 	Core
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 19, 2010 8:36:49 AM
 * @copyright 	Vortal Group
 *
 */

/**
 * Database Interface
 */
require_once('Database.interface.php');

/**
 * Database Exceptions
 */
require_once('DatabaseExceptions.class.php');


/**
 * Database Flag: Master
 * 
 * @var string
 */
define('DATABASE_TYPE_MASTER', 'master');

/**
 * Database Flag: Slave
 * 
 * @var string
 */
define('DATABASE_TYPE_SLAVE', 'slave');

/**
 * Default Configuration File.
 * 
 * @var string
 */
define('DATABASE_DEFAULT_CONFIG', realpath(pathinfo(__FILE__, PATHINFO_DIRNAME).'/DatabaseConfig.class.php'));

/**
 * Debugging Flag: None
 *
 * @var integer
 */
define('DATABASE_DEBUG_NONE', 0);

/**
 * Debugging Flag: Print
 *
 * Prints all queries executed
 *
 * @var integer
 */
define('DATABASE_DEBUG_PRINT', 1);

/**
 * Debugging Flag: No Write
 *
 * This flag will print/execute all your queries up to the point that it tries to
 * execute a "WRITE" type query (INSERT/UPDATE/DELETE etc). 
 * 
 * Once it reaches that point, the script would act as if it found an error on your query.
 *
 * @var integer
 */
define('DATABASE_DEBUG_NOWRITE', 2);	// do not execute "write" type queries. (insert, delete, update)

/**
 *
 *
 * This package contains an implementation of DatabaseInterface: DatabaseManager
 *
 * DatabaseManager can handle multiple master/slave connections and automatically sends those queries
 * to the appropriate database connections.
 *
 * <code>
 * <?php
 * // create the manager
 * $dbManager = new DatabaseManager();
 *
 * // query
 * $result = $dbManager->Query("SELECT * FROM employee LIMIT 5");
 *
 * // get the results
 * while($row = $dbManager->FetchAssoc($result)) {
 *
 * 		echo sprintf("Employee #%s: %s %s", $row['Id'], $row['FirstName'], $row['LastName']);
 *
 * }
 * ?>
 * </code>
 *
 * @package 	Database
 * @subpackage	Database Manager
 * @category	backend
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		Apr 13, 2010 2:13:52 PM
 * @copyright 	Vortal Group
 *
 */
class DatabaseManager implements DatabaseInterface, DatabaseErrorHandler {


	/**
	 * Debugging Flag
	 *
	 * @access private
	 * @var integer
	 */
	static private $debugLevel = 0;

	/**
	 * Operations that are considered "Read" operations
	 * @access private
	 */
	static private $readOperations = array("select", "describe", "show", "explain", "show");

	/**
	 * Array of all master-type connection info
	 * @access private
	 */
	private $master = array();

	/**
	 * Array of all slave-type connection info
	 * @access private
	 */
	private $slave = array();

	/**
	 * Name of preferred master connection
	 * @access private
	 */
	private $preferredMaster;

	/**
	 * Name of preferred slave connection
	 * @access private
	 */
	private $preferredSlave;


	/**
	 * Database Error Handler Object
	 * @var DatabaseErrorHandler
	 */
	private $dbErrorHandler;


	/**
	 * Creates a new database manager.
	 *
	 * <code>
	 * <?php
	 * // default configuration file
	 * $dbm = new DatabaseManager();
	 *
	 * // custom configuration file
	 * $dbm = new DatabaseManager('/some/custom/config.ini');
	 *
	 * $config = array( array('host'=>localhost,'user'=>'root', 'pass'=>'*****'), array(...) );
	 * $dbm = new DatabaseManager($config);
	 *
	 * $dbm = new DatabaseManager('localhost', 'my_database', 'root', 'pass');
	 * ?>
	 * </code>
	 * @param string $info		Either a file path to an INI file, or an array containing
	 * 							the connection information
	 * @link	 DatabaseManager.readme.txt
	 */
	public function __construct( $info = DATABASE_DEFAULT_CONFIG ) {

		// this switch handles the various overloaded constructor calls
		switch(true) {

			case is_array( $info ): 		break; 		// if this is the case, no processing is neccessary
			case file_exists( $info ): 		$info = $this->prepConnectionInfoFromFile($info); break;
			default: 						$info = $this->prepConnectionInfoFromArgs(func_get_args()); break;

		}


		$this->LoadDatabaseConnectionInfo($info);
		$this->SetPreferredConnections();

		// by default, this object is its own error handler
		$this->SetErrorHandler($this);

	}



	/**
	 * Changes the Error handler for this object
	 *
	 * @param DatabaseErrorHandler $errorHandler
	 */
	public function SetErrorHandler( DatabaseErrorHandler $errorHandler ) {

		$this->dbErrorHandler = $errorHandler;

	} /* </SetErrorHandler> */



	/**
	 * Parse connection infromation from INI
	 * @access private
	 */
	private function prepConnectionInfoFromFile( $file ) {

		switch( strtolower(pathinfo($file, PATHINFO_EXTENSION)) ) {
			case 'php':
				require_once($file);
				return DatabaseConfig::Load();
			default:
				return parse_ini_file($file, true);
		}
	} /* </prepConnectionInfoFromFile> */



	/**
	 * Used by the constructor to parse all the arguments into the initialization array.
	 *
	 * @access private
	 * @param	array 	$args			return value of func_get_args in the constructor
	 * @return 	array
	 */
	private function prepConnectionInfoFromArgs( $args ) {

		/**
		 * This is what $args should look like
		 *
		 * $args 	0 host
		 * 			1 database
		 * 			2 username
		 * 			3 password
		 */

		return array ( array(	'database'	=> isset($args[1]) ? $args[1] : '',
								'user'		=> isset($args[2]) ? $args[2] : '',
								'pass'		=> isset($args[3]) ? $args[3] : '',
								'host'		=> isset($args[0]) ? $args[0] : '') );

	} /* </prepConnectionInfoFromArgs> */



	/**
	 * Select a MySQL database
	 *
	 * @param	string					Name of database to set as default for the connection
	 * @param	string					Type of connection (master or slave)
	 * @param	boolean|string|resource	Either the name of the database connection, an actual
	 * 									database link, or false to use default
	 * @return	boolean
	 *
	 */
	public function SelectDB( $databaseName, $type = false, $link = false ) {

		if ($type === false) {
			return $this->SelectDB($databaseName, DATABASE_TYPE_MASTER, $link) && $this->SelectDB($databaseName, DATABASE_TYPE_SLAVE, $link);
		} else {
			$link = $this->GetConnection($type, $link);
			return mysql_select_db($databaseName, $link);
		}

	} /* </SelectDB> */



	/**
	 * Get the ID generated in the last query
	 *
	 * @return 	integer		Returns the auto-incremented value of the last inserted record
	 *
	 */
	public function LastInsertID( $link = false ) {

		$link = $this->GetConnection( DATABASE_TYPE_MASTER );
		return mysql_insert_id($link);

	} /* </LastInsertID> */




	/**
	 * Get number of rows in result
	 *
	 * @param	resource	$result		The result resource that is being evaluated. This result comes from a call to mysql_query().
	 * @return 	integer					The number of rows in a result set on success or FALSE on failure
	 */
	public function NumberOfRows( $results ) { return mysql_num_rows( $results ); } /* </NumberOfRows> */



	/**
	 * Sends a query to the database
	 * @param string $query
	 * @param boolean $withException	If true, an exception is thrown when an error occurs.
	 *
	 * @return mixed
	 */
	public function Query( $query, $withException = false ) {

		// all SELECT queries must be directed to slave database, if there exists slave databases.
		if ( $this->IsReadQuery($query) )
		{
			return $this->QueryInternal(DATABASE_TYPE_SLAVE, $query, $withException);

		// if no slave databases exist, or if caller is doing any other queries other than SELECT
		// they will be sent to a master database
		} else { return $this->QueryInternal(DATABASE_TYPE_MASTER, $query, $withException); }

	} /* </Query> */



	/**
	 * Performs a query and returns all the results.
	 *
	 * @param	string		$query			Database query
	 * @param 	boolean		$withException	Throw an exception when an error occurs
	 * @param	boolean		$asObject		Returns objects instead of arrays
	 * @param	string		$class			When $asObject = true, this is the class name to instantiate
	 * @param	array		$params			Parameters to be passed to the class's contructor
	 *
	 * @return	Array						Returns either an array of arrays, or an array of objects
	 *
	 */
	public function FetchAllRowsAssoc( $query, $withException = false ) {

		// execute the query
		$results = $this->Query($query, $withException);
		$list = array();
		while($row = $this->FetchAssoc($results)) { $list[] = $row; }

		// done, return the list
		return $list;

	} /* </FetchAllRowAssoc> */



	/**
	 * Performs a query and returns all the results as an array of objects.
	 *
	 * @param	string		$query			Database query
	 * @param	string		$class			When $asObject = true, this is the class name to instantiate
	 * @param	array		$params			Parameters to be passed to the class's contructor
	 * @param 	boolean		$withException	Throw an exception when an error occurs
	 *
	 * @return	Array
	 *
	 */
	public function FetchAllRowsObject( $query, $class = 'stdclass', $params = array(), $withException = false ) {

		$results = $this->Query($query, $withException);
		$list = array();
		while( $row = $this->FetchObject($results, $class, $params) ) { $list[] = $row; }
		return $list;

	} /* </FetchAllRowsObject> */



	/**
	 * Get number of affected rows in previous MySQL operation
	 *
	 * @param string	$link		Database Connection Link
	 *
	 */
	public function AffectedRows( $link = false ) {

		if (!$link) return mysql_affected_rows();
		else return mysql_affected_rows($link);

	} /* </AffectedRows> */



	/**
	 * Fetch a result row as an associative array
	 * @param 	resource	$result		The result resource that is being evaluated. This result
	 * 									comes from a call to Query().
	 * @return 	array					Returns an associative array of strings that corresponds
	 * 									to the fetched row, or FALSE if there are no more rows.
	 */
	public function FetchAssoc( $result ) { return mysql_fetch_assoc($result); } /* </FetchAssoc> */



	/**
	 * Fetch a result row as an object
	 *
	 * @param 	resource	$result
	 * @param	string		$class		Name of the Class to instantiate
	 * @param	array		$params
	 * @return	object
	 */
	public function FetchObject( $result, $class = 'stdClass', $params = array() ) {

		if (!empty($params)) return mysql_fetch_object($result, $class, $params);
		else return mysql_fetch_object($result, $class);

	} /* </FetchObject> */



	/**
	 * Escapes special characters in a string for use in a SQL statement
	 *
	 * @param	string	$string		The string that is to be escaped.
	 * @return Returns the escaped string, or FALSE on error.
	 */
	public function EscapeString( $string ) {

		$link = $this->GetConnection(DATABASE_TYPE_SLAVE);
		return mysql_real_escape_string($string, $link);

	} /* </EscapeString> */



	/**
	 * Returns the last encountered error.
	 *
	 * @return string
	 */
	public function Error() { return mysql_error(); }



	/**
	 * Sets the debugging level of the database manager
	 *
	 * @param string $debugLevel
	 * @see DATABASE_DEBUG_NONE
	 * @see DATABASE_DEBUG_PRINT
	 * @see DATABASE_DEBUG_NOWRITE
	 */
	static public function SetDebug( $debugLevel ) { self::$debugLevel = $debugLevel; }



	/**
	 * Sets the preferred Master and Slave connections
	 *
	 * In a multi-server, master/slave-type setup, you can use this to set the preferred
	 * master and slave connections. if none is specified, a random one will be selected
	 * from all the connections. This function is automatically when the object is
	 * instantiated, but can be set again at any time.
	 *
	 * @param string 	$master 		Name of Default master connection. If false, random one will be selected
	 * @param string	$slave 		Name of Default slave connection. If false, a random one will be selected
	 * @exception Exception		Thrown whenever no connection informations are found.
	 *
	 */
	public function SetPreferredConnections( $master = false, $slave = false ) {

		$this->SetPreferredConnection('master', $master);
		$this->SetPreferredConnection('slave', $slave);

	} /* </SetPreferredConnections> */



	/**
	 * Sets the preferred connection.
	 *
	 * This function is initially called internally by the system to set a preferred
	 * connection for both slave and master type connections.
	 *
	 * If you wish to set a specific connection to be the preffered connection, you
	 * can call this function again with the corresponding information
	 *
	 * The $connection parameter is the connection name you associated with a connection
	 * array during initialization. It's simply the index of the array associated with
	 * the connection.
	 *
	 * @throws DatabaseInvalidSetup Thrown when the connection
	 *
	 * @param string 	$mode			'DATABASE_TYPE_MASTER' or 'DATABASE_TYPE_SLAVE'
	 * @param string	$connection		Connection Name
	 * @see DATABASE_TYPE_MASTER
	 * @see DATABASE_TYPE_SLAVE
	 *
	 */
	public function SetPreferredConnection($mode, $connection) {

		$mode = strtolower($mode); $Mode = ucfirst($mode); // Capital "M" in $Mode is intentional.

		if (empty($this->$mode)) 	throw new DatabaseInvalidSetup("No $Mode connection informations was found.");

		// if a specific connection is desired, make sure they exist.
		if ($connection !== false && !isset($this->$mode[$connection])) { throw new DatabaseInvalidSetup("Preffered $Mode Connection Not Found"); }
		elseif ($connection !== false) { $this->{"preferred".$Mode} = $connection; }
		else { $this->{"preferred".$Mode} = $this->PickRandomConnectionInfo($mode); }

	} /* </SetConnection> */



	/* ------------------------------- PRIVATE FUNCTIONS ---------------------------*/





	/**
	 * Returns a database connection link
	 *
	 * @param 	$type 		string		Specify if the desired connection is either master or slave
	 * @param 	$connectionName 		string		The name of the connection that's desired. If false,
	 * 									then a random one is selected.
	 * @return 				resource	The database connection
	 *
	 */
	private function GetConnection($type, $connectionName=false) {

		// if no specific connection name is specified, we'll use the preferred/default ones.
		if ($connectionName === false) { $connectionName = $this->GetPreferredConnectionName($type); }

		// a caller might also pass on the actual connection link resource, if so, simply return that.
		if (is_resource($connectionName)) return $connectionName;

		// if we make it this far, this means we now have a valid connection name.
		// the goal is to return a database link with that name; either return an existing connection
		// or make a new connection and return that.

		// first, make sure we have connection information available
		if (empty($this->{$type}[$connectionName])) { throw new DatabaseInvalidSetup("Connection information not found for {$connectionName}"); }

		// at this point we have isoloted a specific connection info,
		// that we can connect to the database, if we haven't already
		if (isset($this->{$type}[$connectionName]['dblink'])) { return $this->{$type}[$connectionName]['dblink'];

		// connection does not exists yet, connect now.
		} else {

			$info = $this->{$type}[$connectionName];
			$host = sprintf('%s:%s', $info['host'], $info['port']);
			$link = @mysql_pconnect($info['host'], $info['user'], $info['pass']);

			if (!$link) { throw new DatabaseInvalidSetup("Failed to connect to {$info['host']}"); }

			// save it back into the same array, in case it's called again later
			$this->{$type}[$connectionName]['dblink'] = $link;

			// select the default database for that connection when set
			if ( !empty( $this->{$type}[$connectionName]['database'] )) { $this->SelectDB($this->{$type}[$connectionName]['database'], $type); }
			return $link;

		}

	} /* </GetConnection> */


	/**
	 * Determines if a given query is a select query.
	 *
	 * @access private
	 * @param  $query string
	 * @return boolean
	 */
	private function IsReadQuery($query) {

		$queryFragments = explode(' ', trim($query));

		// if the first fragment is select, then it's a select query.
		return (in_array(strtolower(trim($queryFragments[0])), self::$readOperations));

	} /* </IsReadQuery> */



	/**
	 * Returns the key of a random element of an array
	 * 
	 * @access private
	 * @param 	$source 	array	The source array to get the random key from
	 * @return 	string				A random key of the array
	 */
	private function PickRandomConnectionInfo($source) {

		if ($source == DATABASE_TYPE_MASTER) { $source = $this->master; }
		else { $source = $this->slave; }

		// pick a random slot in the array
		$target = mt_rand(0, count($source) - 1);

		// get all the keys of the array
		$keys = array_keys($source);

		// pick one of those keys as specified by $target, then return that key
		return $keys[$target];

	} /* </PickRandomConnectionInfo> */



	/**
	 * This is the actual query function, that decides that to do with incoming queries.
	 * 
	 * @param	string	$type
	 * @param	string	$query
	 * @param	boolean	$withException 	If set to true, whenever an error occurs, an exception is thrown, otherwise, the error handler is called.
	 * 
	 * @return 	resource
	 * @access private
	 */
	private function QueryInternal($type, $query, $withException) {

		// get the appropriate connection to use for this query
		$link = $this->GetConnection($type);

		// remember this as the last connection
		$this->lastConnectionType = $type;

		// decide if the query should be executed.
		if ( $this->DebugHandler($type, $query) ) { $result = mysql_query($query, $link);
		} else { $result = false; }

		// default exception
		if (empty($result)) {
				
			if ( $withException ) { throw new DatabaseInvalidQuery($this->Error()); }
			else { $this->dbErrorHandler->HandleError($this->Error(), mysql_errno(), new DatabaseInvalidQuery($query)); }
				
		} else { return $result; }

	} /* </QueryInternal> */


	/**
	 * Last connection used. Used only by the error handler
	 *
	 * @var array
	 * @access private
	 */
	private $lastConnectionType;


	/**
	 * Returns to the preferred connection name of specified connection type
	 *
	 * @access private
	 * @param string $type
	 * @return string
	 */
	private function GetPreferredConnectionName($type) {

		if ($type == DATABASE_TYPE_MASTER) { return $this->preferredMaster; }
		else { return $this->preferredSlave; }

	} // end GetPreferredConnectionName



	/**
	 * Loads the INI file, and initializes $slave and $master
	 *
	 * @access private
	 * @param $ini string Path to the configuration ini file
	 */
	private function LoadDatabaseConnectionInfo($info) {
		
		// support 1D array inputs
		$info = $this->Wrap1DArray($info);

		foreach($info as $serverName => $serverInfo)
		{
			$serverInfo = $this->ShapeConnectionArray($serverInfo);
			$this->OrganizeConnections($serverName, $serverInfo);
		}

	} /* </LoadDatabaseConnectionInfo> */



	private function ShapeConnectionArray(array $serverInfo) {
		
		$defaults = array('user'=>'', 'pass'=>'', 'host'=>'localhost', 'port'=>'3306', 'type'=>'');

		// merge the connection data with the defaults
		$serverInfo = array_merge($defaults, $serverInfo);

		// support for host:port inputs
		$host_port = explode(':', $serverInfo['host']);

		if ( count($host_port) > 1 )
		{		
			$serverInfo['port'] = $serverInfo['port'] == '' ? $host_port[1] : $serverInfo['port'];
			$serverInfo['host'] = $host_port[0];		
		}

		return $serverInfo;

	} /* </ShapeConnectionArray> */



	private function OrganizeConnections($serverName, array $serverInfo) {

		// server is a master
		if ($serverInfo['type'] == DATABASE_TYPE_MASTER) { $this->master[$serverName] = $serverInfo; }

		// server is a slave
		elseif ($serverInfo['type'] == DATABASE_TYPE_SLAVE) { $this->slave[$serverName] = $serverInfo; }

		// if type is not set, connection will be considered as both.
		else
		{
			$this->slave[ $serverName ] = $serverInfo;
			$this->master[ $serverName ] = $serverInfo;
		}

	}



	/**
	 * DatabaseManager implements DatabaseErrorHandler and is by default it's own ErrorHandler.
	 *
	 * This function kills the script, and outputs an error.
	 *
	 * @param string 	$message
	 * @param integer 	$errorNo
	 * @param Exception $e
	 */
	public function HandleError($message, $errorNo, Exception $e) {

		$trace = $e->GetTrace();
		$appTrace = $trace[0];

		// find the trace element that does not originate from this file.
		for($i=0; $i < count($trace)-1; $i++) { if (isset($trace[$i]['file']) && strpos($trace[$i]['file'], __FILE__) === false) { $appTrace = $trace[$i]; break; } }
		
		// get the last used connection
		$connection = $this->GetPreferredConnection($this->lastConnectionType);

		// clean the output, so the user only sees this error
		@ob_end_clean();

		print( sprintf(	"<pre>\n" .
						"****************************************************************************************************************************\n" .
						"Failed to execute your Query\n" .
						"Mysql Error: 		%s\n" .
						"Mysql Error Number:	%s\n" .
						"File:			%s\n" .
						"Line:			%s\n" .
						"Query:\n" .
						"%s\n" .
						"Host:			%s\n" .
						"****************************************************************************************************************************\n" .
						"</pre>\n", 
						wordwrap($this->Error(), 100, "\n			"),
						mysql_errno(),
						$appTrace['file'],
						$appTrace['line'],
						$e->GetMessage(),
						$connection['host']));

		exit();

	}



	/**
	 * Returns the preferred conneciton for a given query type
	 * @param 	string 		$type
	 * @access 	private
	 * @return 	array
	 */
	private function GetPreferredConnection($type) {

		$name = $this->GetPreferredConnectionName($type);
		if ($type == "master") { return $this->master[$name]; } else { return $this->slave[$name]; }

	}



	/**
	 * This function is called whenever SetDebug() is set
	 *
	 * @param 	string	$type		Connection Type (master or slave)
	 * @param 	string	$query 		The query to output
	 * @return	boolean				Returns true of the query should be executed, false otherwise.
	 * @access 	private
	 */
	private function DebugHandler($type, $query) {

		$execute = !(self::$debugLevel == DATABASE_DEBUG_NOWRITE && $type == DATABASE_TYPE_MASTER);

		if (self::$debugLevel >= DATABASE_DEBUG_PRINT) {

			echo sprintf(	"***************************************************************************\n" .
						 	"Connection Type:	 %s\n" .
							"Connection Name:	 %s\n" .
							"Query:				 %s\n" .
							"Executed: 			 %s\n" .
							"***************************************************************************\n\n" ,
			$type,
			$this->GetPreferredConnectionName($type),
			trim($query),
			($execute) ? "Yes" : "No" );

		}
		
		return $execute;

	}



	/**
	 * Checks if an array is a 1D array
	 *
	 * @param array $input
	 * @return boolean
	 * @access private
	 */
	private function Is1DArray(array $input) {

		foreach($input as $element) { if (is_array($element)) { return false; } }
		return true;

	}



	/**
	 * If the input is a 1D array, It wraps it into another array
	 *
	 * @param  string $input
	 * @return array
	 * @access private
	 */
	private function Wrap1DArray( array $input ) {

		if ($this->Is1DArray( $input )) { return array( $input );
		} else { return $input; }

	}

}

?>