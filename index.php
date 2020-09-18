<?php

/**
 * 
 * @author David Félix
 * @link https://github.com/David-Felix
 * 
 */

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    
    require_once("libraries/password_compatibility_library.php");
}

// include the configs
require_once("config/db.php");

// load the login class
require_once("classes/Login.php");

$login = new Login();


if ($login->isUserLoggedIn() == true) {
  
    //include("views/logged_in.php");
    //echo "<script>alert('Usuario: $usuario');</script>";

    $usuario = $_SESSION['user_name'];

    if (is_numeric($usuario)) {
    	include("school_dashboard.php");
    }else{
    	include("setor_dashboard.php");
    }

    

} else {
    
    include("views/not_logged_in.php");
}
