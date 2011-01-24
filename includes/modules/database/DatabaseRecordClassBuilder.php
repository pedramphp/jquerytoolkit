<?php 

/** 
 * @category	Modules
 * @package 	Database
 * @subpackage 	DatabaseRecord
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 24, 2010 10:59:38 AM
 * @copyright 	Vortal Group
 *
 */

/**
 * SQL Helper Package
 */
require_once('SQLHelper.class.php');

/**
 * @package 	Database
 * @subpackage 	DatabaseRecord
 * @category	backend
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 5, 2010 9:38:46 AM
 * @copyright 	Vortal Group
 *
 */
class DatabaseRecordClassBuilder {

	/**
	 * Database Interface object
	 * @var DatabaseInterface
	 */
	private $db;

	/**
	 * Base DatabaseRecordClass to extend from
	 * @var string
	 */
	private $baseClass;

	/**
	 * Used in class naming to minimize possibility of collisions
	 * @var string
	 */
	private $prefix, $suffix, $infix;


	/**
	 * Database and table names, these are set and used by DatabaseRecordClassBuilder::Build()
	 * @var string
	 */
	private $table, $database;

	/**
	 * Contains the schema of the currently being processed class
	 * @var array
	 */
	private $schema;


	/**
	 * Create a new instance of DatabaseRecordClassBuilder
	 * @param DatabaseInterface $db
	 */
	public function __construct(DatabaseInterface $db, $baseClass, array $fixes = array()) {

		$this->db = $db;
		$this->baseClass = $baseClass;

		$this->prefix = !empty($fixes['prefix']) ? $fixes['prefix'] : '';
		$this->infix = !empty($fixes['infix']) ? $fixes['infix'] : '';
		$this->suffix = !empty($fixes['suffix']) ? $fixes['suffix'] : '';

	}




	/**
	 * Generate the class PHP code, that encapsulates the data structure of a given table
	 *
	 * @param string $database 	Database name
	 * @param string $table 	Table Name
	 * @return string
	 */
	public function Build($database, $table) {

		$this->table = $table;
		$this->database = $database;

		$className = $this->GenerateClassName();
		if (class_exists($className)) { return $className; }
		
		$query = SQLHelper::Describe(array('database'=>$this->database, 'table'=>$this->table));
		$this->schema = $this->db->FetchAllRowsAssoc($query);

		$class = sprintf(	"class %s extends %s { \n%s \n%s%s }",
							$className,
							$this->baseClass,
							$this->GenerateMembers(),
							$this->GenerateSchemaLoader(),
							$this->GenerateMagicFunctions());

		eval($class);
		return $className;

	}
	
	
	
	private function GenerateMagicFunctions() {
		
		return '
			public function __set($name, $value) { $name = $this->GetCorrectFieldNameCase($name); $this->$name = $value; }
			public function __get($name) { $name = $this->GetCorrectFieldNameCase($name);  return $this->$name;	}
		';
	}



	/**
	 * Generate the function LoadSchema required by DatabaseRecord
	 *
	 * @return string
	 */
	private function GenerateSchemaLoader() { return sprintf("\n\tprotected function LoadSchema() { \n\t\treturn %s; }", var_export($this->schema, true)); }


	private function GenerateMembers() {

		$members = '';
		foreach($this->schema as $schema) { $members .= sprintf("\tpublic \$%s;\n", $schema['Field']); }
		return $members;
	}



	/**
	 * Generate a class name for a database/table combo
	 * @access private
	 * @return string
	 */
	private function GenerateClassName() {

		return sprintf('%s%s%s%s%s',
		$this->prefix,
		$this->database,
		$this->infix,
		$this->table,
		$this->suffix);

	}

}

?>