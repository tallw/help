<?php

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];


if(isset($usuario)  && !is_numeric($usuario)){ // tratar para que não possa ser visto OSs de outro setores passando id na url (verificar se escola pertence ao user)

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

        <!-- ################################ DA TIMELINE ################################# -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css" />
        <link rel="stylesheet" href="../dist/vertical-timeline.css">
        <!-- ################################################################################ -->
    </head>

    <!-- For pending status -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>

    <script language="javascript">

        function toggleDiv(divid){
            if(document.getElementById(divid).style.display == 'none'){
                document.getElementById(divid).style.display = 'block';
            }else{
                document.getElementById(divid).style.display = 'none';
            }
        }

    </script>


    <script type="text/javascript">
           
        function botoes(){

            var id_bio = document.getElementById('id_bio').value;

           //document.getElementById("btn_del").innerHTML = "<a id='del' href='#' class='collection-item'><span class='badgeimp_end'>Excluir</span></a>";
            document.getElementById("btn_edit").innerHTML = "<a id='edit' href='edita_bio.php?id_bio=" + id_bio + "' class='collection-item'><span class='badgeimp_end'>Editar</span></a>";
            document.getElementById("btn_csv").innerHTML = "<a id='csv' href='gera_csv_bio.php?id_bio=" + id_bio + "' class='collection-item'><span class='badgeimp_end'>CSV</span></a>";

        }

    </script>

    <body onload="botoes()">
        
        <?php 

        include("setor_menu.php"); 
        $id_bio = $_GET['id_bio'];
        $row_bio = mysqli_fetch_object($link->query("SELECT * FROM biometrias WHERE id_biometria = '$id_bio'"));
        $serial = $row_bio->serial_bio;

        ?>

            <div id="page-wrapper">

                <div class="header"> 
                    <h1 class="page-header">
                        Informações da biometria: <strong><?php echo $serial; ?></strong>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você tem informações gerais da biometria.</li>
                    </ol> 
                                        
                </div>

                <div id="page-inner">

                    <div class="row">
                        
                        <?php 

                        $patrimonio = $row_bio->patrimonio_bio;

                        $status_bio = $row_bio->status_bio;
                        $status_txt = '';
                        $escola_bio = 'Não está em Escola';

                        if ($status_bio === '1') {
                            $status_txt = 'Em Estoque';
                        }else if ($status_bio === '2') {
                            $status_txt = 'Em Escola';
                            $escola_bio = mysqli_fetch_object($link->query("SELECT * FROM escola WHERE fk_id_biometria = '$id_bio'"))->nome_escola;
                        }else if ($status_bio === '3') {
                            $status_txt = 'Em Sede JP (Defeito)';
                        }else if ($status_bio === '4') {
                            $status_txt = 'Em Garantia (Defeito)';
                        }

                        $id_sede_bio = $row_bio->sede_bio;
                        if ($id_sede_bio === '1') {
                            $sede_bio = 'CG';
                        }else if ($id_sede_bio === '2') {
                            $sede_bio = 'JP';
                        }else if ($id_sede_bio === '3') {
                            $sede_bio = 'SS';
                        }

                        ?>
                        
                        <div class="col-md-7 col-sm-12 col-xs-12">

                            <div class="card">
                                <div class="card-action">
                                    <b><i class="material-icons left">info_outline</i>Dados da Biometria</b>
                                    <a href="javascript:;" onmousedown="toggleDiv('div_dados_bio');"><i class='material-icons dp48'>aspect_ratio</i></a>  
                                </div>
                                <div class="divider"></div>
                                <div class="card-image" id="div_dados_bio">
                                    <ul class="card-content">

                                        <li><p><strong>Nº Serial: </strong><?php echo $serial;?></p></li>
                                        <li><p><strong>Patrimônio: </strong><?php echo $patrimonio;?></p></li>
                                        <li><p><strong>Status: </strong><?php echo $status_txt;?></p></li>
                                        <li><p><strong>Alocada na Escola: </strong><?php echo $escola_bio;?></p></li>
                                        <li><p><strong>Pertence a Sede: </strong><?php echo $sede_bio;?></p></li>
                    
                                    </ul>
                                </div>
                            </div>
                        </div> 

                        
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-action">
                                    <b>Ações<i class="material-icons left">input</i></b>
                                    <a href="javascript:;" onmousedown="toggleDiv('div_input');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                </div>
                                <div class="card-image" id="div_input">
                                    <div class="collection">

                                        <input type="hidden" id="id_bio" name="id_bio" value="<?php echo $id_bio; ?>" >
                                        
                                        <div id='btn_del'></div>
                                        <div id='btn_edit'></div>
                                        <div id='btn_csv'></div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-action">
                                     <b><i class="material-icons left">restore</i>Linha do Tempo:</b>
                                     <a href="javascript:;" onmousedown="toggleDiv('div_timeline');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                </div> 
                                <div class="divider"></div>
                                <div class="card-content" id="div_timeline">
                                    
                                    <!-- ################################################################## TIMELINE ################################################################### -->

                                    <center><span class="badge"><?php echo $serial; ?></span></center>
                                    <div id="vt6">
                                        <?php

                                            $query_history = "SELECT * FROM `history_serial` WHERE fk_id_serial = '$id_bio' ORDER BY data_mudanca";
                                            $result_history = $link->query($query_history);

                                            if(mysqli_num_rows($result_history) > 0){
        
                                                while($row_history = mysqli_fetch_object($result_history)) { 

                                                    $data = date_create($row_history->data_mudanca);
                                                    $data_mudanca = date_format($data, 'd/m/Y');

                                                    $status_history = $row_history->fk_status_bio;

                                                    if ($status_history === '1') {
                                                        $relato_history = "Biometria enviada para o Estoque.";
                                                    }else if ($status_history === '2') {
                                                        $id_escola = $row_history->fk_id_escola_serial;
                                                        $escola_history = mysqli_fetch_object($link->query("SELECT * FROM escola WHERE id_escola = '$id_escola'"))->nome_escola;
                                                        $relato_history = "Biometria enviada para a Escola: .".$escola_history;
                                                    }else if ($status_history === '3') {
                                                        $relato_history = "Biometria enviada para a Sede JP com Defeito.";
                                                    }else if ($status_history === '4') {
                                                        $relato_history = "Biometria enviada para Garantia (Defeito).";
                                                    }

                                                   
                                                    echo "<div data-vtdate='$data_mudanca'>
                                                            <h4>Status Ação:</h4>
                                                            <br>";

                                                    echo "<p>$relato_history</p>";                                       

                                                    echo "</div>";
                                                    
                                                }  
                                            }
                                        ?>     
                                    </div><!-- End vt6 -->
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
                                    <script src="../dist/vertical-timeline.js"></script>
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