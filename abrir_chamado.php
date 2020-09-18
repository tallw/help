<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

if(isset($_SESSION['user_name']) && is_numeric($_SESSION['user_name'])){

	require('./config/config.php');

    $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');
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
<!-- Populate option -->
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="js.js"></script>
    <script type="text/javascript" src="js2.js"></script>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle waves-effect waves-dark" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand waves-effect waves-dark" href="#"><i class="large material-icons">insert_chart</i> <strong>HELP-ECOS</strong></a>
                    
                    <div id="sideNav" href=""><i class="material-icons dp48">toc</i></div>
                </div>

                <ul class="nav navbar-top-links navbar-right">          
                    <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b>Meu Perfil</b> <i class="material-icons right">arrow_drop_down</i></a>
                    </li>
                </ul>
            </nav>
            <!-- Dropdown Structure -->
            <ul id="dropdown1" class="dropdown-content">
                <li>
                    <a href="edita_user.php"><i class="fa fa-key fa-fw"></i> Alterar Senha</a>
                </li>
                <li>
                    <a href="index.php?logout"><i class="fa fa-sign-out fa-fw"></i> Sair</a>
                </li>
            </ul>
           <!--/. NAV TOP  -->
            <nav class="navbar-default navbar-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="main-menu">
                        <li>
                            <a class="waves-effect waves-dark" href="school_dashboard.php"><i class="fa fa-dashboard"></i> Painel</a>
                        </li>
                        <li>
                            <a href="#" class="active-menu waves-effect waves-dark"><i class="fa fa-ticket"></i> Chamados<span class="fa arrow"></span></a>  
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="abrir_chamado.php">Abrir Chamado</a>
                                </li>
                                <li>
                                    <a href="meus_chamados.php?filtro=0">Meus Chamados</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
		  	<div class="header"> 
                <h1 class="page-header">Novo Chamado</h1>
				<ol class="breadcrumb">
					<li>Escolha o departamento ao qual você deseja abrir o chamado e depois o motivo da solicitação.</li>
				</ol> 							
			</div>	
            <div id="page-inner"> 
				<div class="row">
                    <div class="col-md-3"></div>
					<div class="col-md-6">
						<center>
                            <div class="card"">
                    			<div class="card-action">Selecione o departamento e o motivo do chamado</div>
                    				<div class="card-content">
    								    <form class="col s12" name="form-pesquisa" action="executions/grava_chamado.php" method="post">

                                            <div class="row">
                                                <div class="input-field">
                                                    <select class="form-control" name="departamento" id="departamento">
                                                        <?php
                                                            echo "<option value='0'>Escolha o departamento...</option>";
                                                            $sql = "SELECT * FROM departamento ORDER BY nome_departamento";
                                                            $dados = mysqli_query($link, $sql);

                                                            if(mysqli_num_rows($dados) > 0) {
                                                                while($row = mysqli_fetch_object($dados)) {
                                                                    echo "<option value='".$row->id_departamento."'>".$row->nome_departamento."</option>";
                                                                }
                                                            }
                                                        ?> 
                                                    </select>
                                                </div>      
                                            </div>
                                            <div class="row">
                                            	
                                            	<div class="input-field">
                                                    <select class="form-control" name="motivo" id="motivo"></select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                
                                                <div class="input-field">
                                                    <select class="form-control" name="sub_motivo" id="sub_motivo"></select>
                                                </div>
                                            </div>
                                            <div>
                                                <button type="submit" class="waves-effect waves-light btn" id="submit">Enviar</button>
                                            </div>
    								    </form>
    									<div class="clearBoth"></div>
    	  							</div>
    	    					</div>
                            </div>
                        </center>
		 			</div>
                    <div class="col-md-3"></div>		
                </div> 
                <!-- /.col-lg-12 --> 
			</div>
             <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
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
