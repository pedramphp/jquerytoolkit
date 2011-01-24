<?php

/** 
 * @category	Modules
 * @package 	Database
 * @subpackage 	Helpers
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 19, 2010 8:36:49 AM
 * @copyright 	Vortal Group
 *
 */



/**
 * This class can be used to assemble SQL statements.
 * 
 * @package 	Database
 * @subpackage 	Helpers
 * @author 		Juan Caldera
 * @category 	backend
 * @access 		public
 * @version 	1.0
 * @since 		2010 - 03 - 25 02:04 PM ( Thursday )
 * @copyright 	Vortal Group
 *
 * All the methods only takes two parameters: an array of arrays that contains all the information necessary to build
 * the desired query, and a callback routine for Escaping values. The callback function must take a single value
 * (and string to escape), and returns the escaped version of that string.
 *
 * The parameter for all the calls should look this:
 *
 * $parameters = array( 'table'			=> 'my_table',
 * 					   'fields' 		=> array(...),
 * 					   'conditions'		=> array(...),
 * 					   'limit'			=> array(...)
 * 									...
 *				);
 *
 *
 *
 *|***************************************************************************************************|
 *|***************************************************************************************************|
 *|***************************************************************************************************|
 *|																									 |
 *|																									 |
 *|IMPORTANT NOTE: With the exclusion of values, all the parameters can be passed as string, and they |
 *|            will be appended into the correct part of the query AS IS.							 |
 *|																									 |
 *|																									 |
 *|***************************************************************************************************|
 *|***************************************************************************************************|
 *|***************************************************************************************************|
 *
 *
 *
 * The Definition of those respective elements should be defined as follows:
 *
 * <--------------------------------------------------------------------------------------------------------->
 * @param	string			$table			Table name
 *
 * <--------------------------------------------------------------------------------------------------------->
 * @param	string			$database		Database name, can be set to null to use the active database
 *
 * <--------------------------------------------------------------------------------------------------------->
 * @param	array|string	$fields			Used in query statements
 * 			<code><?php
 * // Defining a Simple Field List,
 * 			// How to do:     SELECT `id`, `first`, `last`, `location` FROM ...
 * 			$fields = array( 'id', 'first', 'last', 'location' );
 * 			$fields = 'id, first, last, location';
 *
 *  //Defining a Field List that renames some of the fields.
 * 			// How to do:     SELECT id, first as first_name, last as last_name, location
 * 			$fields = array( 'id', array('first', 'first_name'), array('last', 'last_name'), 'location' );
 * 			$fields = 'id, first AS first_name, last AS last_name, location';
 *			?></code>
 * <--------------------------------------------------------------------------------------------------------->
 * @param	array			$values			An array in the form of $field => $value, used in INSERT/UPDATE
 * 			<code><?php
 * 			//Defining values list
 * 			$values = array(	'First'	=>'John',
 * 								'Last'	=>'Smith',
 * 								'Age'	=> 30 );
 *
 *			?></code>
 * <--------------------------------------------------------------------------------------------------------->
 * @param	string|array	$conditions		This parameter goes in the WHERE clause. You can either pass a string
 * 											(not inlcluding "WHERE" itself. Or pass an associative array
 * 											as follows:
 *
 * @example Simple definition
 *  		<code><?php
 * 			// How to do:     equivalent to: "WHERE id='5' AND birthday='10-10-1990'
 * 			$conditions = array("id" => 5, "birthday" => "10-10-1990");
 * 			$conditions = "id='5' AND birthday='10-10-1990'";
 *			?></code>
 * <--------------------------------------------------------------------------------------------------------->
 * @param	array|string	$order			This parameter goes in the ORDER clause.
 *
 * 			<code><?php
 * 			// How to do:     ORDER BY name DESC, age ASC, weight DESC
 * 			$order = array('name'=>'DESC', 'age' => 'ASC', 'weight' => 'DESC');
 * 			$order = 'name DESC, age ASC, weight DESC';
 *			?></code>
 *
 * <--------------------------------------------------------------------------------------------------------->
 * @param	array|string	$group			This parameter goes to the GROUP clause
 *
 * 			<code><?php
 * 			// How to do:	GROUP BY name, age
 * 			$group = array('name', 'age'); 	// important, order matters
 * 			$group = "name, age";
 *			?></code>
 *
 * <--------------------------------------------------------------------------------------------------------->
 * @param	array|integer||string	$limit	This parameter goes in the Limit clause
 *
 * 			<code><?php
 *			// How to do:	LIMIT 5
 *			$limit = 5;
 *
 * 
 *			// How to do:	LIMIT 5,10
 *			$limit = array(5,10);			// important, order matters.
 *			$limit = '5,10';
 *			?></code>
 * <--------------------------------------------------------------------------------------------------------->
 */
class SQLHelper {


	/**
	 * Creates a DESCRIBE query.
	 *
	 * @see Package documentation on how to define $parameter's elements
	 * @param		array	$parameters		Required: table
	 * 										Optional: database
	 * @exception	Excption				Thrown when a required element is not found in $parameters
	 * @return		string
	 */
	static public function Describe($parameters) {

		// ensure required parameters exist.
		self::AssertDefined($parameters, 'table');

		// prep the query parameters
		$defaults = array( 'database' => '' );
		$parameters = self::MergeDefaults($defaults, $parameters);

		return sprintf('Describe %s`%s`',
		self::SetDatabase($parameters['database']),
		$parameters['table']);

	}
	
	
	
	/**
	 * Delete
	 * @param array $parameters
	 * @param callback $escapeCallback
	 */
	static public function Delete($parameters, $escapeCallback = null) {
		
		self::AssertDefined($parameters, 'table');
		self::AssertDefined($parameters, 'conditions'); // prevent delete alls from being sent
		
		// prep the query parameters
		$defaults = array( 'database' => '', 'limit' => array() );
		$parameters = self::MergeDefaults($defaults, $parameters);
		
		// build the statement
		return sprintf("DELETE FROM %s`%s` %s %s",
									self::SetDatabase($parameters['database']),
									$parameters['table'],
									self::WhereConditionsList($parameters['conditions'], $escapeCallback),
									self::Limit($parameters['limit']));
		
	}



	/**
	 * Creates a SELECT query.
	 *
	 * @see Package documentation on how to define $parameter's elements
	 * @param		array	$parameters		Required: table
	 * 										Optional: database, fields, conditions, order, group, limit
	 * @exception	Excption				Thrown when a required element is not found in $parameters
	 * @return		string
	 */
	static public function Select($parameters, $escapeCallback = null) {

		// ensure required parameters exist.
		self::AssertDefined($parameters, 'table');

		// prep the query parameters
		$defaults = array(	'fields' => array('*'),
							'database' => '', 
							'conditions' => array(), 
							'order' => array(), 
							'group' => array(), 
							'limit' => array() );

		// merge parameters with defaults
		$parameters = self::MergeDefaults($defaults, $parameters);

		// process the statement
		return sprintf("SELECT %s FROM %s`%s` %s %s %s %s",
									self::FieldList($parameters['fields']),
									self::SetDatabase($parameters['database']),
									$parameters['table'],
									self::WhereConditionsList($parameters['conditions'], $escapeCallback),
									self::OrderList($parameters['order']),
									self::GroupList($parameters['group']),
									self::Limit($parameters['limit']));

	}



	/**
	 * Creates an UPDATE query.
	 *
	 * @see Package documentation on how to define $parameter's elements
	 * @param		array	$parameters		Required: table, values
	 * 										Optional: database, conditions, limit
	 * @exception	Excption				Thrown when a required element is not found in $parameters
	 * @return		string
	 */
	static public function Update($parameters, $escapeCallback = null) {

		// ensure required parameters exist.
		self::AssertDefined('values', $parameters);
		self::AssertDefined('table', $parameters);

		// prep the query parameters
		$defaults = array(	'database' => '',
							'conditions' => array(), 
							'limit' => array() );
		$parameters = self::MergeDefaults($defaults, $parameters);

		return sprintf("UPDATE %s`%s` SET %s %s %s",
									self::SetDatabase($parameters['database']),
									$parameters['table'],
									self::FieldValuePair($parameters['values'], $escapeCallback),
									self::WhereConditionsList($parameters['conditions'], $escapeCallback),
									self::Limit($parameters['limit']));

	}
	
	
	
	static public function InsertMultiple($parameters, $onDuplicateUpdate = true, $escapeCallback = null) {
		
		// ensure required parameters exist.
		self::AssertDefined('values', $parameters);
		self::AssertDefined('table', $parameters);

		// prep the query parameters
		$defaults = array(	'database' => '' );
		$parameters = self::MergeDefaults($defaults, $parameters);

		$fields = array_keys($parameters['values'][0]);
		
		// assemble the insert query
		$insertQuery = sprintf("INSERT INTO %s`%s` (%s) VALUES %s",
									self::SetDatabase($parameters['database']),
									$parameters['table'],
									self::FieldList( $fields ),
									self::ValueList( $fields, $parameters['values'], $escapeCallback));

		// assemble the ON UPDATE query if the caller requested for it.
		if ($onDuplicateUpdate === true) {
			$onUpdate = sprintf(" ON DUPLICATE KEY UPDATE %s", 
									self::FieldValuePair(
											$parameters['values'], 
											$escapeCallback));
		} else { $onUpdate = ""; }
		
		return $insertQuery . $onUpdate;
		
	}



	/**
	 * Creates an INSERT query.
	 *
	 * @see Package documentation on how to define $parameter's elements
	 * @param		array	$parameters		Required: table, values
	 * 										Optional: database
	 * @exception	Excption				Thrown when a required element is not found in $parameters
	 * @return		string
	 */
	static public function Insert($parameters, $onDuplicateUpdate = true, $escapeCallback = null) {

		$parameters['values'] = array($parameters['values']);
		return self::InsertMultiple($parameters, $onDuplicateUpdate, $escapeCallback);

	}




















	/* ------------------------------------------------------------------------------------>
	 *
	 * 				Query Assembler Function
	 * 				These are used to assemble various parts of a query
	 *
	 < -------------------------------------------------------------------------------------*/



	static private function FieldValuePair($values, $escapeCallback = null) {

		$values = self::EscapeArray($values, $escapeCallback);

		$list = array();
		foreach($values as $field => $value) {
			$list[] = sprintf("`%s` = '%s'", $field, $value);
		}

		return implode(', ', $list);
	}



	static private function SetDatabase($database) {

		if ($database) { return sprintf ('`%s`.', $database); }
		return "";

	}



	/**
	 * Creates the value list segment of an INSERT query.
	 * @return string
	 */
	static private function ValueList($fields, $valuesArray, $escapeCallback) {

		$flatValuesList = '';
		foreach($valuesArray as $values) {
		
			$values = self::EscapeArray($values, $escapeCallback);
			$list = array();
			foreach($fields as $field) { $list[] = "'{$values[$field]}'"; }
			$flatValuesList[] = '(' . implode(', ', $list) . ')';
		}
		
		return implode(',', $flatValuesList);
		
	}



	/**
	 * Creates the Field list clause of the query
	 * @return string
	 */
	static private function FieldList($fields) {

		// if not an array, consider it a string
		if (!is_array($fields)) return $fields;

		$list = array();
		foreach($fields as $field) {
			if (is_array($field)) { $list[] = sprintf('`%s` AS `%s`', $fields[0], $fields[1]);
			} else { $list[] = strstr($field, '*') !== false ? $field : "`$field`"; }
		}

		return implode(', ', $list);

	}


	/**
	 * Creates the WHERE clause
	 * 
	 * @param	array|string	$conditions
	 * @param	boolean			$autoAppendWhere
	 * @param	callback		$escapeCallback
	 * @return string
	 */
	static public function WhereConditionsList($conditions, $escapeCallback = null, $autoAppendWhere = true ) {


		if (is_array($conditions))  {
			$conditions = self::EscapeArray($conditions, $escapeCallback);
			$list = array();
			
			foreach($conditions as $field => $value) {  
				
				// if the $value is an array, we're going to try and assempble a "field IN (list)" clause
				if (is_array($value)) { $list[] = self::WhereInList($field, $value);
				
				// if the field is an integer, treat value as a full condition
				} elseif (is_integer($field)) { $list[] = $value; } 
				
				// otherwise, proceed as normal
				else { $list[] = sprintf("%s = '%s'", self::FormatConditionField($field), $value); }
				 
			}
			
			$conditions = trim(implode(' AND ', $list));
		} // else not an array, consider it a string

		return $autoAppendWhere && !empty($conditions) ? "WHERE " . $conditions : $conditions;

	}
	
	
	
	private function FormatConditionField($input) {
		
		if (strpos($input, '.') !== false) { return str_replace('.', '.`', $input) . '`';
		} else { return "`$input`"; }
		
	}
	
	
	
	/**
	 * Helper function for WhereConditionList
	 *
	 */
	static private function WhereInList($field, array $values) {
		
		$list = array();
		foreach($values as $value) { $list[] =  "'$value'"; }
		$list = implode(',', $list);

		return sprintf("%s IN (%s)", self::FormatConditionField($field), $list);
		
	}
	
	
	
	/**
	 * Creates the ORDER clause of the query
	 *
	 * @param array|string	$order
	 * @param boolean
	 * @return string
	 */
	static private function OrderList($order, $autoAppendOrder = true) {

		if (is_array($order)) {
			$list = array();
			foreach($order as $field => $order) {
				$list[] = sprintf("%s %s", $field, $order);
			}
			$order = implode(', ', $list);
		}

		$order = trim($order);
		return $autoAppendOrder && !empty($order) ? "ORDER BY " . $order : $order;

	}



	/**
	 * Creates the group clause of the query
	 *
	 * @param	array|string	$group
	 * @param	boolean
	 * @returns string
	 */
	static private function GroupList($group, $autoAppendGroup = true) {

		if (is_array($group)) {
			$group = implode(', ', $group);
		}

		$group = trim($group);
		return $autoAppendGroup && !empty($group) ? "GROUP BY " . $group : $group;

	}



	/**
	 * Creates the LIMIT portion of the query
	 * @param	array|string	$limit
	 * @param	boolean			$autoAppendLimit		Appends "LIMIT" to the beginning of the clause
	 * @return 	string
	 */
	static private function Limit($limit, $autoAppendLimit = true) {

		if (empty($limit)) {return "";}
		elseif (is_array($limit)) { $limit = sprintf('%s,%s', $limit[0], $limit[1]); return $autoAppendLimit ? "LIMIT " .$limit : $limit; }
		else {return $autoAppendLimit && $limit != "" ? "LIMIT $limit" : $limit;}

	}
	
	
	
	
	
	
	
	
	
	/* ------------------------------------------------------------------------------------>
	 *
	 * 				PRIVATE HELPER FUNCTIONS
	 * 				These functions are used to clean up, and perform checks on data, etc.
	 *
	 < -------------------------------------------------------------------------------------*/

	/**
	 * Merges the default values array with the parameters
	 * @param 	array	$defaults		Default values contained in an array
	 * @param	array	$parameters
	 * @return  array					Returns the field-in $parameters
	 */
	static private function MergeDefaults($defaults, $parameters) {
		return array_merge($defaults, $parameters);
	}



	/**
	 * Tests if a parameter is set. If it's not, throw an exception
	 * @param array $parameters
	 * @param string $field
	 */
	static private function AssertDefined($parameters, $field) {

		if (empty($parameters[$field])) {
			throw new Exception("A proper SQL statement cannot be created without specifying $field.");
		}

	}


	/**
	 * Recursively Runs an "Escaper" callback on all values of an array
	 * @param 	array		$array
	 * @param	callback	$callback
	 * @return 	$array
	 */
	static private function EscapeArray($array, $callback = null) {

		if ($callback == null) return $array;

		foreach($array as $key=>$value) {
			if (is_array($array[$key])) { $array[$key] = self::EscapeArray($array[$key], $callback); }
			
			else { $array[$key] = call_user_func($callback, $value); }
		}

		return $array;
	}

}


?>