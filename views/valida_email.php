<?php 

$login = $_GET["login"];

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$sql_1 = "SELECT * FROM users WHERE user_name = '{$login}'";
$sql_2 = "SELECT * FROM sede WHERE user_sede = '{$login}'";

$result_1 = $link->query($sql_1) or die(mysqli_error($link));
$result_2 = $link->query($sql_2) or die(mysqli_error($link));
mysqli_close($link);

if (mysqli_num_rows($result_1) == 0 && mysqli_num_rows($result_2) == 0) {
	echo "<script>alert('Login nao cadastrado no sistema, entre em contato com o suporte da Ecos...');</script>";
	echo "<script>window.history.back();</script>";
}else{

	if (mysqli_num_rows($result_1) > 0) {
		$linha = mysqli_fetch_object($result_1);
		$email = $linha->user_email;
	}else{
		$linha = mysqli_fetch_object($result_2);
		$email = $linha->sede_email;
	}

	
	//$pedaco = spliti("@", $email)[0];
	echo "<script> if(confirm('Confirma envio de um Link de recuperacao de senha para o E-mail: ".$email."...?')) { location.href= 'envia_email.php?login=".$login."' ;}else{window.history.back();}</script>";
}
	
?>