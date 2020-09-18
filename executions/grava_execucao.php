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
                    Adicionar Execução Diária
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

    									if(isset($_POST['data']) && isset($_POST['tecnico']) && isset($_POST['local']) && isset($_POST['servico']) && isset($_POST['id_os'])){

    										if(!empty($_POST['data']) && !empty($_POST['tecnico']) && !empty($_POST['local']) && !empty($_POST['servico']) && !empty($_POST['id_os'])){

                                                // UPDATE `execucao_diaria` SET `observacao`= REPLACE(`observacao`, "'", '')

    											$data = $_POST['data'];
                                                $tecnico = str_replace("'","",$_POST['tecnico']);
                                                $tecnico = str_replace('"',"",$tecnico);
                                                $local =  str_replace("'","",$_POST['local']);
                                                $local = str_replace('"',"",$local);
                                                $servico = str_replace("'","",$_POST['servico']);
                                                $servico = str_replace('"',"",$servico);
                                                $id_os = $_POST['id_os'];
                                                $observacao = str_replace("'","",$_POST['observacao']);
                                                $observacao = str_replace('"',"",$observacao);
                                                $pendencias = str_replace("'","",$_POST['pendencias']);
                                                $pendencias = str_replace('"',"",$pendencias);
                                                $unsolved = 1;

                                                $local_topic = "<b>Local da execução: </b>";
                                                $local = "{$local_topic}{$local}";

                                                $service_topic = "<br><b>Serviço realizado: </b>";
                                                $servico = "{$service_topic}{$servico}";

                                                $observation_topic = "<br><b>Observações: </b>";
                                                $observacao = "{$observation_topic}{$observacao}";

                                                $done_by_topic = "<br><b>Executado por: </b>";
                                                $tecnico = "{$done_by_topic}{$tecnico}";


                                                if(!empty($_POST['pendencias'])){

                                                    $pending_topic = "<b>Pendências: </b>";
                                                    $pendencia = "{$pending_topic}{$pendencias}";
                                                    $query_add_exec = "INSERT INTO `execucao_diaria`(`id_execucao`, `data_execucao`, `tecnicos`, `local`, `servico`, `observacao`, `pendencias`, `status_pendencia`, `fk_id_ordem_servico`) VALUES (NULL,'$data','$tecnico','$local','$servico','$observacao','$pendencia','$unsolved','$id_os')";
                                                }else{

                                                    $query_add_exec = "INSERT INTO `execucao_diaria`(`id_execucao`, `data_execucao`, `tecnicos`, `local`, `servico`, `observacao`, `fk_id_ordem_servico`) VALUES (NULL,'$data','$tecnico','$local','$servico','$observacao','$id_os')";
                                                }

                                                

                                                
    											$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
                                                $link->set_charset('utf8');

    											if (!$link){

    											  die('Connect Error (' . mysqli_connecterrno() . ')' .
    											    mysqli_connect_error());

    											}else{

                                                    
            										$result_add_exec = $link->query($query_add_exec) or die( mysqli_error( $link ) );


    												if($result_add_exec == TRUE){

                                                        //########################################################## LOG ############################################

                                                        $DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
                                                        $DT_atual = $DT_atual->format('Y-m-d h:m:i');

    
                                                        $query_descobre_sede = "SELECT * FROM sede S WHERE S.user_sede = '$usuario'";
                                                        $result_descobre_sede = $link->query($query_descobre_sede);
                                                        $row_sede = mysqli_fetch_object($result_descobre_sede);
                                                        $id_usersede = $row_sede->sede_id;


                                                        $query_add_log = "INSERT INTO `log_acoes`(`id_acao`, `data_acao`, `descricao_acao`, `fk_id_user`) VALUES (NULL,'$DT_atual','Execução inserida na OS ID: $id_os','$id_usersede')";
                                                        $result_add_log = $link->query($query_add_log) or die( mysqli_error( $link ) );

                                                        //###################################################################################################################


    												  		?>

    														<div class="input-field col s12">
    											                <div class="alert alert-success">
    											                    <strong>Execução Registrada com Sucesso!</strong>
    											                </div>
                                                            </div>
                                                            <div class="input-field col s3">
                                                                <a href="../setor_ver_os.php?id_os=<?php echo $id_os; ?>" class="waves-effect waves-light btn custom-back">Voltar</a>
                                                            </div>
    											            <?php

    													}else{

    										  				?>

    														<div class="input-field col s12">
    											                <div class="alert alert-danger">
    											                    <strong>A execução não foi registrada. Tente novamente.</strong>
    											                </div>            
    											            </div>
                                                            <div class="input-field col s3">
                                                                <a href="../setor_ver_os.php?id_os=<?php echo $id_os; ?>" class="waves-effect waves-light btn custom-back">Voltar</a>
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