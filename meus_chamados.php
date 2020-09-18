<?php

$filtro = $_GET['filtro'];

require_once("config/db.php");

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];



if(isset($usuario) && is_numeric($usuario) && ($filtro == '1' || $filtro == '2' || $filtro == '3' || $filtro == '0')){

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

                <?php
                    $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

                    if (!$link){

                        die('Connect Error (' . mysqli_connecterrno() . ')' .
                        mysqli_connect_error());

                    }else{

                        $query_dados_escola = "SELECT * FROM escola WHERE inep = '$usuario'";
                        $result_get_escola = $link->query($query_dados_escola);
                        $row_escola = mysqli_fetch_object($result_get_escola);
                        $id_escola = $row_escola->id_escola;

                        $query_OSs = "";
                        $status = '';

                        if ($filtro == '0') {
                            $query_OSs = "SELECT * FROM ordem_servico where fk_id_nome_escola = '$id_escola' and tipo_chamado = 0";
                            $status = 'Todos';
                        }else if ($filtro == '1') {
                            $query_OSs = "SELECT * FROM ordem_servico where fk_id_nome_escola = '$id_escola' AND status = '1' and tipo_chamado = 0";
                            $status = 'Abertos'; 
                        }else if ($filtro == '2') {
                            $query_OSs = "SELECT * FROM ordem_servico where fk_id_nome_escola = '$id_escola' AND status = '2' and tipo_chamado = 0";
                            $status = 'Em Andamento'; 
                        }else if ($filtro == '3') {
                            $query_OSs = "SELECT * FROM ordem_servico where fk_id_nome_escola = '$id_escola' AND status = '3' and tipo_chamado = 0";
                            $status = 'Finalizados'; 
                        }
                    }
                ?>
                    <h1 class="page-header">
                        Meus Chamados (<?php echo $status; ?>)
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você lista e visualiza seus chamados.</li>
                    </ol> 
                                        
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                             Tabela de Chamados
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Protocolo</th>
                                            <th>Departamento</th>
                                            <th>Motivo</th>
                                            <th>Data Abertura</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $result_OSs = $link->query($query_OSs);

                                            while($row_OSs = mysqli_fetch_object($result_OSs)) { 

                                                $id_os = $row_OSs->id_os;
                                                $protocolo = $row_OSs->protocolo;
                                                $dt_abertura = date_create( $row_OSs->dt_abertura);
                                                $data = date_format($dt_abertura, 'd/m/Y');

                                                $status = $row_OSs->status;
                                                $status_txt = '';
                                                if ($status == '1') {
                                                    $status_txt = 'Aberto';
                                                }else if ($status == '2') {
                                                    $status_txt = 'Em Andamento';
                                                }else{
                                                    $status_txt = 'Finalizado';
                                                }

                                                $id_motivo = $row_OSs->fk_id_motivo_os;
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

                                                echo "<tr class='odd gradeX'>
                                                        <td>$protocolo</td>
                                                        <td>$dep</td>
                                                        <td>$motivo</td>
                                                        <td>$data</td>";
                                                        if ($status == '1') {
                                                            echo "<td class='center' style='color: #d9534f'><b>$status_txt</b></td>";
                                                        }else if ($status == '2') {
                                                            echo "<td class='center' style='color: #f0ad4e'><b>$status_txt</b></td>";
                                                        }else{
                                                            echo "<td class='center' style='color: #5cb85c'><b>$status_txt</b></td>";
                                                        }
                                                        
                                                        echo "<td class='center'>
                                                            <a href='ver_os.php?id_os=$id_os'><i class='material-icons dp48'>visibility</i></a>
                                                            <br>";                                                            
                                                        echo 
                                                        "</td>
                                                    </tr>";   
                                            }                                      
                                        ?>        
                                    </tbody>
                                    
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
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

         <!-- DATA TABLE SCRIPTS -->
        <script src="assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
        </script>
        
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