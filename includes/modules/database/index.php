<?php 

require_once('DatabaseStatic.class.php');

$dbm = new DatabaseManager(array(array('host'=> 'localhost', 'database' => 'genome', 'user'=>'root')));
echo "<pre>".print_r($dbm->FetchAllRowsAssoc('SELECT NOW()'), true)."</pre>"; exit();
?>