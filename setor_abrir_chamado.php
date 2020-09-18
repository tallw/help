<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];


if(isset($usuario) && !is_numeric($usuario)){

	require('./config/config.php');

    $link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
    $link->set_charset('utf8');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
    <title>HELP-ECOS | Sistema de Apoio às Escolas</title>

    <!-- ############################################### SEARCH SELECT ################################################## -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
    <!--<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" rel="stylesheet" />-->

    <!-- ############################################### SEARCH SELECT ################################################## -->
	
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
    <!-- Search select -->
    <link href="dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="assets/js/Lightweight-Chart/cssCharts.css"> 

</head>
<!-- Populate option -->
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="js.js"></script>
    <script type="text/javascript" src="js2.js"></script>

<body>
    <?php include("setor_menu.php"); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" > 
		  	<div class="header"> 
                <h1 class="page-header">Novo Chamado</h1>
				<ol class="breadcrumb">
					<li>Abra um chamado de emergência (protocolo CG..., JP..., SS...) ou programado (protocolo CGEX..., JPEX..., SSEX...).</li>
				</ol> 							
			</div>	
            <div id="page-inner"> 
				<div class="row">
                    <div class="col-md-3"></div>
					<div class="col-md-6">
						<center>
                            <div class="card"">
                    			<div class="card-action">Selecione a escola e depois o motivo do chamado</div>
                    				<div class="card-content">
    								    <form class="col s12" name="form-pesquisa" action="executions/setor_grava_chamado.php" method="post">

                                            <div class="row">
                                                <div class="input-field col s3">
                                                    <label for="emergencia">DATA ABERTURA: </label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s3">
                                                    <input type="date" name="data" required>
                                                </div>
                                            </div>

    								    	<div class="row"> 
	                                            <div class="input-field">

	                                                <?php 

	                                                    // get active sede user
	                                                    $query_get_sede = "SELECT sede, user_sede FROM sede WHERE user_sede = '$usuario'";
	                                                    $result_id = $link->query($query_get_sede) or die(mysqli_error($link));
	                                                    while($row_id = mysqli_fetch_assoc($result_id)){
	                                                        $sede = $row_id['sede'];
	                                                    }
	                                                ?>

	                                                <!-- ############################################### SEARCH SELECT ################################################## -->
	                                                <select name="escola" class="selectpicker" data-show-subtext="true" data-live-search="true" id="escola">
	                                                    <?php
	                                                            echo "<option value='0'>Escola...</option>";

	                                                            if ($sede === '0') {
	                                                                $sql = "SELECT * FROM escola AS E INNER JOIN gre AS G ON E.gre = G.id_gre WHERE E.ativo = 1";
	                                                            }else{

                                                                    if($sede === '1'){

                                                                        $sql = "SELECT * FROM escola AS E INNER JOIN gre AS G ON E.gre = G.id_gre WHERE G.id_sede = '$sede' OR E.id_escola = 70 AND E.ativo = 1";

                                                                    }else{

                                                                        $sql = "SELECT * FROM escola AS E INNER JOIN gre AS G ON E.gre = G.id_gre WHERE G.id_sede = '$sede' AND E.ativo = 1";
                                                                    } 
	                                                            }
	                                                            
	                                                            $dados = mysqli_query($link, $sql);

	                                                            $c = 0;

	                                                            if(mysqli_num_rows($dados) > 0) {
	                                                                while($row = mysqli_fetch_object($dados)) {
	                                                                    echo "<option value='".$row->id_escola."'>".$row->nome_escola."</option>";
	                                                                }
	                                                            }
	                                                        ?>         
	                                                </select>
	                                            </div>
                                        	</div>

                                            <div class="row">
                                                <div class="input-field">
                                                    <select class="form-control" name="motivo" id="motivo" required="">
                                                        <?php
                                                            echo "<option value='0'>Categoria...</option>";
                                                            $sql = "SELECT * FROM motivo_chamado ORDER BY motivo";
                                                            $dados = mysqli_query($link, $sql);

                                                            if(mysqli_num_rows($dados) > 0) {
                                                                while($row = mysqli_fetch_object($dados)) {
                                                                    echo "<option value='".$row->id_motivo."'>".$row->motivo."</option>";
                                                                }
                                                            }
                                                        ?> 
                                                    </select>
                                                </div>       
                                            </div>

                                            <div class="row">
                                                
                                                <div class="input-field">
                                                    <select class="form-control" name="sub_motivo" id="sub_motivo" required=""></select>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <p>
                                                    <input type="radio" name="tipo" id="emergencia" value="emergencia" >
                                                    <label for="emergencia">Emergência</label>
                                                    <input type="radio" name="tipo" id="programado" value="programado" >
                                                    <label for="programado">Programado</label>
                                                </p>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <textarea class="form-control" rows="5" name="obs_op" placeholder="Observações do operador:" maxlength="10000"></textarea>
                                                </div>
                                            </div>
                                            <div>
                                                <button type="submit" class="waves-effect waves-light btn" id="submit">Abrir Chamado</button>
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

    <!-- Search select -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js" defer></script>
    <script src="dist/js/bootstrap-select.js" defer></script>


</body>

</html>

<?php
}else{

    header("location: ./index.php");
    exit();

}
?>
