<?php


if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];
$id_os = $_GET['id_os'];

if(isset($_SESSION['user_name']) && !is_numeric($_SESSION['user_name'])){

	$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
    $link->set_charset('utf8');

    if (!$link){

    	die('Connect Error (' . mysqli_connecterrno() . ')' .mysqli_connect_error());

    }else{

    	$query_update_os = "UPDATE `ordem_servico` SET `status`='2' WHERE `id_os` = '$id_os'";
        $result_update_os = $link->query($query_update_os) or die(  mysqli_error( $link ) );

        header("location: setor_ver_os.php?id_os=".$id_os);

    exit();
   		
    }


}else{

    header("location: ../index.php");
    exit();

}




?>