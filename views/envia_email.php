<?php 

$login = $_GET['login'];

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

if (is_numeric($login)) {
	$sql = "SELECT * FROM users WHERE user_name = '{$login}'"; 
}else{
	$sql = "SELECT * FROM sede WHERE user_sede = '{$login}'"; 
}

$result = $link->query($sql) or die(mysqli_error($link));
mysqli_close($link);

$linha =mysqli_fetch_object($result);

if (is_numeric($login)) {
	$email = $linha->user_email;
}else{
	$email = $linha->sede_email;
}

$link = "www.ecospb.com.br/helpecos/views/redefinicao_senha.php?user=".$login;


$txtNome	= 'ECOS Helpdesk...';
$txtAssunto	= 'Envio de recuperacao de senha para acesso ao sistema de Helpdesk...';
$txtEmail	= $email;
$txtMensagem = 'Clique nesse link para redefinir a senha referente ao Login: '.$login.': '.$link;

/* Montar o corpo do email*/
$corpoMensagem 		= "<b>Remetente: </b> ".$txtNome." <br><b>Assunto: </b> ".$txtAssunto."<br><b>Mensagem: </b> ".$txtMensagem;

/* Extender a classe do phpmailer para envio do email*/
require_once("phpmailer/class.phpmailer.php");

/* Definir Usuário e Senha do Gmail de onde partirá os emails*/
define('GUSER', 'ecos.avisos'); 
define('GPWD', 'EcosAvisos1');

function smtpmailer($para, $de, $nomeDestinatario, $assunto, $corpo) { 
	global $error;
	$mail = new PHPMailer();
	/* Montando o Email*/
	$mail->IsSMTP();		    /* Ativar SMTP*/
	$mail->SMTPDebug = 0;		/* Debugar: 1 = erros e mensagens, 2 = mensagens apenas*/
	$mail->SMTPAuth = true;		/* Autenticação ativada	*/
	$mail->SMTPSecure = 'tls';	/* TLS REQUERIDO pelo GMail*/
	$mail->Host = 'smtp.gmail.com';	/* SMTP utilizado*/
	$mail->Port = 587;  		   /* A porta 587 deverá estar aberta em seu servidor*/
	$mail->Username = GUSER;
	$mail->Password = GPWD;
	$mail->SetFrom($de, $nomeDestinatario);
	$mail->Subject = $assunto;
	$mail->Body = $corpo;
	$mail->AddAddress($para);
	$mail->IsHTML(true);

	/* Função Responsável por Enviar o Email*/
	if(!$mail->Send()) {
		$error = "<font color='red'><b>Mail error: </b></font>".$mail->ErrorInfo; 
		return false;
	} else {
		$error = "<font color='blue'><b>Mensagem enviada com Sucesso!</b></font>";
		return true;
	}
}

/* Passagem dos parametros: email do Destinatário, email do remetende, nome do remetente, assunto, mensagem do email.*/
if (smtpmailer($txtEmail, 'ecos.avisos@gmail.com', $txtNome, $txtAssunto, $corpoMensagem)) {
 	//echo "<script>window.history.back();window.history.back();</script>";
	 Header("location: sucesso.php"); // Redireciona para uma página de Sucesso.
}
if (!empty($error)) echo $error;

?>