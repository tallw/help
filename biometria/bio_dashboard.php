<?php

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($usuario) && !is_numeric($usuario)){

?>

    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <link rel="icon" href="../image/ecos_logo.jpg" type="image/x-icon" />

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>HELP-ECOS | Sistema de Apoio às Escolas</title>

        <!-- ############################################### SEARCH SELECT ##################################################  ecos_logo
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />

         ############################################### SEARCH SELECT ################################################## -->
    	
    	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    	<link rel="stylesheet" href="../assets/materialize/css/materialize.min.css" media="screen,projection"/>
        <!-- Bootstrap Styles-->
        <link href="../assets/css/bootstrap.css" rel="stylesheet"/>
        <!-- FontAwesome Styles-->
        <link href="../assets/css/font-awesome.css" rel="stylesheet"/>
        <!-- Morris Chart Styles-->
        <link href="../assets/js/morris/morris-0.4.3.min.css" rel="stylesheet"/>
        <!-- Custom Styles-->
        <link href="../assets/css/custom-styles.css" rel="stylesheet"/>
        <!-- Google Fonts-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link rel="stylesheet" href="../assets/js/Lightweight-Chart/cssCharts.css"> 


    </head>

    <script language="javascript">
        function toggleDiv(divid){
            if(document.getElementById(divid).style.display == 'none'){
                document.getElementById(divid).style.display = 'block';
            }else{
                document.getElementById(divid).style.display = 'none';
            }
        }
    </script>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    
    <body>
        
            <?php

                    include("setor_menu.php");

                    $link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
                    $link->set_charset('utf8');

                    if(!$link){

                        die('Connect Error (' . mysqli_connecterrno() . ')' .
                        mysqli_connect_error());

                    }else{

                        $query_todos = "SELECT * FROM `biometrias`";
                        $query_estoque = "SELECT * FROM `biometrias` WHERE status_bio = 1";
                        $query_escola = "SELECT * FROM `biometrias` WHERE status_bio = 2";
                        $query_sedejp = "SELECT * FROM `biometrias` WHERE status_bio = 3";
                        $query_garantia = "SELECT * FROM `biometrias` WHERE status_bio = 4";
                        $query_escola_sem_bio = "SELECT * FROM escola e, gre g WHERE e.fk_id_biometria = 0 AND e.gre = g.id_gre";

                        if($sede !== '0') {

                            $query_todos .= " WHERE sede_bio = '$sede'";
                            $query_estoque .= " AND sede_bio = '$sede'";
                            $query_escola .= " AND sede_bio = '$sede'";
                            $query_sedejp .= " AND sede_bio = '$sede'";
                            $query_garantia .= " AND sede_bio = '$sede'";
                            $query_escola_sem_bio.= " AND g.id_sede = '$sede'";
                        }

                        $todos = mysqli_num_rows($link->query($query_todos));
                        $estoque = mysqli_num_rows($link->query($query_estoque));
                        $escola = mysqli_num_rows($link->query($query_escola));
                        $sedejp = mysqli_num_rows($link->query($query_sedejp));
                        $garantia = mysqli_num_rows($link->query($query_garantia));
                        $escola_sem_bio = mysqli_num_rows($link->query($query_escola_sem_bio));

                        if ($sede === '0') {
                            $sede_tela = "Todas";
                        }else if ($sede === '1') {
                            $sede_tela = "CG";
                        }else if ($sede === '2') {
                            $sede_tela = "JP";
                        }else if ($sede === '3') {
                            $sede_tela = "SS";
                        }

                    }                           
                        
                        

                ?>
          
    		<div id="page-wrapper">
                <div class="header"> 
                    <h1 class="page-header">
                        Painel
                    </h1> 
                    <ol class="breadcrumb">
                        <li>Departamento de Tecnologia da Informação - Informações sobre as Biometrias (<?php echo $sede_tela; ?>).</li>
                    </ol>  									
    			</div>



                <div id="page-inner">   

                    <!-- /. ROW  -->
                    <div class="fixed-action-btn horizontal click-to-toggle">
                    
                        <div class="tooltip">
                            <a href="inserir_bio.php" class="btn-floating btn-large red">
                                <!--<i class="fa fa-ticket"></i>-->
                                <i class="large material-icons">library_add</i>
                            </a>
                            
                            <span class="tooltiptextleft">Inserir Bio</span>
                        </div>
                    
                    </div>




                    <div class="alert-message"> </div>

                <div class="card">

                    <div class="card-action">
                            <b><i class="material-icons left">dashboard</i>DASHBOARDS BIO:</b>
                            <a href="javascript:;" onmousedown="toggleDiv('div_dash_bio');"><i class='material-icons dp48'>aspect_ratio</i></a>
                    </div>

                    <div class="divider"></div>

                    <div class="row" id="div_dash_bio">

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('lista_bio.php?filtro=0')">

                            <div class="card horizontal cardIcon waves-effect waves-dark">

                                <div class="card-image blue">
                                    <i class="material-icons dp8">thumb_up</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $todos; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Todas</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('lista_bio.php?filtro=1')">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image orange">
                                    <i class="material-icons dp8">vpn_key</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $estoque; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Em Estoque</strong>
                                    </div>
                                </div>
                            </div>       
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('lista_bio.php?filtro=2')">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image green">
                                    <i class="material-icons dp8">done_all</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $escola; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Em Escola</strong>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('lista_bio.php?filtro=3')">

                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image red">
                                    <i class="material-icons dp8">done</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $sedejp; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Sede JP (Defeito)</strong>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('lista_bio.php?filtro=4')">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image black">
                                    <i class="material-icons dp8">query_builder</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $garantia; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Garantia (Defeito)</strong>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('lista_escola_sem_bio.php')">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image pink">
                                    <i class="material-icons dp8">live_help</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $escola_sem_bio; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Escolas Sem Biometria</strong>
                                    </div>
                                </div>
                            </div> 
                        </div>

                    </div>
                </div>

                <?php

                if ($sede === '0') {

                    $tot_cg = mysqli_num_rows($link->query("SELECT * FROM biometrias WHERE status_bio = 2 AND sede_bio = 1"));
                    $tot_jp = mysqli_num_rows($link->query("SELECT * FROM biometrias WHERE status_bio = 2 AND sede_bio = 2")); 
                    $tot_ss = mysqli_num_rows($link->query("SELECT * FROM biometrias WHERE status_bio = 2 AND sede_bio = 3")); 

                    $tot_sedes = $tot_cg + $tot_jp + $tot_ss;

                    $percent_cg = number_format($tot_cg / ($tot_sedes/100), 2, '.', '');
                    $percent_jp = number_format($tot_jp / ($tot_sedes/100), 2, '.', '');
                    $percent_ss = number_format($tot_ss / ($tot_sedes/100), 2, '.', '');

                ?>

                <div class="card"> <!-- ############################################################## Não finalizadas ################################################### -->
                    <div class="card-action">
                        <b><i class="material-icons left">warning</i>Biometrias em Escola:</b>
                        <a href="javascript:;" onmousedown="toggleDiv('div_bio_esc');"><i class='material-icons dp48'>aspect_ratio</i></a>
                    </div>
                    <div class="divider"></div>                    
                    <div class="row" id="div_bio_esc">

                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>Todas as Sedes</h4>
                                <div class="easypiechart" id="easypiechart-orange" data-percent="100" ><span class="percent">100%</span>
                                </div> 
                                <div class='tooltip'>
                                    <a href='lista_bio.php?filtro=5' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $tot_sedes; ?> Biometrias</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>Campina Grande</h4>
                                <div class="easypiechart" id="easypiechart-blue" data-percent="<?php echo $percent_cg; ?>" ><span class="percent"><?php echo $percent_cg; ?>%</span>
                                </div> 
                                <div class='tooltip'>
                                    <a href='lista_bio.php?filtro=6' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $tot_cg; ?> Biometrias</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>João Pessoa</h4>
                                <div class="easypiechart" id="easypiechart-red" data-percent="<?php echo $percent_jp; ?>" ><span class="percent"><?php echo $percent_jp; ?>%</span>
                                </div>
                                <div class='tooltip'>
                                    <a href='lista_bio.php?filtro=7' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $tot_jp; ?> Biometrias</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>Sousa</h4>
                                <div class="easypiechart" id="easypiechart-teal" data-percent="<?php echo $percent_ss; ?>" ><span class="percent"><?php echo $percent_ss; ?>%</span>
                                </div>
                                <div class='tooltip'>
                                    <a href='lista_bio.php?filtro=8' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $tot_ss; ?> Biometrias</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>
                        </div>
                    </div><!--/.row-->
                </div>

                

        <!-- ################################################################################################################################################################################################################## -->

                <?php } ?>
                    <!-- /. ROW  --> 
                    </div>
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

    header("location: ./index.php");
    exit();

}
?>