<?php

//echo "<script>alert('aqui')</script>";

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$user = $_POST['user'];
$nova_senha = $_POST['senha'];

$hash_nova_senha = password_hash($nova_senha, PASSWORD_DEFAULT);






if (is_numeric($user)) {
	$query = "UPDATE `users` SET `user_password_hash`='$hash_nova_senha' WHERE user_name = '{$user}'";
}else{
	$query = "UPDATE `sede` SET `user_password_hash`='$hash_nova_senha' WHERE user_sede = '{$user}'";
}


$result = $link->query($query) or die(mysqli_error($link));
mysqli_close($link);

if ($result == True) {
	echo "<script>alert('Sua nova senha foi cadastrada com sucesso...');</script>";
	echo "<script>window.history.back();</script>";
}else{
	echo "<script>alert('Erro ao cadastrar a nova senha, entre em contato com o suporte da Ecos...');</script>";
	echo "<script>window.history.back();</script>";
}

?>