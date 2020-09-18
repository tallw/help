<?php

if(!isset($_SESSION)){ 

    session_start(); 
}

$user = $_SESSION['user_name'];
$user_email = $_POST['email_user'];
$user_password = $_POST['user_password'];

$options = ['cost' => 10,];

$senha_segura = password_hash($user_password, PASSWORD_DEFAULT, $options);


if(isset($user)){



	if (isset($user_email) && isset($user_password)) {

		if (is_numeric($user)) {
			$sql = "UPDATE `users` SET `user_password_hash` = '{$senha_segura}', `user_email` = '{$user_email}' WHERE `user_name` = '{$user}'";
		}else{
			$sql = "UPDATE `sede` SET `user_password_hash`='{$senha_segura}',`sede_email`='{$user_email}' WHERE `user_sede` = '{$user}'";
		}

		require_once("config/db.php");
		$strcon = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die('Erro ao conectar ao banco de dados');
		//$sql = "UPDATE `users` SET `user_password_hash` = '{$senha_segura}', `user_email` = '{$user_email}' WHERE `user_name` = '{$user}'";

		if( $strcon->query($sql) ){ 

			mysqli_close($strcon);
			echo "<script>alert('Usuario editado com sucesso...');</script>";
			echo "<script>window.history.back();window.history.back();</script>";

		}else{

			mysqli_close($strcon);
			echo "<script>alert('Erro ao tentar editar usuario...');</script>";
			//echo "<script>window.history.back();</script>";
		}	

	}

}else{

    header("location: ./index.php");
    exit();

}

?>