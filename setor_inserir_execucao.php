<?php

//$id_os = $_GET['id_os'];

require_once("config/db.php");

$id_os = $_GET['id_os']; // tratar depois (não pode ver os dos outros)
$protocolo = $_GET['protocolo'];


if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');

$query_escola = "SELECT * FROM ordem_servico o, escola e WHERE o.id_os = '$id_os' and o.fk_id_nome_escola = e.id_escola";
$result_escola = $link->query($query_escola) or die(mysqli_error($link));
$row_result = mysqli_fetch_object($result_escola);
$id_escola = $row_result->id_escola;

$query_serial = "SELECT * FROM escola e, biometrias bi WHERE e.id_escola = '$id_escola' AND e.fk_id_biometria = bi.id_biometria";
$result_serial = $link->query($query_serial);

if (mysqli_num_rows($result_serial) > 0) {
    $serial = mysqli_fetch_object($result_serial)->serial_bio;
}else{
    $serial = "Não possui...";
}


if(isset($usuario) && isset($id_os) && !is_numeric($usuario)){ // fazer condicao pra garantir que a escola sera do operador

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

    <script type="text/javascript">
                            
        function verifica() { 

            if (document.getElementById('alterar').checked) {

                document.getElementById('data').disabled = true;
                document.getElementById('tecnico').disabled = true;
                document.getElementById('local').disabled = true;
                document.getElementById('servico').disabled = true;
                document.getElementById('observacao').disabled = true;
                document.getElementById('pendencias').disabled = true;
                document.getElementById('salva_exec').disabled = true;
                document.getElementById('alterar_s').style.display = "inline";

            }else{

                document.getElementById('data').disabled = false;
                document.getElementById('tecnico').disabled = false;
                document.getElementById('local').disabled = false;
                document.getElementById('servico').disabled = false;
                document.getElementById('observacao').disabled = false;
                document.getElementById('pendencias').disabled = false;
                document.getElementById('salva_exec').disabled = false;
                document.getElementById('alterar_s').style.display = "none";
            }
            
                    
               
        }

    </script> 
    <body>
        <?php include("setor_menu.php"); ?>
       
            <!-- /. NAV SIDE  -->

            <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        Adicionar Execução Diária <small>Protocolo: <?php echo $protocolo; ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você adiciona os serviços descritos na folha de execução.</li>
                    </ol>                         
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-action">
                             Dados da Execução:
                        </div> 
                        <div class="card-content">
                            
                            <form method="post" action="executions/grava_execucao.php" name="abrir_chamado">
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input type="date" name="data" id="data" required disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input type="text" name="tecnico" id="tecnico" placeholder='Nome do Técnico' required disabled>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="input-field col s3">
                                        <input value="<?php echo $serial; ?>" type="text" name="serial" placeholder='Serial da Biometria'>
                                    </div>
                                </div>  --> 

                                <div class="row">
                                    <div class="input-field col s3">
                                        <label><font size="4"> SERIAL: <?php echo $serial; ?></font></label>
                                    </div>

                                    <div class="input-field col s3">
                                        <p>
                                            <input type="radio" name="tipo" id="nalterar" value="nalterar" onclick="verifica();">
                                            <label for="nalterar">Não Alterar</label>
                                            <input type="radio" name="tipo" checked="checked" id="alterar" value="alterar" onclick="verifica();">
                                            <label for="alterar">Alterar</label>
                                        </p>
                                    </div>

                                    
                                        
                                    <div class="input-field col s3" id="alterar_s" name="alterar_s">
                                        <input type="button" class="waves-effect waves-light btn" value="alterar" onclick="window.location.href='altera_serial.php?id_os=<?php echo $id_os; ?>'">
                                    </div>
                                    

                                </div><br>


                                <div class="row">
                                    <div class="input-field col s10">
                                        <input type="text" id="local" name="local" placeholder='Local da Execução' required disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea class="form-control" rows="5" id="servico" name="servico" placeholder="Serviço realizado:" maxlength="10000" required disabled></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea class="form-control" rows="5" id="observacao" name="observacao" placeholder="Observações:" maxlength="10000" disabled></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea class="form-control" rows="5" id="pendencias" name="pendencias" placeholder="Pendências:" maxlength="10000" disabled></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" name="id_os" id="id_os" value="<?php echo $id_os; ?>">
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s3">
                                            <button type="submit" id="salva_exec" name="salva_exec" class="waves-effect waves-light btn" onClick="return validarcampos()" disabled>Inserir Execucao</button>
                                        </div>
                                        <div class="input-field col s3">
                                            <a href="setor_ver_os.php?id_os=<?php echo $id_os; ?>" class="waves-effect waves-light btn custom-back">Voltar</a>
                                        </div>
                                    </div>
                                </div>     
                            </form>       
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