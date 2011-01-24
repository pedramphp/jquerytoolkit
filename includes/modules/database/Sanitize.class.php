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
 * This encasulates various types of sanitization routines that are useful when ensuring clean data inputs.
 *
 * If PHP verion is less than 5.3 The Magic Functions cannot be called in the static context.
 * This is due to the fact that __callStatic has not been implemented for this version.
 * 
 * For consitency, it's best to just use the object context for all application, unless it is certain
 * the deployment environement will alway shave PHP 5.3 or higher.
 * The functions would work exactly the same way in the object context, except the caller needs
 * to instanciate an object before doing so. See Examples.
 *
 * IMPORTANT: All the Sanitization calls will return false when the function fails
 * to sanitize the input, with the exception of Boolean, which returns null.
 *
 * On a successful sanitization, the functions will return valid values of the correct type.
 *
 * <code>
 * <?php
 * // <--------------------------- SIMPLE STATIC CALLS ---------------------------------->
 *
 * Sanitize::Integer('"4343"x'); 			// returns (int)4343
 * Sanitize::Integer('"fdsadfdsx');			// returns (boolean) false
 * Sanitize::Boolean('false');				// returns (boolean) false
 * Sanitize::Boolean('x');					// returns null; NOTE: Boolean returns null on fail, 
 * 											// instead of false.
 * Sanitize::Escape('"fd\fdsfds"');			// returns \"fd\\fdsfds\"
 * Sanitize::String('false');				// returns (boolean) false
 * Sanitize::SpecialChars/('false');		// returns (boolean) false
 * Sanitize::Email('false');				// returns (boolean) false
 * Sanitize::URL('http://google.com');		// returns http://google.com
 * Sanitize::Decimal('12x3.x25x');			// returns 123.25
 *
 * <------------------------------ MAGIC FUNCTION CALLS --------------------------------->
 *
 * The same function calls as above can be used to grab data directly from various sources
 * such as $_POST, $_GET, $_REQUEST, and custom arrays.
 *
 * $_POST = array(
 * 				'my_integer' => '30',
 * 				'my_boolean' => 'false',
 * 				'my_string'	 => '<a href="google.com">test</a>,
 * 				'my_decimal' =>	'23.32'
 * 				);
 * 
 * Sanitize::IntegerFromPost('my_integer');		// returns (int) 30
 * Sanitize::IntegerFromPost('my_boolean');		// returns (boolean) false
 * Sanitize::IntegerFromPost('my_string');		// returns (string) test
 * Sanitize::IntegerFromPost('my_decimal');		// returns (float) 23.32
 * 
 * // For these examples, the array $_POST is being passed, but this can just be any array
 * // including user-created ones.
 * 
 * Sanitize::IntegerFromArray($_POST, 'my_integer');		// returns (int) 30
 * Sanitize::IntegerFromArray($_POST, 'my_boolean');		// returns (boolean) false
 * Sanitize::IntegerFromArray($_POST, 'my_string');			// returns (string) test
 * Sanitize::IntegerFromArray($_POST, 'my_decimal');		// returns (float) 23.32
 * 
 * 
 * <------------------------------ Calls through the object context --------------------------------->
 *
 *
 * $_POST = array(
 * 				'my_integer' => '30',
 * 				'my_boolean' => 'false',
 * 				'my_string'	 => '<a href="google.com">test</a>,
 * 				'my_decimal' =>	'23.32'
 * 				);
 *
 * $sanitize = new Sanitize()
 * $sanitize->IntegerFromPost('my_integer');				// returns (int) 30
 * $sanitize->IntegerFromPost('my_boolean');				// returns (boolean) false
 * $sanitize->IntegerFromArray($_POST, 'my_string');		// returns (string) test
 * $sanitize->IntegerFromArray($_POST, 'my_decimal');		// returns (float) 23.32
 *
 * ?>
 * </code>
 *
 * @package 	Database
 * @subpackage 	Sanitization
 * @author 		Juan Caldera
 * @category 	backend
 * @access 		public
 * @version 	1.0
 * @since 		2010 - 03 - 17 02:25 PM ( Tuesday )
 * @copyright 	Vortal Group
 *
 * 
 */
class Sanitize {



	/**
	 * Escapes certain characters in the string.
	 *
	 * @param 	$var 	string
	 * @param 	$db		string		mysql, postgre
	 * @return string
	 */
	static public function Escape($var, $db='mysql') {

		if (get_magic_quotes_gpc()) $var = stripslashes($var);

		switch(strtolower($db)) {
			case 'mysql': return mysqli_real_escape_string($var);
			case 'postgresql':
			case 'postgre': return pg_escape_string($var);
			default:  return addslashes($var);
		}

	}
	
	
	
	/**
	 * HTML-escape '"<>& and characters with ASCII value less than 32, 
	 * optionally strip or encode other special characters. 
	 * 
	 * @param $var
	 * @param $flags			See: http://us.php.net/manual/en/filter.filters.sanitize.php
	 * @return string|boolean	Returns false when sanitization fails.
	 */
	static public function SpecialChars($var, $flags = null) {
		
		$cleaned = filter_var($var, FILTER_SANITIZE_STRING, $flags);
		return $cleaned == "" ? false : $cleaned;
		
	}



	/**
	 * Strip tags, optionally strip or encode special characters. 
	 * 
	 * @param $var
	 * @param $flags See: http://us2.php.net/manual/en/filter.filters.sanitize.php
	 * @return string|boolean Retuns false when sanitization fails.
	 */
	static public function String($var, $flags=null) {

		$cleaned = filter_var($var, FILTER_SANITIZE_STRING, $flags);
		return $cleaned == "" ? false : $cleaned;

	}




	/**
	 * Remove all characters except digits, +- and optionally .,eE.
	 * 
	 * @param 	$var
	 * @param 	$flags  		See: http://us2.php.net/manual/en/filter.filters.sanitize.php
	 * @return 	float|boolean	Return false when sanitization fails.
	 */
	static public function Float($var, $flags = null) {

		$cleaned = filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, $flags);
		return $cleaned == "" ? false : (float)$cleaned;

	}
	
	
	
	
	/**
	 * This is a more specific verion of Sanitize::Float that by default uses the
	 * FILTER_FLAG_ALLOW_FRACTION flag.
	 * 
	 * @param 	$var
	 * @param 	$flags			See: http://us2.php.net/manual/en/filter.filters.sanitize.php
	 * @return 	float|boolean	Return false when sanitization fails.
	 */
	static public function Decimal($var, $flags = null) {
		
		$cleaned = filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, $flags | FILTER_FLAG_ALLOW_FRACTION);
		return $cleaned == "" ? false : (float)$cleaned;
		
	}



	/**
	 * Sanitizes input as boolean. Returns null when sanitazion fails.
	 * 
	 * @param $var string
	 * @return boolean|null	Returns the boolean back on success, null otherwise
	 */
	static public function Boolean( $var ) {

		$options = array('flags' => FILTER_NULL_ON_FAILURE);
		$result = filter_var( $var, FILTER_VALIDATE_BOOLEAN, $options);

	}



	/**
	 * Remove all characters except digits, plus and minus sign. 
	 *
	 * @param string $var
	 * @return integer|boolean Returns either an integer on success, or false when it fails.
	 */
	static public function Integer( $var ) {

		$cleaned = filter_var( $var, FILTER_SANITIZE_NUMBER_INT);
		return $cleaned == "" ? false : (int)$cleaned;

	}



	/**
	 * Remove all characters except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=. 
	 *
	 * @param string $var
	 * @return string|boolean Returns false when URL is not valid.
	 */
	static public function URL( $var ) {

		$cleaned = filter_var( $var, FILTER_SANITIZE_URL );
		return filter_var($cleaned, FILTER_VALIDATE_URL) ? $cleaned : false;

	}



	/**
	 * Remove all characters except letters, digits and !#$%&'*+-/=?^_`{|}~@.[]. 
	 * 
	 * @param $var
	 * @return string|boolean Returns false when email address is not valid.
	 */
	static public function Email ( $var ) {

		$cleaned = filter_var( $var, FILTER_SANITIZE_EMAIL );
		return filter_var($cleaned, FILTER_VALIDATE_EMAIL) ? $cleaned : false;

	}
	
	
	
	/**
	 * Validate a variable with fixed set of possible values.
	 * @param mixed $var
	 * @param array $allowed
	 */
	static public function Enum ( $var, $allowed ) { return in_array( $var, $allowed ) ? $var : false; }
	
	
	
	/**
	 * Validate multiple inputs, each with a corresponding filter.
	 * 
	 * All elements of filter must have a corresponding value in $data, otherwise
	 * this would throw a MissingInputException.
	 * 
	 * If an entry in data does not have a corresponding value in $filters, it will
	 * not be returned by this method.
	 * 
	 * $filters must be an array of arrays, first indexed by the variable name, then by the
	 * filter directives.
	 * 
	 * example:
	 * <code>
	 * 
	 * // we wish to validate the following
	 * $data = array( 	'MyInteger' => 30,
	 * 					'MyEmail'	=> 'email@example.com'
	 * 					'MyWebsite'	=> 'http://example.com'
	 * 	)
	 * 
	 * // these are the rules we need to set
	 * $filters = array(
	 * 				'MyInteger' =>
	 * );
	 * 
	 * </code>
	 * 
	 * @param array $filters
	 * @param array $data
	 */
	static public function ValidateMultiple( array $filters, array $data ) {
		
		$processedData = array();
		
		foreach($filters as $variable => $filter) {
		
			// first, check if the variable was sent. All variables are required
			if ( !isset( $data[ $variable ] ) )
			{
				if (!empty($filter[ 'required'] ) && $filter['required'])
				{
					throw new MissingInputException( self::FormatMissingErrorMessage($variable), 0, $variable );
				} else { continue; }	// variable doesn't exist, but it is not required, continue to the next filter	
			} 
			
			if ( $data[ $variable ] == '' ) { $processedData[$variable] = ''; continue; }
						
			try
			{
				// now, let see if the data is valid
				if ($filter['type'] == 'Enum')
				{
					$processedData[$variable] = self::Validate(	$data[$variable],
																$filter[ 'type' ],
																$filter[ 'allowed' ] );	
				} else
				{
					$processedData[$variable] = self::Validate(	$data[ $variable ],
																$filter[ 'type' ]	 );
				}
			} catch (InvalidInputException $e)
			{ 
				
				$validInput = ($filter['type'] == 'Enum') ? implode(',', $filter['allowed']) : $filter['type'];
				$report = array(	'varName'	=> 	$variable, 
									'type'		=>	$filter['type'], 
									'input'		=>	$data[$variable], 
									'valid'		=>	isset($filter['allowed']) ? $filter['allowed'] : null );
				throw new InvalidInputException( self::FormatInvalidErrorMessage( $variable, $validInput), 0, $report); 
			}
		} /* </foreach> */
		
		return $processedData;
		
	}

	
	
	
	/**
	 * This is a wrapper function for any of validation rules defined in this class. 
	 *  
	 * @param  mixed $value
	 * @param  string $type
	 * @param  mixed You can pass as many parameters as required by the corresponding validation function.
	 * @throws InvalidArgumentException Thrown when a requested filter is not defined
	 * @throws InvalidInputException	Throws when the supplied value fails validation
	 * @return mixed
	 */
	static public function Validate( $value, $type ) {
	
		// using func_get_args instead of the explicit parameters the user to access 
		// any and all parameters required for the validator that we are going to relay to.
		$args = func_get_args();
		$value = array_shift( $args );
		$type = ucfirst( strtolower( array_shift( $args ) ) );
		
		if (method_exists(__CLASS__, $type)) { 
			array_unshift($args, $value);
			
			$validated = call_user_func_array(array(__CLASS__, $type), $args); 
			if ($validated === false) {  throw new InvalidInputException( self::FormatInvalidErrorMessage(false, $type) );
			 } else { return $validated; }
			
		} else { throw new InvalidArgumentException( "$type is not a defined validator." ); }
	
	}


	/**
	 * This magic function allows the other functions defined above to be called
	 * using various other forms.
	 * The magic function calls are in the form of:
	 * 
	 * (Type)From(Source)
	 * 
	 * Where types are the various functions defined above and source can either be
	 * Get, Post, Request (where $_GET, $_POST, and $_REQUEST will be used respectively)
	 * or Array where a custom array (given as parameter) will be used as the source.
	 * 
	 * The functions are called exactly the same as the originally defined functions, but
	 * instead of giving the actual variable, the name of the variable is passed instead.
	 * 
	 * @example
	 * 
	 * @exception InvalidInputException
	 * @exception MissingInputException
	 * 
	 *
	 */
	static public function __callStatic($name, $arguments) {
		
		/****************************************************************************
		 * 
		 * Developers Note: Any new sanitization routine needs to be the regex rule 
		 * before they can be access through this relay function.
		 * 
		 ****************************************************************************/
		
		if (preg_match('#(Enum|Email|URL|Integer|Boolean|Escape|Float|String|SpecialChars|Decimal)From(Get|Post|Request|Array)#', $name, $match)) {
				
			// This case is for *FromArray type calls.
			// *FromArray calls must have at least 2 parameters, the source array, then the variable name
			// in order to make it compatible with RunSanitizer, we need to shift out the 0th element
			// out of argument (this is the source array).
			if ($match[2] == 'Array') { $source = array_shift($arguments); } 
			
			// the other type of calls are *From(Get|Post|Request) 
			else { $source = self::PickSource($match[2]); }
			
			// finally, run the sanitizer and return the results
			return self::RunSanitizer($match[1], $source, $arguments);
				
		} else { return false; }

	}
	
	
	
	/**
	 * Allows methods in this class to be called in the object context.
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 *
	 */
	public function __call($name, $arguments) { return self::__callStatic($name, $arguments); }



	/**
	 * Picks either $_GET, $_POST, or $_REQUEST depending on given source
	 * 
	 * @param $source
	 * @return array
	 */
	static private function PickSource($source) {
		
		switch( strtolower($source) ) {
			
			case 'get': 	return $_GET; break;
			case 'post': 	return $_POST; break;
			default: 
			case 'request': return $_REQUEST; break;
			
		}
		
	}



	/**
	 * Helper function for __callStatic
	 * @param $mode string One of the sanitazion functions defined above.
	 * @param $mode
	 *
	 */
	static private function RunSanitizer($mode, $source, $arguments) {
	
		//$mode = ucfirst(strtolower($mode));
		$var = $arguments[0];	// grab the variable name. This is the name of 
								// the target variable not its value

		// throw an exception when the variable is not defined.
		if (!isset($source[$var])) throw new MissingInputException( self::FormatMissingErrorMessage($var), 0, $var );

		// replace the variable name, with it's value. Setting it to the 0-element
		// will cause it to be the first parameter when the function is called with
		// call_user_func_array
		$arguments[0] = $source[$var];

		// call the target function
		$return = call_user_func_array(__CLASS__.'::'.$mode, $arguments);

		// determine if the validation worked.
		// boolean requires a special case, as it returns null instead of true|false when
		// it fails.
		if (($mode == 'Boolean' && $return == null) ||
		($mode != 'Boolean' && $return === false)) { 
			
			// prepare a report for the exception handler
			$exceptionReport = array(	'varName' 	=> $var,
										'input'		=> $arguments[0],
										'type'		=> $mode 			);
			if ($mode == 'Enum') { $exceptionReport['valid'] = $arguments[1]; }
			
			throw new InvalidInputException( self::FormatInvalidErrorMessage($var, $mode), 0,  $exceptionReport); }
			 
		else { return $return; }
		
	}
	
	
	
	/**
	 * Format Missing Input Error Message
	 * @param string $varName	Variable Name
	 */
	static private function FormatMissingErrorMessage($varName = false) {
		
		if (empty($varName)) { return "Missing Input"; }
		return "Missing Input: `$varName`";
		
	}
	
	
	/**
	 * Format Invalid Input Error Message
	 * @param mixed $varName		Variable name
	 * @param mixed $expectedType	Expected type
	 */
	static private function FormatInvalidErrorMessage($varName = false, $expectedType = false) {
		
		$varError = ""; $typeError = "";
		
		if ( !empty($varName) ) { $varError = "Invalid input for `$varName`."; }
		else { $varError = "Invalid input."; }
		
		if ( !empty($expectedType) ) { $typeError = "Expecting type `$expectedType`."; }
		
		return $varError . $typeError;
		
	}

}


/**
 * This exception is thrown whenever a variable requested in Sanitize is not valid.
 * @author Juan Caldera
 *
 */
class InvalidInputException extends Exception {
	
	/**
	 * @access private
	 * @var string
	 */
	private $varName ='', $input ='', $type='', $valid='';
	
	/**
	 * Create a new InvalidInputException
	 * @param string $message
	 * @param long $code
	 * @param array $info
	 */
	public function __construct( $message = "", $code = 0, $info = array() ) {
		
		parent::__construct($message, $code);
		
		foreach(array('varName', 'input', 'type', 'valid') as $field) {
			if (isset($info[$field])) { $this->$field = $info[$field]; }
		}
		
	}
	
	/**
	 * Returns the name of the variable that failed validation.
	 * @return string
	 */
	final public function GetVariableName() { return $this->varName; }
	
	/**
	 * Returns the input sent that caused the validation to fail.
	 * @return string
	 */
	final public function GetInput() { return $this->input; }
	
	/**
	 * Returns the validation type
	 * @return string
	 */
	final public function GetExpectedType() { return $this->type; }
	
	/**
	 * For Type: Enum, expected inputs as an array
	 * @return array
	 */
	final public function GetValidInputs() { return $this->valid; }
}

/**
 * This exception is thrown whenever a variable requested in Sanitize is not found.
 * @author Juan Caldera
 *
 */
class MissingInputException extends Exception {
	
	private $varName;
	
	/**
	 * Create a new MissingInputException
	 * @param string $message
	 * @param long $code
	 * @param string $varName
	 */
	public function __construct( $message = "", $code = 0, $varName='') {
		
		parent::__construct( $message, $code );
		
		if (!empty($varName)) { $this->varName = $varName; }
		
	}
	
	
	/**
	 * Returns the name of the variable that failed validation.
	 * @return string
	 */
	final public function GetVariableName() { return $this->varName; }
	
	
}


?>