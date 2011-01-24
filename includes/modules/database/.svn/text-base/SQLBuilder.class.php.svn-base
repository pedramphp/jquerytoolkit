<?php 

require_once('SQLHelper.class.php');

/**
 * Provides a PHP-friendly way of building queries.
 * 
 * @category		Modules
 * @package			Database
 * @subpackage		SQLBuilder
 * @author			Juan Caldera
 * @since			Jun 8, 2010 9:21:22 AM
 * @version			1
 */
class SQLBuilder {
	
	/**
	 * The assembled query is stored here as an array
	 * @var array
	 */
	protected $query = array();

	
	private $callback = 'mysql_real_escape_string';
	
	
	/**
	 * Create a new Select SQL Builder
	 * @return SelectBuilder
	 */
	static public function Select() { return new SelectBuilder(); }
	
	
	protected function __construct() {}
	
	
	
	/**
	 * Sets the `escaper` function that will be used by this function to escape quotes.
	 * @param callback $callback
	 */
	public function SetEscapeCallback( $callback ) { $this->callback = $callback; }
	
	
	
	
	/**
	 * Adds a where condition to the statement
	 * @param string $whereClause
	 * @param string $whereValue
	 * @return SQLBuilder
	 */
	public function Where() { 
		
		$args = func_get_args();
		
		// The argument will be treated lieterally
		if (count($args) == 1) { $this->query['conditions'][] = $args[0]; }
		
		// the call sent a field/value pair
		elseif (count($args) == 2 && strpos($args[0], '?') === false) {

			$this->query['conditions'][$args[0]] = $args[1];
			
		} else {
			
			// the first argument will be treated as a condition fragment, and the rest will be
			// pasted onto it.
			$args[0] = str_replace('?', '%s', $args[0]);
			
			for($i=1; $i<count($args); $i++) {  
				
				if (is_array($args[$i])) { $args[$i] = "'" . implode( "','" , SQLHelper::EscapeArray($args[$i])) . "'";  
				} else { $args[$i] = "'{$args[$i]}'"; }
			
			} /* </ args foreach quote loop > */

			// paste the variables into the fragment, and add it to the conditions list
			$fragment = call_user_func_array('sprintf', $args); 
			$this->query['conditions'][] =  $fragment;
		}
		return $this;
		
	}
	
	/**
	 * Adds an order by Clause to the statement
	 * @param string $field
	 * @param string $order
	 */
	public function OrderBy($field, $order='ASC') {
		
		$this->query['order'][$field] = $order;
		return $this;
		
	}
	
	
	
	/**
	 * Adds a FROM clause to the statement
	 * @param string $database
	 * @param string $table
	 * @return SQLBuilder
	 */
	public function From($database = false, $table = false) {
		
		if (!empty($database)) { $this->query['database'] = $database; }
		
		if (!empty($table)) { $this->query['table'] = $table; }
		return $this;
	}
	
	
	
	/**
	 * Adds a LIMIT clause to the statement
	 * @param integer $limit
	 * @param integer $offset
	 */
	public function Limit($limit, $offset=0) { 

		$this->query['limit'] = array($offset, $limit); 
		return $this; 
	
	}
	
	
	
	public function ToArray() { return $this->query; }
	
}


class SelectBuilder extends SQLBuilder {
	
	/**
	 * Add fields to the statement
	 * @param $field
	 * @return SelectBuilder
	 */
	public function Fields() { $this->query['fields'] = func_get_args(); return $this; }
	
	
	
	/**
	 * Adds a having clause to the statement
	 */
	public function Having() {
		
		$args = func_get_args();
		if (count($args) == 1) { $this->query['having'][] = $args[0]; }
		elseif (count($args) == 2 && strpos($args[0], '?') === false) {

			if (is_array($args[1])) { return $this->Having("{$args[0]} IN (?)", $args[1]); }
			else { return $this->Where("{$args[0]} = ?", $args[1]); }
			
		} else {
			
			// the first argument will be treated as a condition fragment, and the rest will be
			// pasted onto it.
			$args[0] = str_replace('?', '%s', $args[0]);
			
			for($i=1; $i<count($args); $i++) {  
				
				if (is_array($args[$i])) { $args[$i] = "'" . implode( "','" , SQLHelper::EscapeArray($args[$i])) . "'";  
				} else { $args[$i] = "'{$args[$i]}'"; }
			
			} /* </ args foreach quote loop > */

			// paste the variables into the fragment, and add it to the conditions list
			$fragment = call_user_func_array('sprintf', $args); 
			$this->query['conditions'][] =  $fragment;
		}
		return $this;
		
	}
	
	
	public function ToString() { return SQLHelper::Select($this->query, $this->callback); }
	
}

?>