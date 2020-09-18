 
<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($_SESSION['user_name']) && !is_numeric($_SESSION['user_name']) && isset($_POST['id_os']) ){

    $id_os = $_POST['id_os'];
    include('../libraries/feriado.php');
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
    <?php include("setor_menu.php"); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">
                    FECHAMENTO DE OS
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

                            //data tecnico local servico

								if(!empty($_POST)){  

									if(isset($_POST['id_os']) && isset($_POST['data']) && isset($_POST['nota']) && isset($_FILES['arquivo']['name'])){

										if(!empty($_POST['id_os']) && !empty($_POST['data']) && !empty($_POST['nota']) && !empty($_FILES['arquivo'])){

											$_UP['pasta'] = '../documents/';
											$pathToSave = $_UP['pasta'];
											/*Checa se a pasta existe - caso negativo ele cria*/
											if (!file_exists($pathToSave)) {
    											mkdir($pathToSave);
											}	

											// Tamanho máximo do arquivo (em Bytes)
											$_UP['tamanho'] = 1024 * 1024 * 20; // 20Mb post_max_size=20M alterar no php ini
											// Array com as extensões permitidas
											$_UP['extensoes'] = array('pdf');
											
											// Array com os tipos de erros de upload do PHP
											$_UP['erros'][0] = 'Não houve erro';
											$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
											$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
											$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
											$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
											// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro #################################################### 1

											// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar #################################################### 2
											// Faz a verificação da extensão do arquivo
											$arq = explode('.', $_FILES['arquivo']['name']);
											$file_name = explode('.', $_FILES['arquivo']['name'])[0];
											$extensao = strtolower(end($arq));

											$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
	                                        $link->set_charset('utf8');

	                                        if (!$link){
												die('Connect Error (' . mysqli_connecterrno() . ')' .
												mysqli_connect_error());
											}else{ 

												$id_os = $_POST['id_os'];

												$result_os = mysqli_query($link, "SELECT * from ordem_servico where id_os = '$id_os'");
												$row_os = mysqli_fetch_object($result_os);
	                                            $protocolo = $row_os->protocolo;
	                                            $dt_abertura = $row_os->dt_abertura;



											}

											if ($file_name != $protocolo) {
												?>
												<div class="input-field col s12">
								                	<div class="alert alert-danger">
								                    	<strong>Por favor, apenas envie arquivos com nome identico ao protocolo da OS seguindo padrão YY00000 ou YYEX00000!</strong>
								                	</div>            
								            	</div>

								            <?php


											}else if ($_FILES['arquivo']['error'] != 0) {
												?>
												<div class="input-field col s12">
								                	<div class="alert alert-danger">
								                    	<strong><?php die("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['arquivo']['error']]); exit; ?></strong>
								                	</div>            
								            	</div>

								            <?php


											}else if (array_search($extensao, $_UP['extensoes']) === false) {
												?>
												<div class="input-field col s12">
								                	<div class="alert alert-danger">
								                    	<strong>Por favor, apenas envie arquivos com a extensão PDF</strong>
								                	</div>            
								            	</div>
											<?php

											 
											}else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
												?>
												<div class="input-field col s12">
								                	<div class="alert alert-danger">
								                    	<strong>O arquivo enviado é muito grande, envie arquivos de até 20Mb.</strong>
								                	</div>            
								            	</div>
											<?php

											}else if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $_FILES['arquivo']['name'])) {

												rename("../documents/".$_FILES['arquivo']['name'], "../documents/".$protocolo.".pdf");

												
	                                            $data = $_POST['data'];
	                                            $nota = 0;
	                                            if ($_POST['nota'] === 'dois'){
	                                                $nota = 2;
	                                            }else if($_POST['nota'] === 'quatro'){
	                                                $nota = 4;
	                                            }else if($_POST['nota'] === 'seis'){
	                                                $nota = 6;
	                                            }else if($_POST['nota'] === 'oito'){
	                                                $nota = 8;
	                                            }else if($_POST['nota'] === 'dez'){
	                                                $nota = 10;
	                                            }

												if (!$link){

												  die('Connect Error (' . mysqli_connecterrno() . ')' .
												    mysqli_connect_error());

												}else{ 

	                                                
	                                                $vencimento_sla_c = DiasUteis(converte_date($dt_abertura),converte_date($data));
	                                                $query_update_os = "UPDATE `ordem_servico` SET `status`='3', `dt_conclusao` = '$data', `avaliacao` = '$nota', `sla_conclusao` = '$vencimento_sla_c' WHERE `id_os` = '$id_os'";
	    											$result_update_os = $link->query($query_update_os) or die( mysqli_error($link));

													if($result_update_os == TRUE){

														$DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
    													$DT_atual = $DT_atual->format('Y-m-d h:m:i');

    
    													$query_descobre_sede = "SELECT * FROM sede S WHERE S.user_sede = '$usuario'";
   	 													$result_descobre_sede = $link->query($query_descobre_sede);
    													$row_sede = mysqli_fetch_object($result_descobre_sede);
    													$id_usersede = $row_sede->sede_id;


    													$query_add_log = "INSERT INTO `log_acoes`(`id_acao`, `data_acao`, `descricao_acao`, `fk_id_user`) VALUES (NULL,'$DT_atual','Fechamento da OS: $protocolo','$id_usersede')";
    													$result_add_log = $link->query($query_add_log) or die( mysqli_error( $link ) );

    													$query_delete_tec_os = "DELETE FROM `tecnicos_os` WHERE fk_id_os = '$id_os'";
    													$result_add_log = $link->query($query_delete_tec_os) or die( mysqli_error( $link ) );

														?>

														<div class="input-field col s12">
											                <div class="alert alert-success">
											                    <strong>OS Fechada com Sucesso!</strong>                                                            
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
												                 <strong>A OS não foi fechada. Tente novamente.</strong>
												             </div>            
												         </div>
												         <?php
													}  
													mysqli_close($link);  
	                                            }
											} else {
												?>
												<div class="input-field col s12">
								                	<div class="alert alert-danger">
								                    	<strong>Não foi possível enviar o arquivo, tente novamente.</strong>
								                	</div>            
								            	</div>
											<?php
											
											}
											
										}else{

											?>

											<div class="input-field col s12">
								                <div class="alert alert-danger">
								                    <strong>Algum problema no formulário 1 !</strong>
								                </div>            
								            </div>

								            <?php
										}
									}else{


										?>

										<div class="input-field col s12">
								            <div class="alert alert-danger">
								                <strong>Algum problema no formulário 2 !</strong>
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
						                    <strong>Algum problema no formulário 3 !</strong>
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