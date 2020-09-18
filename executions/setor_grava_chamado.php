<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($_SESSION['user_name']) && !is_numeric($usuario)){
    
require('../config/config.php');
include('../libraries/functions.php');
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

									if(isset($_POST['escola']) && isset($_POST['sub_motivo'])){


										if(!empty($_POST['escola']) && !empty($_POST['sub_motivo']) && !empty($_POST['tipo']) && !empty($_POST['data'])){

											$id_escola = $_POST['escola'];
                                            $id_sub_motivo = $_POST['sub_motivo'];
                                            $obs_op = str_replace("'","",$_POST['obs_op']);

                                            $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
                                            $link->set_charset('utf8');


                                            if ($_POST['tipo'] === 'emergencia'){

                                                $tipo = '0';

                                            }else{

                                                $tipo = '1';
                                            }

                                            $query_descobre_sede = "SELECT * FROM sede WHERE user_sede = '$usuario'";
                                            $result_descobre_sede = $link->query($query_descobre_sede);
                                            $row_sede = mysqli_fetch_object($result_descobre_sede);
                                            $sede = $row_sede->sede;
                                            $id_usersede = $row_sede->sede_id;

                                            if ($sede === '0') { // mudei aqui

                                                $query_descobre_sede2 = "SELECT G.id_sede ID_SEDE FROM escola E, gre G WHERE E.id_escola = '$id_escola' AND E.gre = G.id_gre";
                                                $result_descobre_sede2 = $link->query($query_descobre_sede2);
                                                $row_sede2 = mysqli_fetch_object($result_descobre_sede2);
                                                $sede = $row_sede2->ID_SEDE;
                                            }

                                            //echo "<script>alert('$sede')</script>";

                                            
                                            

                                            try{

                                                $DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza'));
                                                $DT_atual = $DT_atual->format('Y-m-d h:m:i');

                                                $DT = new DateTime($_POST['data']);
                                                $DT = $DT->format('Y-m-d h:m:i');
                                                $dt_abertura = date('d/m/Y', strtotime($DT));

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

											if (!$link){

											  die('Connect Error (' . mysqli_connecterrno() . ')' .
											    mysqli_connect_error());

											}else{

                                                // check if the ticket already exist

                                                $chamado_aberto = checkExistenciaChamado($id_escola, $id_sub_motivo, $link);

                                                // check last protocol

                                                $novo_protocolo = checkUltimoProtocolo($tipo, $sede, $id_escola, $link);

                                                $data_hoje = explode(" ", $DT_atual)[0];
                                                $data_abertura = explode(" ", $DT)[0];

                                                if (strtotime($data_abertura) > strtotime($data_hoje)) {
                                                    ?>

                                                    <div class="input-field col s12">
                                                        <div class="alert alert-danger">
                                                            <strong>A data de abertura não pode ser maior que a data de hoje!</strong>
                                                        </div>            
                                                    </div>

                                                    <?php
                                                }else if ($chamado_aberto == 'N'){ 

                                                    $query_add_chamado = "INSERT INTO `ordem_servico`(`protocolo`, `tipo_chamado`, `status`, `dt_abertura`, `sla_atendimento`, `sla_conclusao`, `avaliacao`, `fk_id_motivo_os`, `fk_id_nome_escola`, `fk_id_sede`, `obs_operador`) VALUES ('$novo_protocolo','$tipo', '1', '$DT', 0, 0, 0, '$id_sub_motivo', '$id_escola', '$sede', '$obs_op')";

                                                    // 0=todos 1=aberto 2=em_atendimento 3=finalizado

        											$result_add_chamado = $link->query($query_add_chamado) or die( mysqli_error( $link ) );

													if($result_add_chamado == TRUE){

                                                        //echo "<script>alert('$sede')</script>";

                                                        $query_add_log = "INSERT INTO `log_acoes`(`id_acao`, `data_acao`, `descricao_acao`, `fk_id_user`) VALUES (NULL,'$DT_atual','Abertura da OS: $novo_protocolo','$id_usersede')";
                                                        $result_add_log = $link->query($query_add_log) or die( mysqli_error( $link ) );

										  
												  		?>

														<div class="input-field col s12">
                                                            <?php

                                                                $query_os_inserida = "SELECT * FROM ordem_servico O WHERE protocolo = '$novo_protocolo'";
                                                                $result_os_inserida = $link->query($query_os_inserida);
                                                                $row_os_inserida = mysqli_fetch_object($result_os_inserida);
                                                                $os_inserida = $row_os_inserida->id_os;
                                                            
                                                            ?>
											                <div class="alert alert-success">
											                    <strong>Chamado Registrado com Sucesso!</strong>
                                                                <p>Protocolo: <strong><?php echo ($novo_protocolo); ?></strong></p>
                                                                <p>Data abertura: <strong><?php echo ($dt_abertura); ?></strong></p>
											                    <p>Você pode acompanhar o andamento deste chamado no menu "Meus Chamados".</p>
											                </div>
                                                            <div class="input-field col s3">
                                                                <a href="../setor_ver_os.php?id_os=<?php echo $os_inserida; ?>" class="waves-effect waves-light btn custom-back">Ver o Chamado</a>
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
                                                        <?php

                                                            $query_descobre_os = "SELECT * FROM ordem_servico WHERE fk_id_nome_escola = '$id_escola' AND fk_id_motivo_os = '$id_sub_motivo' AND status != 3";
                                                            $result_descobre_os = $link->query($query_descobre_os);
                                                            $row_os = mysqli_fetch_object($result_descobre_os);
                                                            $os = $row_os->id_os;

                                                        ?>
                                                        <div class="alert alert-danger">
                                                            <strong>Já existe um chamado aberto com o mesmo motivo.</strong>
                                                            <p>É necessário aguardar a conclusão do chamado em andamento.</p>
                                                        </div> 
                                                        <div class="input-field col s3">
                                                            <a href="../setor_ver_os.php?id_os=<?php echo $os; ?>" class="waves-effect waves-light btn custom-back">Ver o Chamado</a>
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