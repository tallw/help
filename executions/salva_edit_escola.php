 <?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($_SESSION['user_name']) && !is_numeric($_SESSION['user_name'])){
    
require('../config/config.php');
include('../libraries/functions.php');
include('../libraries/functions_date.php');
include('../libraries/feriado.php');
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
                    Edição De CheckList Escola
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

    									if(isset($_POST['id_escola'])){

    										if(!empty($_POST['id_escola'])){

    											$id_escola = $_POST['id_escola'];

                                                $qtde_1 = $_POST['qtde_1'];
                                                $qtde_2 = $_POST['qtde_2'];
                                                $qtde_3 = $_POST['qtde_3'];
                                                $qtde_4 = $_POST['qtde_4'];
                                                $qtde_5 = $_POST['qtde_5'];

                                                $MP = 'O';
                                                $VT = 'O';
                                                $I = 'O'; 
                                                $IR = 'O'; 
                                                $LI = 'O'; 
                                                $B = 'O';
                                                $CA = 'O';
                                                $SA = 'O';

                                                if (isset($_POST['MP'])) {
                                                    $MP = 'X';
                                                }
                                                if (isset($_POST['VT'])) {
                                                    $VT = 'X';
                                                }
                                                if (isset($_POST['I'])) {
                                                    $I = 'X';
                                                }
                                                if (isset($_POST['IR'])) {
                                                    $IR = 'X';
                                                }
                                                if (isset($_POST['LI'])) {
                                                    $LI = 'X';
                                                }
                                                if (isset($_POST['B'])) {
                                                    $B = 'X';
                                                }
                                                if (isset($_POST['CA'])) {
                                                    $CA = 'X';
                                                }
                                                if (isset($_POST['SA'])) {
                                                    $SA = 'X';
                                                }

                                                
                                                $query_edit_infra = "UPDATE `infraestrutura` SET `mnt_preventiva`='$MP',`vistoria`='$VT',`internet`='$I',`infra_rede`='$IR',`lab_info`='$LI',`biometria`='$B',`camera`='$CA',`saber`='$SA', `qtde_labs_info`='$qtde_1',`qtde_pcs`='$qtde_2',`qtde_pcs_atv`='$qtde_3',`qtde_pcs_labs_info`='$qtde_4',`qtde_pcs_labs_info_atv`='$qtde_5' WHERE fk_id_escola ='$id_escola'";

                                                
                                                
                                                
    											$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
                                                $link->set_charset('utf8');

    											if (!$link){

    											  die('Connect Error (' . mysqli_connecterrno() . ')' .
    											    mysqli_connect_error());

    											}else{

                                                    
            										$result_edit_infra = $link->query($query_edit_infra) or die( mysqli_error( $link ) );

    												if($result_edit_infra == TRUE){

                                                        //########################################################## LOG ############################################

                                                        $DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
                                                        $DT_atual = $DT_atual->format('Y-m-d h:m:i');

    
                                                        $query_descobre_sede = "SELECT * FROM sede S WHERE S.user_sede = '$usuario'";
                                                        $result_descobre_sede = $link->query($query_descobre_sede);
                                                        $row_sede = mysqli_fetch_object($result_descobre_sede);
                                                        $id_usersede = $row_sede->sede_id;


                                                        $query_add_log = "INSERT INTO `log_acoes`(`id_acao`, `data_acao`, `descricao_acao`, `fk_id_user`) VALUES (NULL,'$DT_atual','Infra editada na escola de ID: $id_escola','$id_usersede')";

                                                        $result_add_log = $link->query($query_add_log) or die(mysqli_error($link));

                                                        //###################################################################################################################


    												  		?>

    														<div class="input-field col s12">
    											                <div class="alert alert-success">
    											                    <strong>Infra editada com Sucesso!</strong>
    											                </div>
                                                                <div class="input-field col s3">
                                                                    <a href="../timeline_tudao.php?id_escola=<?php echo $id_escola; ?>" class="waves-effect waves-light btn custom-back">Voltar</a>
                                                                </div>
                                                            </div>
                                                            
    											            <?php

    													}else{

    										  				?>

    														<div class="input-field col s12">
    											                <div class="alert alert-danger">
    											                    <strong>A Infra não foi editada. Tente novamente.</strong>
    											                </div>            
    											            </div>

    											            <?php
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