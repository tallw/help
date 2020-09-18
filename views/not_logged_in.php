<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>HELP-ECOS | Sistema de Apoio às Escolas</title>

  <link rel="stylesheet" href="views/css/style.css">

</head>

<script type="text/javascript">
  
  function ChamarLink() {

    var login = document.getElementById("login_input_username").value; 
          
    if((login.length < 1) || (login.split(" ").length > 1)){
      alert("Login Inválido...");
    }else{
      location.href = "views/valida_email.php?login=" + login;
    }
          
  }



</script>

<body>
  <center><a href=""><img height="100" width="400" src="./image/help_logo.png"/></a></center>
  <hgroup>
    
    <h3>HELP-ECOS Sistema de Apoio às Escolas</h3>
    
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
<form method="post" action="index.php" name="loginform">
  <div class="group">
    <input type="text" id="login_input_username" class="form-control" name="user_name" required=""><span class="highlight"></span><span class="bar"></span>
    <label>Usuário</label>
  </div>
  <div class="group">
    <input type="password" id="login_input_password" class="form-control" name="user_password" autocomplete="off" required=""><span class="highlight"></span><span class="bar"></span>
    <label>Senha</label>
  </div>
  <button type="submit" name="login" class="button buttonBlue">Entrar
    <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
  </button>

  <div>
  <input class="forget" type="button" onclick="ChamarLink()" value="Esqueceu sua senha?">
</div>
</form>



<footer>
  <p>© 2018 ECOS PB - Departamento de T.I.</a></p>
</footer>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script  src="views/js/index.js"></script>

</body>
</html>
