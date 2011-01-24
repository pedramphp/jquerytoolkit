<?php

/** 
 * @category	Modules
 * @package 	Database
 * @subpackage 	Examples
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		May 19, 2010 8:36:49 AM
 * @copyright 	Vortal Group
 *
 */





/**
 * This is the default configured used by DatabaseManager and consequently, DatabaseStatic.
 * 
 * If you want database static to be initialized with a different configured, define 
 * 'DATABASESTATIC_NOAUTOLOAD' before you include the DatabaseStatic file.
 *
 * Then Call 'DatabaseStatic::Iniitialize()'. This function supports all parameters 
 * supported by DatabaseManager's constructor.
 * 
 * If you need to have a custom Database Connection Do this
 * this is going to overload the current configuration
 *
 * define('DATABASESTATIC_NOAUTOLOAD', 1);
 * $config =  array(
 * 
 *				 array( "host" => "localhost" ,
 *				   		"user"   => "root" ,
 *				  		"pass"  => "" ,
 *				   		"database" => "book"
 *				 ),
 *				
 *				 array( "host" => "localhost" ,
 *				   		"user"   => "root" ,
 *				  		"pass"  => "" ,
 *				   		"database" => "book"
 *				 )
 *
 *	);
 *	DatabaseStatic::Initialize($config);
 *
 *
 *
 *
 * This class is used to bridge an existing definitions file with the database manager.
 *
 * This file can be used to load any database info array and load it through the database manager.
 * The function Load must simply return an array in a compatible format.
 *
 * Then, use the file (must be .php)'s path as parameter when instantiating the databasemanager object.
 *
 * @package 	Database
 * @subpackage 	DefLoader
 * @category	backend
 * @author 		Juan Caldera
 * @version 	1.0
 * @since 		Apr 21, 2010 10:04:05 AM
 * @copyright 	Vortal Group
 *
 */





/**
 * Default Configuration AuthoL
 * @var unknown_type
 */


/**
 * Genome Database server configuration file.
 * This is specific to genome and has nothing to do with the database package.
 */




class DatabaseConfig {

	/**
	 * 
	 * 
	 * This is the loader function called by DatabaseManager internally.
	 * The information retured by this function should be the server information
	 * neccessary to connect to your database.
	 * 
	 * The goal of the function is to return an array of arrays as follows:
	 * 
	 * <code><?php
	 * 
	 * 			return  array(
	 *						array( 	"host"		=> "localhost" , 
	 *							 	"user"   		=> "root" , 
	 *							 	"pass"  		=> "" ,
	 *							 	"database" 		=> "book"
	 *						) 
	 *					);
	 *
	 * ?></code>
	 * 
	 * 
	 * <code>
	 * 
	 *  		return parse_ini_file('database.config.ini',true);
	 * 
	 * </code>
	 * 
	 * 
	 * HINT: You can also load your data from an INI file using php's parse_ini_file function
	 * 
	 * <code>
	 * ; this is an INI file
	 * 
	 * [database1]
	 * 		host=127.0.0.1
	 * 		user=root
	 * 		pass=
	 * 		database=book
	 * 		type=master
	 * [database2]
	 * 		host=127.0.0.2
	 * 		user=root
	 * 		pass=
	 * 		database=book
	 * 		type=slave
	 * </code>
	 * 
	 * Using parse_ini_file on this file would automatically yield the correctly formatted
	 * array of arrays.
	 * 
	 * @return array
	 */
	static public function Load() {
	
		
/*	
		return parse_ini_file('database.config.ini',true);
    	
    	    OR */
        return  array(
                     array(     "host"          => "localhost" , 
                                "user"          => "buttonmaker" , 
                                "pass"          => "Khazokhil1" ,
                                "database"      => "buttonmaker"
                     ) 
        );

	        
	}


}


?>

