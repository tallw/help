<?php


if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];


if(isset($usuario) && !is_numeric($usuario)){ // fazer condicao pra garantir que a escola sera do operador

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

    </head>
    <body>
        <?php include("setor_menu.php"); ?>
       
            <!-- /. NAV SIDE  -->

            <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        BACKUP DA BASE DE DADOS HELP-ECOS
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você faz e baixa o backup da base de dados do sistema.</li>
                    </ol>                         
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-action">
                             Escolha uma opção:
                        </div> 

                        <div class="card-content">

                            <a class="waves-effect waves-dark" href="schema/help_desk_ecos.sql.gz" download><i class="material-icons left">play_for_work</i>Download Backup Base de Dados Help-Ecos</a>

                            <br><br>

                            <a style="color: red" class="waves-effect waves-dark" href="schema/backup_db.php"><i class="material-icons left">play_for_work</i>Atualização Backup Base de Dados Help-Ecos</a>
                                
                        </div>  
                    </div>
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