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
                <h1 class="page-header">Edição de Biometria</h1>
				<ol class="breadcrumb">
					<li>Altere e salve a biometria...</li>
				</ol> 							
			</div>	
            <div id="page-inner"> 
				<div class="row">
                    <div class="col-md-3"></div>
					<div class="col-md-6">
                        <div class="card"">
                    		<div class="card-action">Escolha quais dados mudar.</div>
                    			<div class="card-content">

                                    <?php

                                        $id_bio = $_GET['id_bio'];
                                        $query_bio = "SELECT * FROM biometrias WHERE id_biometria = '$id_bio'";
                                        $result_bio = $link->query($query_bio);
                                        $row_bio = mysqli_fetch_object($result_bio);

                                        $serial = $row_bio->serial_bio;
                                        $patrimonio = $row_bio->patrimonio_bio;
                                        $status = $row_bio->status_bio;
                                        $sede_b = $row_bio->sede_bio;

                                    ?>



    								<form class="col s12" name="form-pesquisa" action="salva_edit_bio.php" method="post">

                                        <div class="row">
                                            <div class="input-field">
                                                <select class="form-control" name="sede" id="sede" required="" style="width: 200px;">
                                                    <?php
                                                        echo "<option value='0'>Sedes...</option>";

                                                        $array_sedes = array("CG", "JP", "SS");

                                                        for ($i=1; $i < 4; $i++) { 
                                                            if ($sede != 0) {
                                                                if ($i == intval($sede_b)) {
                                                                    echo "<option selected='selected' value='".$i."''>".$array_sedes[$i-1]."</option>";
                                                                } 
                                                            }else{
                                                                if ($i == intval($sede_b)) {
                                                                    echo "<option selected='selected' value='".$i."''>".$array_sedes[$i-1]."</option>";
                                                                }else{
                                                                    echo "<option value='".$i."''>".$array_sedes[$i-1]."</option>";
                                                                }
                                                            }
                                                        }
                                                    ?> 
                                                </select>
                                            </div>       
                                        </div>

                                        <div class="row">
                                            <div class="input-field">
                                                <select class="form-control" name="status" id="status" required="" style="width: 200px;">
                                                    <?php
                                                        echo "<option value='0'>Status...</option>";

                                                        $array_status = array("ESTOQUE", "ESCOLA", "SEDE JP", "GARANTIA");

                                                        for ($i=1; $i <= 4; $i++) { 
                                                            if ($i != 2) {
                                                                if ($i == intval($status)) {
                                                                    echo "<option selected='selected' value='".$i."''>".$array_status[$i-1]."</option>";
                                                                }else{
                                                                    echo "<option value='".$i."''>".$array_status[$i-1]."</option>";
                                                                }
                                                                
                                                            }
                                                        }
                                                    ?> 
                                                </select>
                                            </div>       
                                        </div>

                                        <input type="hidden" id="id_bio" name="id_bio" value="<?php echo $id_bio; ?>" >

                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input type="text" name="serial" value="<?php echo $serial; ?>" required>
                                            </div>
                                        </div>  

                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input type="text" name="patrimonio" value="<?php echo $patrimonio; ?>" placeholder='Patrimônio'>
                                            </div>
                                        </div>  

                                        <div>
                                            <center><button type="submit" class="waves-effect waves-light btn" id="submit">Salvar Edição</button></center>
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
