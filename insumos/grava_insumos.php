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
                    Adicionar Levantamento
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

    									if(isset($_POST['data']) && isset($_POST['observacao']) && $_POST['item'][0] !== ''){

    										if(!empty($_POST['data']) && !empty($_POST['observacao'])){

                                                $array_itens = array();
                                                $array_qtdes = array();
                                                $array_refs = array();
                                                $array_sedes = array();

                                                $data = $_POST['data'];

                                                $observacao = str_replace("'","",$_POST['observacao']);
                                                
                                                $item = $_POST['item'];
                                                foreach ($item as $key1 => $value1) { // #################### PEGANDO Os itens
                                                    array_push($array_itens, $value1);
                                                }

                                                $qtde = $_POST['qtde'];
                                                foreach ($qtde as $key1 => $value1) { // #################### PEGANDO Os Qtdes
                                                    array_push($array_qtdes, $value1);
                                                }

                                                $ref = $_POST['ref'];
                                                foreach ($ref as $key1 => $value1) { // #################### PEGANDO Os Refs
                                                    array_push($array_refs, $value1);
                                                }

                                                $sede = $_POST['sede'];
                                                foreach ($sede as $key1 => $value1) { // #################### PEGANDO Os Setores
                                                    array_push($array_sedes, $value1);
                                                }

                                                $status = '0';
                                                
                                                $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
                                                $link->set_charset('utf8');

                                                if (!$link){

                                                  die('Connect Error (' . mysqli_connecterrno() . ')' .
                                                    mysqli_connect_error());

                                                }else{

                                                    $query_sede = "SELECT * FROM sede WHERE user_sede = '$usuario'";
                                                    $result_sede = $link->query($query_sede);
                                                    $row_sede = mysqli_fetch_object($result_sede);
                                                    $id_sede = $row_sede->sede_id;

                                                    $query_insert_cotacao = "INSERT INTO `cotacoes_insumos`(`id_cot_insumos`, `status_cot_insumos`, `dt_cot_insumos`, `obs_cotacao`, `fk_id_user_insu`) VALUES (NULL,'0','$data','$observacao',' $id_sede')";

                                                    $result_insert_cotacao = $link->query($query_insert_cotacao) or die(  mysqli_error( $link ) );

                                            
                                                    if($result_insert_cotacao == TRUE){

                                                        $id_cotacao = mysqli_insert_id($link);

                                                        for ($i=0; $i < count($array_itens); $i++) { 

                                                            $referencia = $array_refs[$i];
                                                            $quantidade = $array_qtdes[$i];
                                                            $local_destino = $array_sedes[$i];
                                                            $fk_id_item = $array_itens[$i];

                                                            $query_insert_insumo_cotado = "INSERT INTO `insumos_cotados`(`id_insumo_cotado`, `referencia_insumo`, `qtde_insumo`, `destino_insumo`, `fk_id_insumo`, `fk_id_cot_insumo`) VALUES (NULL,'$referencia','$quantidade', $local_destino, '$fk_id_item','$id_cotacao')";
                                                            $result_insert_insumo_cotado = $link->query($query_insert_insumo_cotado) or die( mysqli_error( $link ) );
                                                        }


                                                        //########################################################## LOG ############################################

                                                        
                                                        $DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
                                                        $DT_atual = $DT_atual->format('Y-m-d h:m:i');

                                                        $query_add_log = "INSERT INTO `log_acoes`(`id_acao`, `data_acao`, `descricao_acao`, `fk_id_user`) VALUES (NULL,'$DT_atual','Cotação de insumos','$id_sede')";
                                                        $result_add_log = $link->query($query_add_log) or die( mysqli_error( $link ) );

                                                        //###################################################################################################################

                                                        
                                                            ?>

                                                            <div class="input-field col s12">
                                                                <div class="alert alert-success">
                                                                    <strong>Cotação se insumos Registrada com Sucesso!</strong>
                                                                </div>
                                                                <div class="input-field col s3">
                                                                    <a href="cad_insumos.php" class="waves-effect waves-light btn custom-back">Voltar</a>
                                                                </div>
                                                            </div>
                                                            
                                                            <?php

                                                    }else{

                                                            ?>

                                                            <div class="input-field col s12">
                                                                <div class="alert alert-danger">
                                                                    <strong>A cotação de insumos não foi registrada. Tente novamente.</strong>
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