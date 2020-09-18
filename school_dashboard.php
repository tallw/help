<?php

/*require('config.php');*/

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($usuario) && is_numeric($usuario)){

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
                            <a class="active-menu waves-effect waves-dark" href="school_dashboard.php"><i class="fa fa-dashboard"></i> Painel</a>
                        </li>
                        <li>
                            <a href="#" class="waves-effect waves-dark"><i class="fa fa-ticket"></i> Chamados<span class="fa arrow"></span></a>  
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

            <?php

                    $link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');

                    if (!$link){

                        die('Connect Error (' . mysqli_connecterrno() . ')' .
                        mysqli_connect_error());

                    }else{

                        $query_dados_escola = "SELECT * FROM escola WHERE inep = '$usuario'";
                        $result_get_escola = $link->query($query_dados_escola);
                        $row_escola = mysqli_fetch_object($result_get_escola);
                        $id_escola = $row_escola->id_escola;



                        $query_os_aguardando = "SELECT * FROM ordem_servico WHERE status = '1' and fk_id_nome_escola = '$id_escola' and tipo_chamado = 0";
                        $query_os_abertos = "SELECT * FROM ordem_servico WHERE status = '2' and fk_id_nome_escola = '$id_escola' and tipo_chamado = 0";
                        $query_os_finalizados = "SELECT * FROM ordem_servico WHERE status = '3' and fk_id_nome_escola = '$id_escola' and tipo_chamado = 0";

                        

                        $query_os_aguardando = $link->query($query_os_aguardando);
                        $query_os_abertos = $link->query($query_os_abertos);
                        $result_os_finalizados = $link->query($query_os_finalizados);
                        

                        $query_os_aguardando = mysqli_num_rows($query_os_aguardando);
                        $query_os_abertos = mysqli_num_rows($query_os_abertos);
                        $qtde_os_finalizados = mysqli_num_rows($result_os_finalizados);

                    }

                    mysqli_close($link);

                ?>       
    		<div id="page-wrapper">
    		  <div class="header"> 
                   <h1 class="page-header">
                        Painel
                    </h1> 					
    			</div>               
                <div id="page-inner">   
                    <div class="row">   
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-action">
                                     Dados Escola
                                    <div class="tooltip"><i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Mantenha os dados da escola sempre atualizados</span>
                                    </div>
                                </div> 
                                
                                <div class="card-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <tr>
                                                <td><b>Escola: </b></td>
                                                <td colspan="3"><?php echo $row_escola->nome_escola; ?></td>
                                                
                                            </tr>
                                            <tr>
                                                <td><b>GRE: </b></td>
                                                <td><?php echo ($row_escola->gre.'ª GRE'); ?></td>
                                                <td><b>Cidade: </b></td>
                                                <td><?php echo $row_escola->cidade; ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Endereço: </b></td>
                                                <td colspan="3"><?php echo $row_escola->endereco; ?></td>
                                                
                                            </tr>
                                            <tr>
                                                <td><b>Responsável: </b></td>
                                                <td><?php echo $row_escola->responsavel; ?></td>
                                                <td><b>Contato 1: </b></td>
                                                <td><?php echo $row_escola->contato01; ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Contato 2: </b></td>
                                                <td><?php echo $row_escola->contato02; ?></td>
                                                <td><b>E-mail: </b></td>
                                                <td><?php echo $row_escola->email; ?></td>
                                            </tr>                                         
                                        </table>
                                    </div>                                  
                                </div>
                            </div>    
                        </div>      
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4" onclick="window.location.href='meus_chamados.php?filtro=1'">
    					
    						<div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image red">
                                    <i class="material-icons dp8">query_builder</i>
                                </div>
                                <div class="card-stacked">
                                   <div class="card-content">
                                       <h3><?php echo $query_os_aguardando; ?></h3> 
                                   </div>
                                   <div class="card-action">
                                       <strong> Abertos</strong>
                                   </div>
                                </div>
                            </div>
    	 
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4" onclick="window.location.href='meus_chamados.php?filtro=2'">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image orange">
                                    <i class="material-icons dp8">done</i>
                                </div>
                                <div class="card-stacked">
                                   <div class="card-content">
                                       <h3><?php echo $query_os_abertos; ?></h3> 
                                   </div>
                                   <div class="card-action">
                                       <strong> Em Andamento</strong>
                                   </div>
                                </div>
                            </div>  
                             
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4" onclick="window.location.href='meus_chamados.php?filtro=3'">
    					
    						<div class="card horizontal cardIcon waves-effect waves-dark">
    						    <div class="card-image green">
    						        <i class="material-icons dp8">done_all</i>
    						    </div>
    						    <div class="card-stacked">
    						        <div class="card-content">
    						            <h3><?php echo $qtde_os_finalizados; ?></h3> 
    						        </div>
        						    <div class="card-action">
        						        <strong> Finalizados</strong>
        						    </div>
                                </div>
    						</div> 
                        </div>
                                             
                    </div>
    			
                    <!-- /. ROW  -->
                    <div class="fixed-action-btn horizontal click-to-toggle">
                    
                        <div class="tooltip">
                            <a href="abrir_chamado.php" class="btn-floating btn-large red">
                                <!--<i class="fa fa-ticket"></i>-->
                                <i class="large material-icons">library_add</i>
                            </a>
                            
                                <span class="tooltiptextleft">Abrir Chamado</span>
                        </div>
                    
                    </div>
                </div>
                <!-- /. PAGE INNER  -->
            </div>
            <!-- /. PAGE WRAPPER  -->
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