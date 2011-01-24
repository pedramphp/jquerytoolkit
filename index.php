<?php
  //error_reporting(E_ALL);
  //ini_set('display_errors', '1');
  $realpath = realpath('.')."/";
  require_once($realpath."LiteFrame/index.php");    
  $LiteFrame =  new LiteFrame();
  
  // if set to false, you will be unable to set folder paths to get specific templates.
 	$LiteFrame->SetTemplateEngine(true);

  // There is always going to be an action that the framework is going to be pointing to.
  $LiteFrame->SetPreAction("homepage");  // Set your default action here for the beginning of your project!
    
  // $LiteFrame->SetPreActionWithTemplate("homepage","homepage2");
  $LiteFrame->SmartyPath($realpath."includes/modules/smarty/libs/Smarty.class.php");

  // Adding JS / CSS files with the same Action name to the DOM if they exist
  $LiteFrame->AddingRelatedFilesToDOM();
    
    
  $LiteFrame->SetJavascriptLibraryFolder("jslib");
  $LiteFrame->SetStyleLibraryFolder("jslib");
  
  // if you want all your javascript on the bottom of the page 
  $LiteFrame->JavascriptBottomPage();
  
  // Grab all $yAction and make it to a json obj
  $LiteFrame->JavaScriptActionInfo();  
  
  $LiteFrame->RunApplication();
    
  


?>
