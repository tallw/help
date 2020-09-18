<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];


if(isset($usuario) && !is_numeric($usuario)){ 

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
        

        <?php

            include("setor_menu.php");

            $tipo = $_GET['tipo'];

            $destino = "";

            $titulo = "";

            if ($tipo === '0') {
                $destino = "docs/gera_excel.php";
                $titulo = "Totais SLA por Sede";
            }else if($tipo === '1'){
                $destino = "docs/gera_excel2.php";
                $titulo = "Totais SLA por GRE";
            }

                    
        ?>
                           
    		<div id="page-wrapper">
    		  <div class="header"> 
                   <h1 class="page-header">
                        Relatório SLA TI
                    </h1> 					
    			</div>               
                <div id="page-inner">   
                    <div class="row">   
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-action">
                                     <?php echo $titulo; ?>
                                    <div class="tooltip"><i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Depois coloco uma mensagem aqui para você :D</span>
                                    </div>
                                </div> 

                                <div class="card">
                                
                                    <div class="card-image">
                                        <div class="collection">
                                            <form method="post" name="rel_quantitativo" action=<?php echo $destino; ?>>

                                                <div class="input-field col s2">
                                                    <b>Data inicial</b><input id="inicial" type="date" name="inicial">
                                                </div>

                                                <div class="input-field col s2">
                                                    <b>Data final</b><input id="final" type="date" name="final">
                                                </div>

                                                <div class="card-content"> 
                                                    <input class="btn btn-primary" type="submit" value="GERAR">
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-content">
                                    <div class="table-responsive">

                                        <table  class="table table-striped table-bordered table-hover" id="table_rel_sla" name="table_rel_sla">
                                                                        
                                        </table>

                                    </div>                                  
                                </div>
                            </div>    
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

        <script src="js_table_rel.js"></script>
        <script src="js_pdfrel.js"></script>
     

    </body>

    </html>

<?php
}else{

    header("location: ./index.php");
    exit();

}
?>