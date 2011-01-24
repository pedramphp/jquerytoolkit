<?php

/** 
 * @category	Modules
 * @package 	Database
 * @subpackage 	DatabaseRecord
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 19, 2010 8:36:49 AM
 * @copyright 	Vortal Group
 *
 */

/**
 * Database Manager
 */
require_once('DatabaseManager.class.php');

/**
 * Database Record
 */
require_once('DatabaseRecord.class.php');


/**
 * This class extends DatabaseRecordLoader which instead of creating DatabaseRecord.
 * 
 * This loader creates ExtendedRecord instead of DatabaseRecord. [ExtendedRecord in turn
 * an extension of DatabaseRecord]
 * 
 * This has the exact same functionalities as DatabaseRecordLoader. 
 * 
 * It specifically uses DatabaseManager as it's database handler.
 * 
 * @see 	ExtendedRecord
 * @author 	Juan Caldera
 * @package Database
 *
 */
class ExtendedRecordLoader extends DatabaseRecordLoader {

	/**
	 * Creates a new ExtendedRecordLoader
	 *
	 * @param DatabaseInterface 	$dbManager		Database Connection Manager
	 * @param string 				$preferred		Database name
	 */
	public function __construct($dbName = 'genome', DatabaseInterface $dbManager = null) {

		if (empty($dbManager)) { $dbManager = new DatabaseManager(); }
		//$dbManager->SetDebug(2);
		parent::__construct($dbManager, $dbName, 'ExtendedRecord');
		
	}
	
	
	
	/**
	 * Returns the database manager
	 *
	 * <code><?php
	 * $loader->GetDatabaseManager
	 * ?></code>
	 * @return DatabaseManager
	 */
	public function GetDatabaseManager() { return $this->dbManager; }

}



/**
 * Specialized extension of DatabaseRecord.
 * 
 * This automatically sets the Entry_Date field as well as Timestamp field of a given table.
 * 
 * Because of this, it is requires that those fields must exist in any table whose records
 * are represented by this table.
 * 
 * All other functionality of DatabaseRecord is preserved
 * @see DatabaseRecord
 * @see ExtendedRecordLoader
 * 
 * @author Juan Caldera
 * @package Database
 *
 */
abstract class ExtendedRecord extends DatabaseRecord {

	private $entryDateField = 'Entry_Date';			// Field that should contain record's creation date
	private $timeStampField = 'TimeStamp';


	/**
	 * Save the record into the database. This adds functionality to the save routine such that
	 * it saves the current date to field specified in $entryDateField
	 *
	 * @param	boolean		$allowOverwrite		If the record is new, and it's primary keys are already
	 * 											owned by a different record in the database
	 * 											This determines whether or not the new record would overrite
	 * 											that record.
	 * @return	boolean							Returns true on success, false otherwise
	 *
	 */
	public function Save($allowOverwrite = false) {
		
		if ($this->IsNew() && $this->GetSchemaInfo($this->entryDateField) !== false) { $this->{$this->entryDateField} = date("Y-m-d H:i:s");} 
		$this->{$this->timeStampField} = date("Y-m-d H:i:s");
		
		return parent::Save($allowOverwrite);

	}
	
}



?>