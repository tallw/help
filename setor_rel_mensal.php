<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start();
}

if(isset($_SESSION['user_name']) && !is_numeric($_SESSION['user_name'])){

    $usuario = $_SESSION['user_name'];

	require('./config/config.php');

    include('config/base.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, init6ial-scale=1.0" />
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
    
    <?php

    include("setor_menu.php");

        $tipo = $_GET['tipo'];

        $destino = "";

        $titulo = "";

        if ($tipo === '0') {
            $destino = "executions/gera_rel_atividades.php";
            $titulo = "Atividades por Escola";
        }else if($tipo === '1'){
            $destino = "executions/gera_rel_grafico.php";
            $titulo = "Atividades por GRE";
        }else if($tipo === '2'){
            $destino = "executions/gera_rel_pendencias.php";
            $titulo = "OS's com Pendências";
        }

    ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
		  	<div class="header"> 
                <h1 class="page-header"><?php echo $titulo; ?></h1>
				<ol class="breadcrumb">
					<li>Escolha o ano e o mês que você deseja gerar o relatório.</li>
				</ol> 							
			</div>	
            <div id="page-inner"> 
				<div class="row">
					<div class="col-md-12">
						<center>
                            <div class="card"">
                    			<div class="card-action">Selecione o ano e o mês</div>
                    				<div class="card-content">
    								    <form class="col s12" name="form-pesquisa" action=<?php echo $destino; ?> method="post">
                                            <div class="row">
                                                <div class="input-field col s1">
                                                    <strong>Ano: </strong>
                                                </div>
                                                <div class="input-field col s2">
                                                    <select class="form-control" name="ano" id="ano">
                                                        <option value='2019'>2019</option>"
                                                        <option value='2018'>2018</option>"
                                                        <option value='2017'>2017</option>"
                                                    </select>
                                                </div>
                                                <div class="input-field col s1">
                                                    <strong>Mês:</strong>
                                                </div>
                                                <div class="input-field col s3">
                                                    <select class="form-control" name="mes" id="mes">
                                                        <option value=''>Selecione um mês...</option>
                                                        <option value='01'>Janeiro</option>
                                                        <option value='02'>Fevereiro</option>
                                                        <option value='03'>Março</option>
                                                        <option value='04'>Abril</option>
                                                        <option value='05'>Maio</option>
                                                        <option value='06'>Junho</option>
                                                        <option value='07'>Julho</option>
                                                        <option value='08'>Agosto</option>
                                                        <option value='09'>Setembro</option>
                                                        <option value='10'>Outubro</option>
                                                        <option value='11'>Novembro</option>
                                                        <option value='12'>Dezembro</option>
                                                    </select>
                                                </div>
                                                <?php 

                                                if ($tipo === '2') {
                                                    ?>

                                                    <div class="input-field col s2">
                                                    <select class="form-control" name="status" id="status">
                                                        <option value=''>Selecione o Status...</option>"
                                                        <option value='1'>Com Pendência</option>"
                                                        <option value='0'>Sem Pendência</option>"
                                                        <option value='2'>Todas</option>"
                                                    </select>
                                                </div>



                                                    <?php
                                                    
                                                }




                                                ?>
                                                <div class="input-field col s3">
                                                    <button type="submit" class="waves-effect waves-light btn" id="submit">Gerar Relatório</button>
                                                </div>      
                                            </div>
    								    </form>
    									<div class="clearBoth"></div>
    	  							</div>
    	    					</div>
                            </div>
                        </center>
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
