<?php 
/** 
 * @category	Modules
 * @package 	Database
 * @subpackage 	Interfaces
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 19, 2010 8:36:49 AM
 * @copyright 	Vortal Group
 *
 */


/**
 * This interface lays out the typical command found in database wrappers
 * 
 * @author Juan Caldera
 *
 */
interface DatabaseInterface {
	
	
	/**
	 * Fetch a result row as an associative array
	 * @param 	resource	$result		The result resource that is being evaluated. This result 
	 * 									comes from a call to Query().
	 * @return 	array					Returns an associative array of strings that corresponds 
	 * 									to the fetched row, or FALSE if there are no more rows.
	 */
	public function FetchAssoc( $result );
	
	/**
	 * Performs a query and returns all the results.
	 * 
	 * @param	string		$query			Database query
	 * @param 	boolean		$withException	Throw an exception when an error occurs
	 * @param	boolean		$asObject		If true, returns objects instead of arrays
	 * @param	string		$class			When $asObject = true, this is the class name to instantiate
	 * @param	array		$params			Parameters to be passed to the class's contructor
	 * 
	 * @return	Array						Returns either an array of arrays, or an array of objects
	 *
	 */
	public function FetchObject( $result, $class='stdobj', $params = array() );
	
	/**
	 * Sends a query to the database
	 * 
	 * @param 	string 		$query			Database query
	 * @param 	boolean 	$withException	If true, an exception is thrown when an error occurs.
	 * 
	 * @return mixed
	 */
	public function Query( $query, $withException = true);
	
	/**
	 * Escapes special characters in a string for use in a SQL statement
	 * 
	 * @param	string		$string			The string that is to be escaped.
	 * @return 	string						Returns the escaped string, or FALSE on error.
	 */
	public function EscapeString( $string );
	
	/**
	 * Performs a query and returns all the results as an array of arrays.
	 * 
	 * @param	string		$query			Database query
	 * @param 	boolean		$withException	Throw an exception when an error occurs
	 * 
	 * @return	Array						An array of arrays
	 *
	 */
	public function FetchAllRowsAssoc( $query, $withException = true );

	/**
	 * Performs a query and returns all the results as an array of objects.
	 * 
	 * @param	string		$query			Database query
	 * @param	string		$class			The name of the class to instantiate, set the properties of and return. 
	 * 										If not specified, a stdClass object is returned.
	 * @param	array		$params			An optional array of parameters to pass to the constructor for 
	 * 										$class objects.
	 * @param 	boolean		$withException	Throw an exception when an error occurs
	 * 
	 * @return	Array						An array of $class objects
	 *
	 */
	public function FetchAllRowsObject( $query, $class = 'stdclass', $params = null, $withException = true );
	
	/**
	 * Get the number of affected rows by the last INSERT, UPDATE, REPLACE or DELETE 
	 * query associated with $connectionLabel .
	 * 
	 * @param	boolean|string|resource			Either the name of the database connection, an actual 
	 * 											database link, or false to use default
	 * @return	integer							Returns the number of affected rows on success, and -1 if 
	 * 											the last query failed.
	 * 
	 */
	public function AffectedRows( $link = false );
	
	/**
	 * Returns the last encountered error.
	 * 
	 * @return string
	 */
	public function Error();
	
	/**
	 * Returns the number of rows contained in a results resource
	 * @param 	resource						The result resource that is being evaluated. 
	 * 											This result comes from a call to mysql_query().
	 * @return	integer
	 */
	public function NumberOfRows( $result );
	
	/**
	 * Get the ID generated in the last query
	 * @param	boolean|string|resource			Either the name of the database connection, an  
	 * 											actual database link, or false to use default
	 * @return 	integer							The number of rows in a result set on success or 
	 * 											FALSE on failure.
	 *
	 */
	public function LastInsertID( $link = false );

	
	/**
	 * Select a MySQL database
	 * @param	string					Name of database to set as default for the connection
	 * @param	string					Type of connection (master or slave)
	 * @param	boolean|string|resource	Either the name of the database connection, an actual 
	 * 									database link, or false to use default
	 * @return	boolean
	 *
	 */
	public function SelectDB($databaseName, $type = false, $link = false);
	
}



interface DatabaseErrorHandler {
	
	public function HandleError($dbErrorMessage, $dbErrorNo, Exception $e);
	
}

?>