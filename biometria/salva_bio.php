<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($_SESSION['user_name']) && !is_numeric($usuario)){
    
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
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="../assets/js/Lightweight-Chart/cssCharts.css"> 
</head>

<body>
    <?php include("setor_menu.php"); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">
                    Nova Biometria
                </h1>
                <ol class="breadcrumb">
					<li>Resultado da solicitação.</li>
				</ol>
            </div>
		
            <div id="page-inner"> 
               
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                            Mensagem
                        </div>
                        <div class="card-content">
 						<div class="table-responsive">
                            <?php

								if(!empty($_POST)){

									if(isset($_POST['serial']) && isset($_POST['sede'])){

                                        //echo "<script>alert('ok')</script>";


										if(!empty($_POST['serial']) && $_POST['sede'] !== '0'){

											$serial = $_POST['serial'];
                                            $sede = $_POST['sede'];

                                            if (strlen($serial) < 17 and $serial != "") {

                                                $novo = $serial;

                                                for ($i=0; $i < (17-strlen($serial)); $i++) { 
                                                    $novo = "0".$novo;
                                                }

                                                $serial = $novo;

                                            }

                                            if (!empty($_POST['patrimonio'])){

                                                $patrimonio = $_POST['patrimonio'];

                                                $query_insert = "INSERT INTO `biometrias`(`serial_bio`, `patrimonio_bio`, `status_bio`, `sede_bio`) VALUES ('$serial','$patrimonio',1,'$sede')";

                                            }else{

                                                $query_insert = "INSERT INTO `biometrias`(`serial_bio`, `status_bio`, `sede_bio`) VALUES ('$serial',1,'$sede')";
                                            }

                                            $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
                                            $link->set_charset('utf8');

											if (!$link){

											  die('Connect Error (' . mysqli_connecterrno() . ')' .
											    mysqli_connect_error());

											}else{

                                                $result_existe = $link->query("SELECT * FROM biometrias WHERE serial_bio = '$serial'");

                                                if (mysqli_num_rows($result_existe) > 0) {
                                                    ?>

                                                    <div class="input-field col s12">
                                                        <div class="alert alert-danger">
                                                            <strong>Já existe uma biometria no sistema com esse SERIAL!</strong>
                                                        </div>            
                                                    </div>

                                                    <?php
                                                }else{ 

                                                    $result_insert = $link->query($query_insert) or die( mysqli_error( $link ) );


                                                    if($result_insert == TRUE){


                                                        //########################################################## LOG ############################################

                                                        $DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
                                                        $DT_atual = $DT_atual->format('Y-m-d h:m:i');

                                                        $query_add_log = "INSERT INTO `log_acoes`(`data_acao`, `descricao_acao`, `fk_id_user`) VALUES ('$DT_atual','Biometria inserida: $serial','$id_usersede')";
                                                        $result_add_log = $link->query($query_add_log) or die( mysqli_error( $link ) );

                                                        //##################################################### HISTORY ##################################################

                                                        $ultimo_id = mysqli_fetch_object($link->query("SELECT * FROM biometrias WHERE serial_bio = '$serial'"))->id_biometria;

                                                        $query_history = "INSERT INTO `history_serial`(`data_mudanca`, `fk_id_serial`, `fk_id_escola_serial`, `fk_status_bio`, `fk_sede_bio`) VALUES ('$DT_atual','$ultimo_id',0,1, '$sede')";
                                                        $result_history = $link->query($query_history) or die(mysqli_error($link));

                                                        ?>

                                                        <div class="input-field col s12">
                                                            <div class="alert alert-success">
                                                                <strong>Biometria inserida com Sucesso!</strong>
                                                            </div>
                                                        </div>
                                                        <div class="input-field col s3">
                                                            <a href="bio_dashboard.php" class="waves-effect waves-light btn custom-back">Voltar</a>
                                                        </div>
                                                    <?php

                                                    }else{

                                                    ?>

                                                        <div class="input-field col s12">
                                                            <div class="alert alert-danger">
                                                                <strong>A biometria não foi inserida. Tente novamente.</strong>
                                                            </div>            
                                                        </div>
                                                        <div class="input-field col s3">
                                                            <a href="bio_dashboard.php" class="waves-effect waves-light btn custom-back">Voltar</a>
                                                        </div>

                                                    <?php
                                                    }       
                                                    
                                                }
                                            }
											mysqli_close($link);
									  
										}else{

											?>

											<div class="input-field col s12">
								                <div class="alert alert-danger">
								                    <strong>Por favor, preencha o formulário corretamente!</strong>
								                </div>            
								            </div>

								            <?php
										}
									}else{


										?>

										<div class="input-field col s12">
								            <div class="alert alert-danger">
								                <strong>Por favor, preencha o formulário corretamente!</strong>
								            </div>            
								        </div>

								        <?php
									}

									?>
								<?php
								}else{

									?>

									<div class="input-field col s12">
						                <div class="alert alert-danger">
						                    <strong>Por favor, preencha o formulário corretamente!</strong>
						                </div>            
						            </div>

						            <?php
								}	
							?>
						</div>
						<!-- End Card Content-->
						</div>
						<!-- End Table-Responsive-->
					</div>
					<!--End Card -->
				</div>
				<!--End col-md-12-->
			</div>
			<!--ROW-->
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

    header("location: ../index.php");
    exit();

}
?>