<?php
/**
 *
 * LICENSE
 *
 * This source file is subject to the GPL license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @package    LiteFrame
 * @copyright  Copyright (c) 2009 through 2010
 * @license    http://www.gnu.org/licenses/gpl.txt     GPL  
 * 
 * 
 * A class that is treated as Framework
 *  
 * @author     Mahdi Pedramrazi <pedramphp@gmail.com>
 * @author	   Scott Haselton <shaselton@gmail.com>
 * @version    1.0 beta
 * @since       Sep 10th 2009
 *   
 */

class  LiteFrame {
	
	/**
	* yAction is a static var that holds all of the framework settings, variables, and requests that may come into the framework.
	* It may be access anywhere within the framework. It is the only static variable (really, other than the logpath).
	*
	* @access public
	* @var array
	*/
    public  static $yAction;
    
    /**
	* location of where the log files will be stored
	* @access public
	* @var string
	*/
    public  $logFolder          = "log/";
    
    /**
	* location of where the css files will be located
	* @access public
	* @var string
	*/
    public  $cssFolder          = "css/";
    
    /**
	* location of where the javascript files will be located
	* @access public
	* @var string
	*/
    public  $javascriptFolder   = "javascript/";
    
    /**
	* location of where the different modules that can be included
	* @access public
	* @var string
	*/
    public  $javascriptModuleFolder   = "module/";
    
    /**
	* location of where the javascript library files will be located
	* @access public
	* @var string
	*/
    public  $javascriptLibraryFolder   = "library/";
    
    /**
	* location of where the style library files will be located
	* @access public
	* @var string
	*/
    public  $styleLibraryFolder = "library/";
    
    /**
	* location of where the style files will be located
	* @access public
	* @var string
	*/
    public  $styleFolder        = "style/";    
    
    public  $stylePath;
    public  $imagePath;

    /**
	* location of where the include files will be located. This can hold your additional objects, media files, etc.
	* @access public
	* @var string
	*/
    public  $includeFolder      = "includes/";
    
    /**
	* location of where the php action files will be located as part of the MVC.
	* @access public
	* @var string
	*/
    public  $actionFolder       = "actions/";
    
    /**
	* location of where the layout files will be located for the basic parts of the template such as header, footer, and menu systems.
	* @access public
	* @var string
	*/
    public  $layoutFolder       = "layouts/";
    
    /**
	* location of where the includes for your javascript and css will be happening in the html
	* @access public
	* @var string
	*/
    public  $settingsFolder     = "_settings/";
    
    /**
	* location of where the images are going to be located
	* @access public
	* @var string
	*/
    public  $imagesFolder       = "images/";
    
    /**
	* location of where the different template folder will be. It should include the action, includes, layouts, and _settings folders.
	* @access public
	* @var string
	*/
    public  $templateFolder     = "templates/";
    
    /**
	* location of where the compiled templates from smarty will be going.
	* @access public
	* @var string
	*/
    public  $templateCFolder    = "templates_c/";
    
    /**
	* location of where the cached items will be going.
	* @access public
	* @var string
	*/
    public  $templateCacheFolder = "cache/";
    
    /**
	* location of where the configs you might have for your site reside.
	* @access public
	* @var string
	*/
    public  $templateConfigFolder = "configs/";
    
    /**
	* the name of the querystring variable that points to the actions.
	* @example http://www.liteframephp.com/?action=abc
	* @access public
	* @var string
	*/
    public  $actionQuerystring = "action";
    
    /**
	* the location of where smarty is in the system.
	* @access public
	* @var string
	*/
    public  $smartyPath        = '/home/pedramte/phplib/Smarty/Smarty.class.php';    // Set Smarty Path;
    
    /**
	* Name of the template engine folder.
	* @access public
	* @var string
	*/
    public  $templateEngineFolder = '';
    
    /**
	* Template engine flag
	* @access public
	* @var bool
	*/
    public  $templateEngine    = false ;
    
    /**
	* @access public
	* @var ??
	*/
    public  $templateColorName = '';
    
    /**
	* determines if the action has been set before running the application. once verified, the variable will hold the name of the action.
	* @access public
	* @var bool | string
	*/
    public  $action = false;
    
    /**
	* name of the template action. NOTE: can this be removed?!
	* @access public
	* @var bool | string
	*/
    public  $templateAction;
    
    /**
	* name of index template file that will hold the site's header, footer, menu and whatever else is on all pages. Located in templates/layouts/index.tpl.html
	* @access public
	* @var string
	*/
    public  $templateLayoutName = 'index';
    
    /**
	* The type of output you want from your template
	* @access public
	* @var string
	*/
    public  $actionType = 'HTML';
    
    /**
	* The path of the action in dealing with the template engine
	* @access public
	* @var string
	*/
    public  $actionTrigger;
    
    /**
	* The path of the default domain.
	* @access public
	* @var string
	*/
    public  $applicationPath;
    
    /**
	* The real path of the application
	* @access public
	* @var string
	*/
    public  $fileSystemPath;
    
    /**
	* Array of common paths throughout the application
	* @access public
	* @var array
	*/
    public  $fileSystemPaths;
    
    /**
	* Determines if the application variables will be stored and accessable via json.
	* @access public
	* @var bool
	*/
    public  $javaScriptActionInfo = false;
    
    /**
	* Determines if javascript includes are at the bottom of the page rather than top
	* @access public
	* @var bool
	*/
    public  $javascriptBottomPage = false;
    
    /**
	* The application path to the log folder
	* @access public
	* @var string
	*/
    public static $logFilePath;
    
    /**
	* If the application plans to have single javascript and css files that will have the name as the action, set to 'true' so that they are included automatically.
	* @access public
	* @var bool
	*/
    public $RelatedFiles = false;
    
    /**
	* Returns the PHP yAction variable as a JSON var.
	* @access public
	* @var string
	*/
    public $yActionJson;

    public function __construct(){
  		
    	  $this->fileSystemPath = realpath('.') . "/";
          date_default_timezone_set('UTC');
  	
    }  // Construction End	

    /**
	* This function starts the process of the framework to ultimately render the action to the screen.
	* This process requires that the action being set to set the rest of the application paths and process the action.
	*
	* @return null
	*/
    public  function RunApplication(){
    	
		if(!$this->action){ die ("Set the PreAction Before Running The Application");  }
        $this->SetPaths();
       
        $this->UpdateFoldersPaths();
      
		$this->Action();
	
    }

    /**
	* Ultimately this will determine the action to be displayed currently. To do so, we must first set the yAction to determine where we might be going.
	* After we find our action to be displayed, we try to load the php file and then make a few checks on the desired display options for the action.
	* Finally, the action is render via template.
	*
	* @return null
	*/
    public  function Action(){
	                                                                                             
       $this->SetRequestVariables();                  
       $this->SetAction();   
       $this->UpdateyAction(); 
        
      
       $path = $this->fileSystemPath.$this->actionFolder.self::$yAction['_LITE_']['ACTION'].".php";
        
       if(!file_exists ( $path )){ 
       	  header( 'Location: ' . $this->applicationPath.'?action=404' );
       	  //die ("File <b>".$path." </b> <br />Not Found or File doesn't have the permission to open");      
       }
       
       require_once($path);

       	
       $LiteFrameAction = new LiteFrameAction();
       self::$yAction['_LITE_']['VARS']['Request'] = self::$yAction['_LITE_']['REQUEST'];
       $this->liteVars = &self::$yAction['_LITE_']['VARS'];
       // Double checking the Template variables from the Action file
       $this->MergeActionTemplatesWithFramework();
       $this->LoadTemplate();	  

	}

	/**
	* LoadTemplate will deal with template paths. Also, it will load the smarty template engine and sets the defaults.
	* The template will be rendered here.
	*
	* @return null
	*
	*/
  public function LoadTemplate(){

        $this->SetActionTrigger();
        $this->UpdateFoldersPaths();
        if($this->RelatedFiles){  $this->CreateRelatedFiles(); } 
    	  if($this->javaScriptActionInfo){ 
    	  	$this->liteVars['javaScriptActionInfo'] = $this->getJavascriptActionInfo();  
    	  }
        require_once ($this->smartyPath);
		
        self::createUndefinedActions();
		    $smarty = new Smarty;
        $smarty->template_dir = $this->fileSystemPath.$this->templateFolder;
        $smarty->compile_dir  = $this->fileSystemPath.$this->templateCFolder;
        $smarty->cache_dir    = $this->fileSystemPath.$this->templateCacheFolder;
        $smarty->config_dir   = $this->fileSystemPath.$this->templateConfigFolder;
        $smarty->left_delimiter = '<!--{';
        $smarty->right_delimiter = '}-->';
				
        $smarty->assign("ALL_VARS",self::$yAction);
        $smarty->assign(self::$yAction);
        
        $display = $this->GetDisplay();
        if($display == false) return;        
        $smarty->display($display) ;               
		
  }
    
  private function getJavascriptActionInfo(){
    	$jsActionInfo = array(
    											'action' => $this->liteVars['action'],
									    		'stylePath' => $this->liteVars['stylePath'],
									    		'imagePath' => $this->liteVars['imagePath'],
									    		'applicationPath' => $this->liteVars['applicationPath'],
									    		'javascriptPath' => $this->liteVars['javascriptPath'],
									    		'javascriptModulePath' => $this->liteVars['javascriptModulePath'],
									    		'javascriptLibraryPath' => $this->liteVars['javascriptLibraryPath'],
									    		'styleLibraryPath' => $this->liteVars['styleLibraryPath'],
    											'yActionJson' =>  $this->liteVars['yActionJson']
												 );
    	return json_encode( $jsActionInfo );
  }
    
  public function createUndefinedActions(){
    	$arr = array("styleInline",
    				 "styleIncludes",
    				 "javascriptInline",
    				 "javascriptIncludes",
    				 "javascriptLibraryIncludes",
    				 "styleLibraryIncludes");
    	
    	foreach( $arr as $key => $value ){
	    	if(!isset(self::$yAction['_LITE_']['VARS'][$value])){
	    		self::$yAction['_LITE_']['VARS'][$value] = array();
	    	}
    	}
      
  }
    
/****************************************
*           TOOLS   
*****************************************/


	/**
	* Setting all of the default paths of the different folders that are required by the framework.
	*
	* @return null
	*/
	public  function SetPaths(){ 
	   
        $this->logFilePath =  $this->fileSystemPath.$this->logFolder; 
        $this->applicationPath = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']);
        if( substr( $this->applicationPath,strlen($this->applicationPath)-1) != "/" ){
        	$this->applicationPath .= "/";
        }
        $this->fileSystemPaths['basePath']     = $this->fileSystemPath;
        $this->fileSystemPaths['actionPath']   = $this->fileSystemPath . $this->actionFolder;
        $this->fileSystemPaths['templatePath'] = $this->fileSystemPath . $this->templateFolder;           
    
  }

  /**
	* Updates all of the classes variables into the yAction
	*
	* @return null
	*/
  public function UpdateyAction(){
	   
	   $this->UpdateyActionVariables();
       self::SetTemplateAction($this->templateAction);
	   
	}
    
	/**
	* Sets all of the this object's instance to the yAction.
	*
	* @return null
	*/
	public function UpdateyActionVariables(){ self::$yAction['_LITE_']['VARS'] = get_object_vars($this);  }
 
	/**
	* Accessor function for the 'templateAction'
	*
	* @return string $yAction['_LiteFramePHP_']['VARS']['templateAction'] the object's variable template action
	*/
	public  function GetTemplateName(){    return self::$yAction['_LITE_']['VARS']['templateAction'];  }           	

	/**
	* This determines, based on the actiontype, on if the action is an ajax call and the display will be returned or echo'd
	*
	* @return bool | string $display for ajax output or nothing at all
	*/
	public function GetDisplay(){
        
        $display = $this->layoutFolder.$this->templateEngineFolder.$this->templateLayoutName.'.tpl.html';
        switch($this->actionType){
            case "AJAX" : 
              $display = self::$yAction['_LITE_']['VARS']['actionTrigger'];
              break;
              
            case "JSON" : 
            	
              echo json_encode(self::$yAction);
              return false;
              break;
              
               
            case "None":
                return false;
                break;
            
        }
        return $display;  
        
	}

	/**
	* This allows for overriding of the default paths within the framework by the user.
	*
	* @return null
	*/
	public function UpdateFoldersPaths(){
        
        $this->fileSystemPaths['templateActionPath'] = 
        $this->liteVars['fileSystemPaths']['templateActionPath'] = 
        $this->fileSystemPaths['templatePath'].$this->actionFolder.$this->templateEngineFolder;
 
        $this->fileSystemPaths['templateIncludePath'] = 
        $this->liteVars['fileSystemPaths']['templateIncludePath'] = 
        $this->fileSystemPaths['templatePath'].$this->includeFolder.$this->templateEngineFolder;
        
        $this->fileSystemPaths['templateLayoutPath'] = 
        $this->liteVars['fileSystemPaths']['templateLayoutPath'] = 
        $this->fileSystemPaths['templatePath'].$this->layoutFolder.$this->templateEngineFolder;        

        $this->fileSystemPaths['javascriptPath'] =
        $this->liteVars['fileSystemPaths']['javascriptPath'] = $this->fileSystemPath.$this->javascriptFolder.$this->templateEngineFolder;
        
        $this->fileSystemPaths['javascriptModulePath'] =
        $this->liteVars['fileSystemPaths']['javascriptModulePath'] = $this->fileSystemPath.$this->javascriptFolder.$this->templateEngineFolder.$this->javascriptModuleFolder;
        
        
        $this->fileSystemPaths['stylePath'] =
        $this->liteVars['fileSystemPaths']['stylePath'] 	  = $this->fileSystemPath.$this->styleFolder.$this->templateEngineFolder.$this->templateColorName;
        
        $this->liteVars['actionFolder'] = $this->actionFolder.$this->templateEngineFolder;
        $this->liteVars['includeFolder'] = $this->includeFolder.$this->templateEngineFolder;
        $this->liteVars['layoutFolder'] = $this->layoutFolder.$this->templateEngineFolder;
        
        $this->liteVars['stylePath'] = $this->applicationPath.$this->styleFolder.$this->templateEngineFolder.$this->templateColorName;
        $this->liteVars['imagePath'] = $this->applicationPath.$this->styleFolder.$this->templateEngineFolder.$this->templateColorName.$this->imagesFolder;
        $this->liteVars['javascriptPath'] = $this->applicationPath.$this->javascriptFolder.$this->templateEngineFolder;
        $this->liteVars['javascriptModulePath'] = $this->applicationPath.$this->javascriptFolder.$this->templateEngineFolder.$this->javascriptModuleFolder;
        if(!isset($this->liteVars['javascriptLibraryPath'])){
        	$this->liteVars['javascriptLibraryPath'] = $this->applicationPath.$this->javascriptLibraryFolder;
        }
        if(!isset($this->liteVars['styleLibraryPath'])){
        	$this->liteVars['styleLibraryPath'] = $this->applicationPath.$this->styleLibraryFolder;
        }
        $this->liteVars['javaScriptActionInfo'] = $this->javaScriptActionInfo;
        
        $this->fileSystemPaths['settingsPath'] = 
        $this->liteVars['fileSystemPaths']['settingsPath'] = 
        $this->fileSystemPaths['templatePath'].$this->settingsFolder;
		
        if($this->actionType == "INCLUDE_JSON"){
        	$data = self::$yAction; 
        	unset($data['_LITE_']);
        	$this->liteVars['yActionJson']  =  $data;    
				}
        
            
	}
    
	/**
	* Grabbing variables that were set via array to the class' members dealing with actions
	*
	* @return null
	*
	*/
	public function MergeActionTemplatesWithFramework(){
        
         if($this->templateEngine) { $this->SetTemplateEngineFolder();  $this->SetTemplateEngineColorName(); }
         $this->templateLayoutName = $this->liteVars['templateLayoutName'];
         $this->actionType = $this->liteVars['actionType'];
        
	}	

	/**
	* Sets the actionTrigger memberof the object
	*
	* @return null
	*
	*/
	public function SetActionTrigger(){
        
        $this->actionTrigger = 
        $this->liteVars['actionTrigger'] = 
        $this->actionFolder.$this->templateEngineFolder.$this->GetTemplateName().".tpl.html";
 
	}

	/**
	* Setting the action variable from the class' actionQuerystring member
	* Also, the templateAction is set if flagged.
	*
	* @return null
	*/
	public  function SetAction(){     

	   $request = self::$yAction['_LITE_']['REQUEST'];
	   if(  $this->ActionExist() ){
	       
	     $this->action = self::$yAction['_LITE_']['ACTION'] = $request[$this->actionQuerystring];                   
	   
       }else{  self::$yAction['_LITE_']['ACTION'] = $this->action;  }
      
       if(!isset($this->templateAction)){ $this->templateAction = $this->action;  }
       
	}

	/**
	* Helper function to determine if the action exists
	*
	* @return bool return value is based on if the action exists or not. *
	*/
	public function ActionExist(){
        
        $request = self::$yAction['_LITE_']['REQUEST'];
	     return ( isset($request) && 
                  !empty($request) && 
                    array_key_exists($this->actionQuerystring,$request) 
                 ) ? true : false;
                 
    
	}
 
	/**
	* Function to flag the javascriptBottomPage variable to true
	*
	* @return null
	*/
	public function JavascriptBottomPage(){  $this->javascriptBottomPage = true; }
       
	/**
	* if related files are enabled, then the framework locates and trys to load the .css and .js to the corresponding action
	*
	* @return null
	*/
	public function CreateRelatedFiles(){

		$jsFile = $this->fileSystemPaths['javascriptPath'].self::$yAction['_LITE_']['ACTION'].".js";
		if(file_exists($jsFile)){
			$this->liteVars['javascriptIncludes'][] = self::$yAction['_LITE_']['ACTION'].".js"; 
			$this->liteVars['javascriptIncludes'] = array_unique($this->liteVars['javascriptIncludes']);
		}

	    
		$cssFile = $this->fileSystemPaths['stylePath'].self::$yAction['_LITE_']['ACTION'].".css";
		if(file_exists($cssFile)){
			$this->liteVars['styleIncludes'][] = self::$yAction['_LITE_']['ACTION'].".css"; 
			$this->liteVars['styleIncludes'] = array_unique($this->liteVars['styleIncludes']);
		}
	    
	}    
    
 /*****************************************************
*           Funtions being Called by Action file    
******************************************************/     
         
	/**
	* Sets the templateAction variable inside of the yAction variable
	*
	* @param string $templateAction The name of the template's action
	* @return null
	*/
	public static function SetTemplateAction($templateAction){  self::$yAction['_LITE_']['VARS']['templateAction'] = $templateAction;  }

	/**
	* If the template engine is enabled, then this sets the folder's name of the template that is being worked on
	*
	* @param string $templateFolder The name of the folder of the template that is being used.
	* @return null
	*/
	public function SetTemplateFolderName($tempalteFolder){  
   	
    	 if(self::$yAction['_LITE_']['VARS']['templateEngine'] == 1 || $this->templateEngine === true){
          
            $this->templateEngineFolder = self::$yAction['_LITE_']['VARS']['templateEngineFolder'] = $tempalteFolder."/";
            
        }
  }

  
   /**
	* When you are wanting in External javascript files with an action, this static function is called. This function can be called multiple times inside of one action to include multiple javascript files.
	*
	* @param array | string an array of js path
	* @return null
	*/  
    public static function ExternalJavascript(){
    	$args = func_get_args();
    	$includes = (isset(self::$yAction['_LITE_']['VARS']['javascriptExternal'])) 
           ? self::$yAction['_LITE_']['VARS']['javascriptExternal']
           : array();
        if(count($args) == 1 && is_array($args[0])) {  $args = $args[0]; } 
    	self::$yAction['_LITE_']['VARS']['javascriptExternal'] = array_unique(array_merge($includes,$args));  	
    }
  
   /**
	* When you are wanting in include javascript files with an action, this static function is called. This function can be called multiple times inside of one action to include multiple javascript files.
	*
	* @param array | string an array of js files to include that are inside the 'javascript' folder
	* @return null
	*/
	public static function  IncludeJavascript(){
    	
    	self::IncludeFiles(func_get_args(), "javascriptIncludes");

	} 
  
	 /**
	* Same as 'IncludeJavascript()' but the .js file that you are wanting in to include are in the 'jslib' folder. More of a library folder to hold core code.
	*
	* @param array | string an array of js files to include that are inside the 'jslib' folder
	* @return null
	*/
	public static function  IncludeLibraryJavascript(){
        
    	self::IncludeFiles(func_get_args(), "javascriptLibraryIncludes");
        
	}     

	/********** SAME FUNCTIONALITY? *******************/
    /**
	* When you are wanting to include .css files to an action, you call this function
	*
	* @param array | string an array of .css files to include that are inside the 'style' folder
	* @return null
	*/
	public static function  IncludeLibraryStyle(){
    	
    	self::IncludeFiles(func_get_args(), "styleLibraryIncludes");
        
	}    
     
	/********** SAME FUNCTIONALITY? *******************/
    /**
	* When you are wanting to include .css files to an action, you call this function
	*
	* @param array | string an array of .css files to include that are inside the 'style' folder
	* @return null
	*/
	public static function  IncludeStyle(){
			
    	self::IncludeFiles(func_get_args(), "styleIncludes");
        
	}

	/**
	* Helper function that handles all of the including of external files. It merges existing types together so multiple files can be included in one action.
	*
	* @param array | string $args the names/path of the files that are be included.
	* @param string $type identifies the type of file that will be included.
	* @return null
	*/
	private static function IncludeFiles($args, $type){
    	$includes = (isset(self::$yAction['_LITE_']['VARS'][$type])) 
           ? self::$yAction['_LITE_']['VARS'][$type]
           : array();
        if(count($args) == 1 && is_array($args[0])) {  $args = $args[0]; } 
    	self::$yAction['_LITE_']['VARS'][$type] = array_unique(array_merge($includes,$args));
    	
	}
    
	/**
	* If you want to display css code inline during the server processing, you can use this function.
	*
	* @param string $code all of the css code that you would want displayed.
	* @return null
	*/
	public static function InlineStyle($code){  self::$yAction['_LITE_']['VARS']['styleInline'] = $code; }     
    
	/**
	* Extremely helpful function that allows you to set javascript code/vars server-side that your client-side javascript may be initialized off of.
	*
	* @param string $code all of the css code that you would want displayed.
	* @return null
	*/
	public static function InlineJavascript($code){  self::$yAction['_LITE_']['VARS']['javascriptInline'] = $code; } 
    
  public function SetTemplateColorName($tempalteFolder){  
       
        if( (self::$yAction['_LITE_']['VARS']['templateEngine'] == 1 && !empty(self::$yAction['_LITE_']['VARS']['templateEngineFolder']))
           ||	
           ($this->templateEngine === true && !empty($this->templateEngineFolder))
        ){
          
            $this->templateColorName = self::$yAction['_LITE_']['VARS']['templateColorName'] = $tempalteFolder."/";
            
        }
  }

  public function SetTemplateLayoutName($layout){ 

		$this->templateLayoutName = self::$yAction['_LITE_']['VARS']['templateLayoutName'] = $layout; 

	}

	/**
	* This tells the framework that you're expecting AJAX functionality on a particular action. The corresponding template to the action will be handled in an AJAX fashion.
	*
	* @return null
	*/
	public static function AJAXLayout(){ self::$yAction['_LITE_']['VARS']['actionType'] = "AJAX";  }
	
	/**
	* If you want your AJAX action's display to be of the JSON type, call this function within the action file.
	*
	* @return null
	*/
	public static function JSONLayout(){ self::$yAction['_LITE_']['VARS']['actionType'] = "JSON";  }    

	 /**
	* If you have an action that is purely for processing with no display to outputted, call this function in the action file.
	*
	* @return null
	*/
	public static function NoTemplateLayout(){ self::$yAction['_LITE_']['VARS']['actionType'] = "None";  }    
    
	/**
	* Many times we are going to want the PHP template variables to be in JSON. This function allows both PHP template variable to be accessible through normal php or json.
	*
	* @return null
	*/
	public static function IncludeJson(){ self::$yAction['_LITE_']['VARS']['actionType'] = "INCLUDE_JSON";  }  
      
	/**
	* If the template engine is enabled, this function just sets the folder's path.
	*
	* @return null
	*/
	public function SetTemplateEngineFolder(){
      
        $this->templateEngineFolder = self::$yAction['_LITE_']['VARS']['templateEngineFolder'];    
         // echo $this->templateEngineFolder;
	}
    
	/**************    ?? *********************/
	public function SetTemplateEngineColorName(){
        $this->templateColorName = self::$yAction['_LITE_']['VARS']['templateColorName']; 
	}
  
  public static function FetchGetVariable(){  return self::$yAction['_LITE_']['GET']; }
	
  public static function FetchPostVariable(){ return self::$yAction['_LITE_']['POST']; }
	
	public static function FetchRequestVariable(){ return self::$yAction['_LITE_']['REQUEST']; } 
    
  public static function GetFileSystemPath(){ return LiteFrame::$yAction['_LITE_']['VARS']['fileSystemPath']; }
  public static function GetApplicationPath(){ return LiteFrame::$yAction['_LITE_']['VARS']['applicationPath']; }
  public static function GetJavascriptPath(){ return LiteFrame::$yAction['_LITE_']['VARS']['liteVars']['javascriptPath']; }
  public static function GetJavascriptModulePath(){ return LiteFrame::$yAction['_LITE_']['VARS']['liteVars']['javascriptModulePath']; }
  public static function GetStylePath(){ return LiteFrame::$yAction['_LITE_']['VARS']['liteVars']['stylePath']; }
  public static function GetImagePath(){ return LiteFrame::$yAction['_LITE_']['VARS']['liteVars']['imagePath']; }
  public static function GetJavascriptLibraryPath(){ return LiteFrame::$yAction['_LITE_']['VARS']['liteVars']['javascriptLibraryPath']; }
	public static function GetStyleLibraryPath(){ return LiteFrame::$yAction['_LITE_']['VARS']['liteVars']['styleLibraryPath']; }
	public static function GetTemplateIncludePath(){ return LiteFrame::$yAction['_LITE_']['VARS']['fileSystemPaths']['templateIncludePath']; }
	public static function GetTemplateActionPath(){ return LiteFrame::$yAction['_LITE_']['VARS']['fileSystemPaths']['templateActionPath']; }
	public static function GetTemplateLayoutPath(){ return LiteFrame::$yAction['_LITE_']['liteVars']['fileSystemPaths']['templateLayoutPath']; }
	public static function GetTemplateSettingsPath(){ return LiteFrame::$yAction['_LITE_']['VARS']['fileSystemPaths']['settingsPath']; }
	
  public static function InlineJSON($var , $name = ''){
    	
    	$name = empty($name) ? "jsonObj" : $name  ;
    	
    	if(isset(self::$yAction['_LITE_']['VARS']['javascriptInline'])){
    		self::$yAction['_LITE_']['VARS']['javascriptInline'] += " var $name = ". json_encode($var)  . ";";
    	}else{
    		self::$yAction['_LITE_']['VARS']['javascriptInline'] = " var $name = ". json_encode($var)  . ";";
    	}
    	
  }
    
	public function GetVarName($var) {
		
	    foreach($GLOBALS as $var_name => $value) {
	        if ($value === $var) {
	            return $var_name;
	        }
	    }
	
	    return false;
	
	}

/****************************************
*           SET REQUESTS    
*****************************************/  
    public function SetRequestVariables(){
        
        $this->SetPostVars();
        $this->SetGetVars();
        $this->SetRequestVars();
        
    }
    
    public function SetPostVars(){

         self::$yAction['_LITE_']['POST'] = array();   
         if(isset($_POST) && !empty($_POST)){
             foreach($_POST as $parameter => $value){         
                 self::$yAction['_LITE_']['POST'][$parameter] = $this->Sanitize($value);
               }
          }       
            
        
    }

    public function SetGetVars(){

         self::$yAction['_LITE_']['GET'] = array();   
         if(isset($_GET) && !empty($_GET)){
             foreach($_GET as $parameter => $value){         
                 self::$yAction['_LITE_']['GET'][$parameter] = $this->Sanitize($value);
               }
         }       
                  
        
    }

    public function SetRequestVars(){
		
         self::$yAction['_LITE_']['REQUEST'] = array();   
         if(isset($_REQUEST) && !empty($_REQUEST)){
             foreach($_REQUEST as $parameter => $value){         
                 self::$yAction['_LITE_']['REQUEST'][$parameter] = $this->Sanitize($value);
             }
                    
         }
         
    }
    
    public function Sanitize($input){
    	return $input;
        if (is_array($input)){
            foreach($input as $var=>$value){
                $output[$var] = $this->Sanitize($value);
            }
        }else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $output  = $this->CleanInput($input);
            //$output = mysql_real_escape_string($input); 
           }
        return $output;
        
    }

    private function CleanInput($input){
        
    	//$input = urldecode($input);
    	return $input;
    	
  		 /*  	
        $strip = array(
            '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
           '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags 
            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments 
        );
        
        $output = preg_replace($strip, '', $input);
        return $output;
        */

    }


/********************************************
*           FUNCTIONS BEING CALL BY OBJECT    
*********************************************/           	
		public  function SetTemplateEngine($bool){      $this->templateEngine   =  $bool;	}
		
	  public  function SetLogFolder($folder){         $this->logFolder        = $folder;	}
	
    public  function SetJavascriptFolder($folder){  $this->javascriptFolder = $folder;	}
    
    public  function SetJavascriptModuleFolder($folder){  $this->javascriptModuleFolder = $folder;	}
    
    public  function SetJavascriptLibraryFolder($folder){  
    	
    	if(isset(self::$yAction)){
    		
    		self::$yAction['_LITE_']['VARS']['javascriptLibraryFolder'] = $folder."/";
    		self::$yAction['_LITE_']['VARS']['javascriptLibraryPath'] = self::$yAction['_LITE_']['VARS']['applicationPath'].$folder."/";
    	
    	}else{
    		
    		$this->javascriptLibraryFolder = $folder.'/';
    	
    	}
    	
    }
    
		public  function SetStyleLibraryFolder($folder){  
    	if(isset(self::$yAction)){
    		
    		self::$yAction['_LITE_']['VARS']['styleLibraryFolder'] = $folder."/";
    		self::$yAction['_LITE_']['VARS']['styleLibraryPath'] = self::$yAction['_LITE_']['VARS']['applicationPath'].$folder."/";
    	
    	}else{
    		
    		$this->styleLibraryFolder = $folder.'/';
    	
    	}
    	
    }    
  	
    public  function SetIncludeFolder($folder){     $this->includeFolder    = $folder;	}
	
		public  function SetActionFolder($folder){      $this->actionFolder     = $folder;	}
	  
    public  function SetImagesFolder($folder){      $this->imagesFolder     = $folder;	}
		
    public  function SetTemplateFolder($folder){    $this->templateFolder   = $folder;	}
		
    public  function SetTemplateCFolder($folder){   $this->templateCFolder  = $status;	}
	  
    public  function SetSmartyPath($folder){        $this->smartyPath  = $folder;	}
	  
    public  function SetPreAction($action){  $this->action = $action;  }   
    
    public function SetPreActionWithTemplate($action,$template){
        
         $this->action = $action;
         $this->templateAction = $template; 
    
    }
    
    public function JavaScriptActionInfo(){ $this->javaScriptActionInfo = true; }
    
    public function SmartyPath($path){ $this->smartyPath = $path;  }
    
    public function AddingRelatedFilesToDOM(){ $this->RelatedFiles = true; }
    
	public function setFileSystemPath( $path ){ $this->fileSystemPath = $path; }
/****************************************
*           WRITE LOG    
*****************************************/  
    
	
	public  static function WriteLog( $data , $label = "" ){
		
	   $labelStart = (empty($label)) ? "" : "( ".$label." Starts )";
	   $labelEnd   = (empty($label)) ? "" : "( ".$label." Ends )";
	   $date = gmdate( "l jS \of F Y h:i:s A");
	   
	   $file = fopen(self::$yAction['_LITE_']['VARS']['logFilePath']."debug.log", "a+");
	   fwrite($file, "\n\n /************************$labelStart****************************/ \n\n");
	   fwrite($file,$date."\n\n\n");
	   fwrite($file, print_r($data,true));
	   fwrite($file, "\n\n /************************$labelEnd****************************/ \n\n");
	   fclose($file);
	   if($file == false) die("unable to create / Write file");
	   
	}    
	
	
	public static function BuildActionUrl( $action = '' ){
		
		if( empty( $action) ) {
			
			$action = self::GetAction();

		}
		return self::GetApplicationPath() . '?action=' . $action;
		
	}

	public static function GetAction(){ return self::$yAction['_LITE_']['ACTION']; }
  
 	public function IsAjax() {
		
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
	}
	
}  // Class Ends


?>
