<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

if(isset($_SESSION['user_name'])){
    
require('../config/config.php');
include('../libraries/functions.php');
include('../libraries/functions_date.php');
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
                    <a href="#"><i class="fa fa-key fa-fw"></i> Alterar Senha</a>
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
                            <a class="waves-effect waves-dark" href="../school_dashboard.php"><i class="fa fa-dashboard"></i> Painel</a>
                        </li>
                        <li>
                            <a href="#" class="active-menu waves-effect waves-dark"><i class="fa fa-ticket"></i> Chamados<span class="fa arrow"></span></a>  
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="../abrir_chamado.php">Abrir Chamado</a>
                                </li>
                                <li>
                                    <a href="../meus_chamados.php?filtro=0">Meus Chamados</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">
                    Novo Chamado
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

									if(isset($_POST['departamento']) && isset($_POST['sub_motivo'])){

										if(!empty($_POST['departamento']) && !empty($_POST['sub_motivo'])){

											$id_departamento = $_POST['departamento'];
                                            //$id_motivo = $_POST['motivo'];
                                            $id_sub_motivo = $_POST['sub_motivo'];
                                            
                                            $inep = $_SESSION['user_name'];

                                            $tipo = '0'; //type is 0 because it have to be saved as "emergência"

                                            try{

                                                    $DT = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
                                                    $DT = $DT->format('Y-m-d h:m:i');

                                            }catch( Exception $e ){

                                                    echo 'Erro ao instanciar objeto.';

                                                    echo $e->getMessage();

                                                    exit();
                                            }
                                            /*
                                            $encoding = 'UTF-8';
											$departamento = trim($departamento);
											$departamento = mb_convert_case($departamento, MB_CASE_UPPER, $encoding);
                                            */
											$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
                                            $link->set_charset('utf8');

											if (!$link){

											  die('Connect Error (' . mysqli_connecterrno() . ')' .
											    mysqli_connect_error());

											}else{


                                                $query_get_sede = "SELECT * FROM escola AS E INNER JOIN gre AS G ON E.gre = G.id_gre WHERE E.inep = '$inep'";
                                                $result_get_sede = $link->query($query_get_sede);
                                                if(mysqli_num_rows($result_get_sede) > 0) {
                                                    while($row = mysqli_fetch_object($result_get_sede)) {
                                                        $sede = $row->id_sede;
                                                        $id_escola = $row->id_escola; 
                                                    }
                                                }

                                                // checar se o chamado existe

                                                $chamado_aberto = checkExistenciaChamado($id_escola, $id_sub_motivo, $link); 

                                                // check last protocol

                                                $novo_protocolo = checkUltimoProtocolo($tipo, $sede, $id_escola, $link);


                                                if ($chamado_aberto == 'N'){

                                                    //Configura o timezone a ser utilizado
                                                    date_default_timezone_set('America/Fortaleza');

                                                    $fim_expediente      = strtotime('17:00');
                                                    $horaAtual = strtotime(date('H:i'));

                                                    $horaAtual_text = date('H:i');
                                                    $OBS = "";
                                                    $hoje = date("d/m/Y");
    
                                                    if (e_dia_util($hoje) == 0) {

                                                        $prox_dia_util = get_proximo_dia_util($hoje);
                                                        $DT = converte_padrao_date($prox_dia_util);
                                                        $OBS = "Devido a hoje: $hoje não ser um dia útil, o chamado será aberto com data igual ao próximo dia útil: ".$prox_dia_util;

                                                    }else if($horaAtual > $fim_expediente){
                                                        $prox_dia_util = get_proximo_dia_util(Soma1dia($hoje));
                                                        $DT = converte_padrao_date($prox_dia_util);
                                                        $OBS = "Devido a hora atual: $horaAtual_text ser superior ao fim do expediente, o chamado foi aberto com a data do próximo dia útil: ".$prox_dia_util; 
                                                    }

                                                    $query_add_chamado = "INSERT INTO `ordem_servico`( `protocolo`, `tipo_chamado`, `status`, `dt_abertura`, `sla_atendimento`, `sla_conclusao`, `avaliacao`, `fk_id_motivo_os`, `fk_id_nome_escola`, `fk_id_sede`) VALUES ('$novo_protocolo','$tipo', '1', '$DT', 0, 0, 0, '$id_sub_motivo', '$id_escola', '$sede')";

                                                    // 0=todos 1=aberto 2=em_atendimento 3=finalizado

        											$result_add_chamado = $link->query($query_add_chamado) or die( mysqli_error( $link ) );

													if($result_add_chamado == TRUE){

                                                        $get_id = "SELECT id_os "
										  
												  		?>

														<div class="input-field col s12">
											                <div class="alert alert-success">
											                    <strong>Chamado Registrado com Sucesso!</strong>
                                                                <p>Nº Protocolo: <?php echo ($novo_protocolo); ?></p>
											                    <p>Você pode acompanhar o andamento deste chamado no menu "Meus Chamados".</p>
                                                                <p>A ECOS tem até 3 dias úteis para iniciar o atendimento deste chamado.</p>

                                                                <?php
                                                                    if ($OBS != "") { 
                                                                        ?>
                                                                        <p>OBS: <?php echo ($OBS); ?></p>
                                                                        <?php
                                                                    }

                                                                ?>

											                </div>            
											            </div>

											            <?php

													}else{

										  				?>

														<div class="input-field col s12">
											                <div class="alert alert-danger">
											                    <strong>O chamado não foi registrado. Tente novamnte.</strong>
											                </div>            
											            </div>

											            <?php
													}
                                                }else{

                                                    ?>

                                                    <div class="input-field col s12">

                                                        <div class="alert alert-danger">
                                                            <strong>Já existe um chamado aberto com o mesmo motivo.</strong>
                                                            
                                                            <p>É necessário aguardar a conclusão do chamado em andamento.</p>
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
	
	 <script src="assets/js/Lightweight-Chart/jquery.chart.js"></script>
	 <!-- DATA TABLE SCRIPTS -->
    <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
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