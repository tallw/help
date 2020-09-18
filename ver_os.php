<?php

$id_os = $_GET['id_os'];

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$query_OS = "SELECT * FROM ordem_servico WHERE id_os = '$id_os'";
$result_OS = $link->query($query_OS);
$row_OS = mysqli_fetch_object($result_OS);
$id_escola = $row_OS->fk_id_nome_escola;
$protocolo = $row_OS->protocolo;

$query_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'";
$result_escola = $link->query($query_escola);
$row_escola = mysqli_fetch_object($result_escola);
$inep_escola = $row_escola->inep;


if(isset($usuario) && $usuario == $inep_escola && is_numeric($usuario)){

?>


<!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>HELP-ECOS | Sistema de Apoio às Escolas</title>
    	
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
        <!-- Google Fonts-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <link rel="stylesheet" href="assets/js/Lightweight-Chart/cssCharts.css"> 

        <!-- ################################ DA TIMELINE ################################# -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css" />
        <link rel="stylesheet" href="dist/vertical-timeline.css">
        <!-- ################################################################################ -->
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
                    <a href="edita_user.php"><i class="fa fa-key fa-fw"></i> Alterar Senha</a>
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
                            <a class="waves-effect waves-dark" href="school_dashboard.php"><i class="fa fa-dashboard"></i> Painel</a>
                        </li>
                        <li>
                            <a href="#" class="active-menu waves-effect waves-dark"><i class="fa fa-ticket"></i> Chamados<span class="fa arrow"></span></a>  
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="abrir_chamado.php">Abrir Chamado</a>
                                </li>
                                <li>
                                    <a href="meus_chamados.php?filtro=0">Meus Chamados</a>
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
                        Informações do protocolo: <strong><?php echo $protocolo; ?></strong>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você tem informações gerais do chamado.</li>
                    </ol> 
                                        
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                             Dados do Chamado
                        </div> 
                        <div class="card-content">
                            <div class="table-responsive">

                                <?php 

                                    $id_escola = $row_OS->fk_id_nome_escola;

                                    $dt_abertura = date_create( $row_OS->dt_abertura);
                                    $data = date_format($dt_abertura, 'd/m/Y');

                                    $status = $row_OS->status;
                                    $status_txt = '';
                                    if ($status == '1') {
                                        $status_txt = 'Aberto';
                                    }else if ($status == '2') {
                                        $status_txt = 'Em Andamento';
                                    }else{
                                        $status_txt = 'Finalizado';
                                    }

                                    $id_motivo = $row_OS->fk_id_motivo_os;
                                    $query_motivo = "SELECT * FROM sub_motivo_chamado where id_sub_motivo = '$id_motivo'";
                                    $result_motivo = $link->query($query_motivo);
                                    $row_motivo = mysqli_fetch_object($result_motivo);
                                    $motivo = $row_motivo->sub_motivo;

                                    $id_mot = $row_motivo->fk_id_motivo_chamado;
                                    $query_mot = "SELECT * FROM motivo_chamado where id_motivo = '$id_mot'";
                                    $result_mot = $link->query($query_mot);
                                    $row_mot = mysqli_fetch_object($result_mot);
                                    $id_dep = $row_mot->fk_id_departamento;
                                    $query_dep = "SELECT * FROM departamento where id_departamento = '$id_dep'";
                                    $result_dep = $link->query($query_dep);
                                    $row_dep = mysqli_fetch_object($result_dep);
                                    $dep = $row_dep->nome_departamento;


                                    $nome_escola = $row_escola->nome_escola;
                                    $gestor_escola = $row_escola->responsavel;


                                ?>
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <td><b>Nº Protocolo:</b></td>
                                        <td><?php echo $protocolo ?></td>
                                        <td><b>Status: </b></td>
                                        <td><?php echo $status_txt ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Data: </b></td>
                                        <td><?php echo $data ?></td>
                                        <td><b>Montivo: </b></td>
                                        <td><?php echo $motivo ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Departamento: </b></td>
                                        <td><?php echo $dep ?></td>
                                        <td><b>Gestor: </b></td>
                                        <td><?php echo $gestor_escola ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Escola: </b></td>
                                        <td colspan="3"><?php echo $nome_escola ?></td>
                                        
                                    </tr>
                                    
                                </table>

                            </div>
                            
                        </div>
                    </div>

                    <!-- End Advanced Tables -->

                    <div class="card">
                        <div class="card-action">
                             Linha do tempo:
                        </div> 
                        <div class="card-content">
                            
                            <!-- ################################################################## TIMELINE ################################################################### -->
                            <center><span class="badge"><?php echo $protocolo; ?></span></center>
                                <div id="vt6">
                                    <?php

                                        $query_execs = "SELECT * FROM execucao_diaria where fk_id_ordem_servico = '$id_os'";
                                        $result_execs = $link->query($query_execs);

                                        if(mysqli_num_rows($result_execs) > 0){
                                        	$cont = 1;
                                            while($row_execs = mysqli_fetch_object($result_execs)) { 

                                                //$id_execucao = $row_execs->id_execucao;
                                                $data = date_create($row_execs->data_execucao);
                                                $data_execucao = date_format($data, 'd/m/Y');
                                                $relato = $row_execs->relato;

                                                echo "<div data-vtdate='$data_execucao'>
                                                        <h4>Serviços realizados dia $cont</h4>
                                                        <br>
                                                        <p>$relato</p>
                                                    </div>";  
                                                    $cont+=1;
                                            }
                                        }
                                    ?>       
                                </div><!-- End vt6 -->
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
                                <script src="dist/vertical-timeline.js"></script>
                                <script>
                                    $('#vt1').verticalTimeline();
                                    $('#vt2').verticalTimeline();
                                    $('#vt3').verticalTimeline({
                                        startLeft: false
                                    });
                                    $('#vt4').verticalTimeline({
                                        startLeft: false,
                                        arrows: false,
                                        alternate: false

                                    });
                                    $('#vt5').verticalTimeline({
                                        animate: 'fade'
                                    });
                                    $('#vt6').verticalTimeline({
                                        animate: 'slide'
                                    });
                                </script>
                        </div>
                        
                    </div>
                </div>
            </div>
                
                    <!-- /. ROW  -->
                </div>
                <!-- /. PAGE INNER  -->
            </div>
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
    
    </body>

    </html>

<?php
}else{

    header("location: ./index.php");
    exit();
}
?>