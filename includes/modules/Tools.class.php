<?php 

class Tools {
    

    public static $totalTime;
    public static $endTime;
    public static $startTime; 
    
    public function PrintArray($arr){
        
        echo("<pre>".print_r($arr,true)."</pre>");
        
    }
    
    // funciton RoundUp takes the value that is passed in and rounds up to the nearest decimal point that is the second parameter
    public function RoundUp($value, $nearest){
    	
    	return ceil(intval($value)/$nearest)*$nearest;
    	
    }
    
    // funciton RoundDown takes the value that is passed in and rounds down to the nearest decimal point that is the second parameter
    public function RoundDown($value, $nearest){
    	
    	return floor(intval($value)/$nearest)*$nearest;
    	
    }
    

    
    
    public function SecurePage($value){

        $value = htmlspecialchars(trim($value));
        if (get_magic_quotes_gpc()) $value = stripslashes($value);
        return $value;

    }
    
    
    
    public function SecureMysql($value){

        if (get_magic_quotes_gpc()) $value = stripslashes($value);
     //   $value = mysql_real_escape_string($value);
        return $value;

    }
    
    
    
    public function Is_Odd( $int ){
        
      return $int & 1 ;
    
    }

     public function Is_Even( $int ){
        
      return !($int & 1 );
    
    }   
    
    
   
/**
* Remove Duplicates
*     remove the Duplicate from an Array 
* @param mixed $Array
*/
    public function removeDuplicates($Array){   
        $length = count($Array) -1;
        foreach($Array as $key => $value ){
            
          if( $key != 0       && 
              $key != $length && 
              $this->Is_Even($key) ){     unset($Array[$key]);   }   
        
        }
       return $Array;
    }  // remove Duplicates      
    
    
    
    public function array_search_all($Array,$Value){
      $keys = array();
      foreach($Array AS $ArrayKey => $ArrayValue ){
          if( $Value == $ArrayValue ){
              array_push($keys,$ArrayKey);
          }
      }
      return $keys;
    }
    
    
    
    public function ReflectClass($ClassName){

        $reflect  =  new ReflectionClass($ClassName);

        echo "<br />Printing all methods in the '$ClassName' class:<br />";
        echo "============================================<br />";

        foreach($reflect->getMethods() as $reflectmethod) {
            echo "  {$reflectmethod->getName()}()<br />";
            echo "  ", str_repeat("-", strlen($reflectmethod->getName()) + 2), "<br />";

            foreach($reflectmethod->getParameters() as $num => $param) {
                echo "    Param $num: \$", $param->getName(), "<br />";
                echo "      Passed by reference: ", (int)$param->isPassedByReference(), "<br />";
                echo "      Can be null: ", (int)$param->allowsNull(), "<br />";

                echo "      Class type: ";
                if ($param->getClass()) {
                    echo $param->getClass()->getName();
                } else {
                    echo "N/A";
                }
                echo "<br /><br />";
            }
        }
        
       
    }//Reflect Class Ends
    
    
    
    public function RemoveAllDuplicates($Array,$Label){
    
      $Rules = array();
      foreach($Array as $key => $item ){
        
        $SearchKey = array_search($item[$Label], $Rules);
        if($SearchKey !== FALSE  ){
           unset($Array[$key]);
           unset($Array[$SearchKey]);
        }else{ 
            $Rules[] = $item[$Label]; 
        }  
      }
      return $Array;
    }
    
    
    
    public function RemoveArrayByFieldValue($array,$field,$value){
        
        foreach($array as $key => $item){ 
            if($item[$field] == $value) { unset($array[$key]);  }
        }
        return $array;
                
    }
    
    
    public function FilterArrayByFieldAndFetch($array,$field){
        $newArray = array();
        foreach($array as $key => $item){
            array_push( $newArray , $item[$field]);
        }
        return $newArray;
    }
    
    
    
    public function GetAllDuplicates($Array,$Label){
         $Rules = $Duplicates = array();
         foreach($Array as $key => $item ){
           $SearchKey = array_search($item[$Label], $Rules);
           if($SearchKey !== FALSE  ){
             $Duplicates[] = $Array[$key];
             $Duplicates[] = $Array[$SearchKey] ;
           }else{ $Rules[] = $item[$Label]; }
          }
         return $Duplicates;
   }
       
       
   public function PrependToArray($array,$value){
	   
	   $array = array_reverse( $array, true );
       $array[0] = $value; 
       $array = array_reverse( $array, true );	 
	   return $array;
	   
   }   
   
   
   
	public function get_time_difference($start,$end ){
		
	    $uts['start']      =    strtotime( $start );
	    $uts['end']        =    strtotime( $end );
	    if( $uts['start'] !== -1 && $uts['end'] !== -1 ){
	    	
	    	
	        if( $uts['end'] >= $uts['start'] ){
				
	            $diff    =     $uts['end'] - $uts['start'];
	            if( $days    = intval((floor($diff/(60*60*24)))) )   $diff = $diff % 86400;
	            if( $hours   = intval((floor($diff/(60*60))))    )   $diff = $diff % 3600;
	            if( $minutes = intval((floor($diff/60)))         )   $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            $Array =  array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff);
	            return($Array);
	        }
	        else{  
	            trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
	        }
	    }
	    else{
	        trigger_error( "Invalid date/time data detected", E_USER_WARNING );
	    }
	    return false ;
	}     


	public function StartTimer(){
		
	  $mtime = microtime(); 
      $mtime = explode(' ', $mtime); 
      $mtime = $mtime[1] + $mtime[0]; 
      self::$startTime = $mtime; 
		
	}
	
	
	public function EndTimer(){
		
      $mtime = microtime(); 
      $mtime = explode(" ", $mtime); 
      $mtime = $mtime[1] + $mtime[0]; 
      self::$endTime = $mtime; 
      self::$totalTime = (self::$endTime - self::$startTime); 
      		
	}  
    
    
    
    
    public function cleanString($string) {
        
        $detagged = strip_tags($string);
        if(get_magic_quotes_gpc()) {
            $stripped = stripslashes($detagged);
            $escaped = mysql_real_escape_string($stripped);
        } else {
            $escaped = mysql_real_escape_string($detagged);
        }
        return $escaped;
    
    }
    
    
    
	function isAjax() {
	   return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
	}
	
	
    public function RedirectOutside($url){
    	
    	ob_start();
    	header( "Location: {$url}" ) ;
    	ob_end_flush();
    	exit();
    	
    }
    
           
        
}


if ( !function_exists( 'lcfirst' ) ) { 
	
	function lcfirst( $string ) {
		
		$string{0} = strtolower( $string{0} );
		return $string;
		
	}
	
}

?>