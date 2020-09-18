<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($_SESSION['user_name']) && !is_numeric($_SESSION['user_name'])){

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
                    Gravando nova Biometria para escola
                </h1>
                <ol class="breadcrumb">
					<li>Resultado da solicitação.</li>
				</ol>
            </div>
		
            <div id="page-inner"> 
               
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-action">
                            Mensagem
                        </div>
                        <div class="card-content">
     						<div class="table-responsive">
                                <?php

    								if(!empty($_POST)){

                                        //echo "<script>alert('ok')</script>";

    									if(isset($_POST['id_escola']) && isset($_POST['id_bio']) && isset($_POST['status_b']) && isset($_POST['nova_b'])){



    										if($_POST['status_b'] !== '0' && $_POST['nova_b'] !== '0'){

                                                $id_escola = $_POST['id_escola'];
    											$id_bio = $_POST['id_bio'];
    											$status_b = $_POST['status_b'];
    											$nova_b = $_POST['nova_b'];
                                                $sede_b = $_POST['sede_b'];

                                                $query_ba = "UPDATE biometrias SET status_bio = '$status_b' WHERE id_biometria = '$id_bio'";
                                                $query_esc_ba = "UPDATE escola SET fk_id_biometria = 0 WHERE id_escola = '$id_escola'";
                                                $query_bn = "UPDATE biometrias SET status_bio = 2 WHERE id_biometria = '$nova_b'";
                                                $query_esc_bn = "UPDATE escola SET fk_id_biometria = '$nova_b' WHERE id_escola = '$id_escola'";

                                                $mudou_bn = false;
                                                $mudou_ba = false;
                                                $frase_log = '';

                                                if ($id_bio !== 'nt') {
                                                    $result_ba = $link->query($query_ba) or die( mysqli_error( $link ));
                                                    $result_esc_ba = $link->query($query_esc_ba) or die( mysqli_error( $link ));
                                                    $mudou_ba = TRUE;
                                                    $frase_log .= "Biometria ID: ".$id_bio." antiga mudou Status. ";
                                                }

                                                if ($nova_b !== 'nt') {
                                                    $result_bn = $link->query($query_bn) or die( mysqli_error( $link ));
                                                    $result_esc_bn = $link->query($query_esc_bn) or die( mysqli_error( $link ));
                                                    $mudou_bn = TRUE;
                                                    $frase_log.= "Biometria ID: ".$nova_b." Nova na escola de ID: ".$id_escola.". "; 
                                                }

                                                
    											if($mudou_bn == TRUE || $mudou_ba == TRUE){

                                                    $DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
													$DT_atual = $DT_atual->format('Y-m-d h:m:i');

													    
													$query_descobre_sede = "SELECT * FROM sede S WHERE S.user_sede = '$usuario'";
													$result_descobre_sede = $link->query($query_descobre_sede);
													$row_sede = mysqli_fetch_object($result_descobre_sede);
													$id_usersede = $row_sede->sede_id;

                                                    //############################################# HISTORY ##################################################################################

                                                    if ($id_bio !== 'nt') {
                                                        $query_history_ab = "INSERT INTO `history_serial`(`data_mudanca`, `fk_id_serial`, `fk_id_escola_serial`, `fk_status_bio`, `fk_sede_bio`) VALUES ('$DT_atual','$id_bio',0,'$status_b', '$sede_b')";
                                                        $result_history_ab = $link->query($query_history_ab) or die(mysqli_error($link));
                                                    }

                                                    if ($nova_b !== 'nt') {
                                                   
                                                        $query_history_nb = "INSERT INTO `history_serial`(`data_mudanca`, `fk_id_serial`, `fk_id_escola_serial`, `fk_status_bio`, `fk_sede_bio`) VALUES ('$DT_atual','$nova_b','$id_escola',2, '$sede_b')";
                                                        $result_history_nb = $link->query($query_history_nb) or die (mysqli_error($link));
                                                    
                                                    }
                                                    //############################################# LOG ##################################################################################

													$query_add_log = "INSERT INTO `log_acoes`(`id_acao`, `data_acao`, `descricao_acao`, `fk_id_user`) VALUES (NULL,'$DT_atual','$frase_log','$id_usersede')";
													$result_add_log = $link->query($query_add_log) or die( mysqli_error( $link ) );

												
                                                        //###################################################################################################################


    												?>

    												<div class="input-field col s12">
    											        <div class="alert alert-success">
    											            <strong>Mudanças realizadas com sucesso com Sucesso!</strong>
    											        </div>
                                                    </div>
                                                    <div class="input-field col s3">
                                                        <a href="../setor_edita_escola.php?id_escola=<?php echo $id_escola; ?>" class="waves-effect waves-light btn custom-back">Voltar</a>
                                                    </div>
    											    <?php

    											}else{

    										  		?>

    												<div class="input-field col s12">
    											        <div class="alert alert-danger">
    											            <strong>Nenhuma ação realizada.</strong>
    											        </div>            
    											    </div>
                                                    <div class="input-field col s3">
                                                        <a href="../setor_edita_escola.php?id_escola=<?php echo $id_escola; ?>" class="waves-effect waves-light btn custom-back">Voltar</a>
                                                    </div>

    											    <?php
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
						</div>
                        <!-- End Card Content-->
					</div>
					<!--End Card -->
				</div>
				<!--End col-md-6-->
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
	
	<script src="assets/js/Lightweight-Chart/jquery.chart.js"></script>
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