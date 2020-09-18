<?php

$user = $_GET['user'];

if (!isset($user)) {
    echo "<script>window.history.back();</script>";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>HELP-ECOS! | Sistema de Apoio às Escolas</title>

  <link rel="stylesheet" href="css/style.css">
</head>

<script type="text/javascript">
  
  function validarcampos() {

    d = document.redefinicao;
   
   // setor (tipo) do produto

   if (d.senha.value == ""){
      alert("Digite a senha");
      d.senha.focus();
      return false;
    }

    if (d.conf_senha.value == ""){
      alert("Digite a senha de confirmacao");
      d.conf_senha.focus();
      return false;
    }

    if (d.conf_senha.value != d.senha.value){
      alert("A senha de confirmacao nao esta igual a senha!");
      d.conf_senha.value = "";
      d.conf_senha.focus();
      return false;
    }
          
  }



</script>

<body>
  <hgroup>
    <a href=""><img src="../image/help_desk-logo.png"/></a>
    <h3>Redefinição de Senha</h3>
    
    <?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo '<p>'.$error.'</p>';
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo '<p>'.$message.'</p>';
        }
    }
}
?>
  </hgroup>
<form method="post" action="salva_red_senha.php" name="redefinicao">
  <div class="group">
    <input type="password" id="senha" class="form-control" name="senha" autocomplete="off" required=""><span class="highlight"></span><span class="bar"></span>
    <label>Senha</label>
  </div>
  <div class="group">
    <input type="password" id="conf_senha" class="form-control" name="conf_senha" autocomplete="off" required=""><span class="highlight"></span><span class="bar"></span>
    <label>Confirmar Senha</label>
  </div>
  <button type="submit" name="red_senha" class="button buttonBlue" onClick="return validarcampos()">Salvar
    <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
  </button>
  <div>
    <input type="hidden" name="user" value="<?php echo $user;?>">
  </div>
  
</form>

<footer>
  <p>© 2018 ECOS PB - Departamento de T.I.</a></p>
</footer>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script  src="js/index.js"></script>

</body>
</html>