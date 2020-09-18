<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($usuario) && !is_numeric($usuario)){

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
	<link rel="stylesheet" href="../assets/materialize/css/materialize.min.css" media="screen,projection" />
    <!-- Bootstrap Styles-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="../assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="../assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Search select -->
    <link href="../dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="../assets/js/Lightweight-Chart/cssCharts.css"> 

</head>

<body>
    <?php include("setor_menu.php"); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" > 
		  	<div class="header"> 
                <h1 class="page-header">Nova Biometria</h1>
				<ol class="breadcrumb">
					<li>Insira uma nova biometria...</li>
				</ol> 							
			</div>	
            <div id="page-inner"> 
				<div class="row">
                    <div class="col-md-3"></div>
					<div class="col-md-6">
                        <div class="card"">
                    		<div class="card-action">Selecione a Sede e informe o Serial da Biometria.</div>
                    			<div class="card-content">
    								<form class="col s12" name="form-pesquisa" action="salva_bio.php" method="post">

                                        <div class="row">
                                            <div class="input-field">
                                                <select class="form-control" name="sede" id="sede" required="" style="width: 200px;">
                                                    <?php
                                                        echo "<option value='0'>Sedes...</option>";
                                                        $option_cg = "<option value='1'>Campina Grande</option>";
                                                        $option_jp = "<option value='2'>João Pessoa</option>";
                                                        $option_ss = "<option value='3'>Sousa</option>";
                                                        if ($sede === '0') {
                                                            echo $option_jp.$option_cg.$option_ss;
                                                        }else if ($sede === '2') {
                                                            echo $option_jp;
                                                        }else if ($sede === '1') {
                                                            echo $option_cg;
                                                        }else if ($sede === '3') {
                                                            echo $option_ss;
                                                        }
                                                    ?> 
                                                </select>
                                            </div>       
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input type="text" name="serial" placeholder='Serial da Biometria' required>
                                            </div>
                                        </div>  

                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input type="text" name="patrimonio" placeholder='Patrimônio'>
                                            </div>
                                        </div>  

                                        <div>
                                            <center><button type="submit" class="waves-effect waves-light btn" id="submit">Salvar Bio</button></center>
                                        </div>

    								</form>
    								<div class="clearBoth"></div>
    	  						</div>
    	    				</div>
                        </div>
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
    <script src="../assets/js/jquery-1.10.2.js"></script>
	
	<!-- Bootstrap Js -->
    <script src="../assets/js/bootstrap.min.js"></script>
	
	<script src="../assets/materialize/js/materialize.min.js"></script>
	
    <!-- Metis Menu Js -->
    <script src="../assets/js/jquery.metisMenu.js"></script>
    <!-- Morris Chart Js -->
    <script src="../assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="../assets/js/morris/morris.js"></script>
	
	
	<script src="../assets/js/easypiechart.js"></script>
	<script src="../assets/js/easypiechart-data.js"></script>
	
	 <script src="../assets/js/Lightweight-Chart/jquery.chart.js"></script>
	
    <!-- Custom Js -->
    <script src="../assets/js/custom-scripts.js"></script>


</body>

</html>

<?php
}else{

    header("location: ./index.php");
    exit();

}
?>
