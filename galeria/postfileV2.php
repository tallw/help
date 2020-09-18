<?php
/*
 * Written By: Taifun
 * using parts from the "Web2SQL example" from ShivalWolf
 * and parts from the "POST any local file to a php server example" from Scott
 *
 * Date: 2013/Mar/05
 * Contact: info@puravidaapps.com
 *
 * Version 2: 'dirname(__FILE__)' added to avoid problems finding the complete path to the script  
 */

/************************************CONFIG****************************************/

//SETTINGS//
//This code is something you set in the APP so random people cant use it.


/************************************CONFIG****************************************/

//these are just in case setting headers forcing it to always expire
header('Cache-Control: no-cache, must-revalidate');

//if($_GET['p']==$ACCESSKEY){
  // this is the workaround for file_get_contents(...)
  require_once (dirname(__FILE__).'/PHP_Compat-1.6.0a3/Compat/Function/file_get_contents.php');
  $data = php_compat_file_get_contents('php://input');

  $protocolo = $_GET['protocolo'];

  $filename = $protocolo."/#$#".$_GET['filename'];

  if (!file_exists($protocolo)) {                                       
    mkdir($protocolo);
    chmod ($protocolo,0777);                                             
  }
  
  if (file_put_contents($filename,$data)) {
    if (filesize($filename) != 0) {
      echo "ok";
    } else {
      header("HTTP/1.0 400 Bad Request");
      echo "File is empty.";
    }
  } else {
    header("HTTP/1.0 400 Bad Request");
    echo "File transfer failed.";
  }
/*} else {
  header("HTTP/1.0 400 Bad Request");
  echo "Access denied";     //reports if accesskey is wrong
}
*/
?>