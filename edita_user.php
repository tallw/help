<?php

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];



if(isset($usuario)){

?>


<!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>HELP-ECOS | Sistema de Apoio às Escolas</title>
    	
    	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    	<link rel="stylesheet" href="assets/materialize/css/materialize.min.css" media="screen,projection" />
        <!-- Bootstrap Styles-->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FontAwesome Styles-->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- Morris Chart Styles-->
        <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- Custom Styles-->
        <link href="assets/css/custom-styles.css" rel="stylesheet" />
        <!-- Google Fonts-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <link rel="stylesheet" href="assets/js/Lightweight-Chart/cssCharts.css"> 

        <!-- ################################ DA TIMELINE ################################# -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css" />
        <link rel="stylesheet" href="dist/vertical-timeline.css">
        <!-- ################################################################################ -->

        <script type="text/javascript">
            
            function validarcampos(){

                d = document.edita_user;

                if (d.email_user.value == ''){
                    alert("Digite o E-mail para recuperacao de senha");
                    d.email_user.focus();
                    return false;
                }

                if (!validacaoEmail(d.email_user)){
                    alert("E-mail invalido!");
                    d.email_user.focus();
                    return false;
                }

                if (d.user_password.value == ''){
                    alert("Digite a nova senha!");
                    d.user_password.focus();
                    return false;
                }

                if (d.user_password.value.length < 6){
                    alert("A senha precisa ter no minimo 6 caracteres!");
                    d.user_password.focus();
                    return false;
                }

            }

            function validacaoEmail(field) {
                usuario = field.value.substring(0, field.value.indexOf("@"));
                dominio = field.value.substring(field.value.indexOf("@")+ 1, field.value.length);
 
                if ((usuario.length >=1) && (dominio.length >=3) && (usuario.search("@")==-1) && (dominio.search("@")==-1) && (usuario.search(" ")==-1) && (dominio.search(" ")==-1) && (dominio.search(".")!=-1) && (dominio.indexOf(".") >=1) && (dominio.lastIndexOf(".") < dominio.length - 1)) {
                    return true;
                }else{
                    return false;
                }
            }


        </script>
    </head>
    <body>
        <?php include("setor_menu.php"); ?>
       
            <!-- /. NAV SIDE  -->

            <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        Alterar Senha
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você pode alterar sua senha.</li>
                    </ol> 
                                        
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <center><div class="card" style="width: 600px">
                        <div class="card-action">
                             Usuário: <?php echo $usuario ?>
                            <div class="tooltip"><i class="fa fa-info-circle"></i>
                                <span class="tooltiptext">Informe um e-mail válido para recuperação de senha.</span>
                            </div>
                        </div> 

                        <div class="card-content" style="width: 400px">
                            <div class="group" style="width: 400px">

                                <form method="post" action="grava_edit_user.php" name="edita_user">
                                    <div class="group">
                                        <input type="text" id="email_user" class="form-control" placeholder="E-mail para recuperação de senha" name="email_user" required="">
                                    </div>
                                    <div class="group">
                                        <input type="password" id="user_password" class="form-control" placeholder="Nova senha" name="user_password"  required="">
                                    </div>
                                    <div>
                                        <button type="submit" name="salva_edit" class="waves-effect waves-light btn" id="submit" onClick="return validarcampos()">Salvar</button>
                                    </div>
                                    
                                </form>

                            </div>
                            
                        </div>
                    </div></center>



                    <!-- End Advanced Tables -->


                </div>
            </div>
                
                    <!-- /. ROW  -->
                    
                </div>
                <!-- /. PAGE INNER  -->
            </div>
        </div>





            <!-- /. WRAPPER  -->
        <!-- JS Scripts-->
        <!-- jQuery Js -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        
        <!-- Bootstrap Js -->
        <script src="assets/js/bootstrap.min.js"></script>
        
        <script src="assets/materialize/js/materialize.min.js"></script>
        
        <!-- Metis Menu Js -->
        <script src="assets/js/jquery.metisMenu.js"></script>
        <!-- Morris Chart Js -->
        <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
        <script src="assets/js/morris/morris.js"></script>
        
        
        <script src="assets/js/easypiechart.js"></script>
        <script src="assets/js/easypiechart-data.js"></script>
        
        <script src="assets/js/Lightweight-Chart/jquery.chart.js"></script>
        
        <!-- Custom Js -->
        <script src="assets/js/custom-scripts.js"></script> 
     

    </body>

    </html>

<?php
}else{

    header("location: ./index.php");
    exit();

}
?>