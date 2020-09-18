<?php

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];
$id_escola = $_GET['id_escola'];

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');


if(isset($usuario)  && !is_numeric($usuario)){ // tratar para que não possa ser visto OSs de outro setores passando id na url (verificar se escola pertence ao user)

?>

<!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>HELP-ECOS | Sistema de Apoio às Escolas</title>

        <!-- ############################################### SEARCH SELECT ################################################## -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
        <!--<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" rel="stylesheet" />-->

        <!-- ############################################### SEARCH SELECT ################################################## -->
        
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
        <!-- Search select -->
        <link href="dist/css/bootstrap-select.min.css" rel="stylesheet" />
        <!-- Google Fonts-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <link rel="stylesheet" href="assets/js/Lightweight-Chart/cssCharts.css"> 

        <!-- ################################ DA TIMELINE ################################# -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css" />
        <link rel="stylesheet" href="dist/vertical-timeline.css">
        <!-- ################################################################################ -->
    </head>
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="js_tudao.js"></script>
    <script type="text/javascript" src="js_tudao2.js"></script>

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
        
        function chama_change(){
            var escola_id = document.getElementById('id_escola').value;
            //alert(motivo_id);
            if(escola_id != "") {
                $.ajax({
                    url:"gera_dados_escola.php",
                    data:{c_id:escola_id},
                    type:'POST', 
                    success:function(response) {
                        var resp = $.trim(response);
                        $("#vt7").html(resp);
                    }
                });

                $.ajax({
                    url:"gera_timeline_tudao.php",
                    data:{c_id:escola_id},
                    type:'POST', 
                    success:function(response) {
                        var resp = $.trim(response);
                        $("#vt6").html(resp);
                    }
                });
            } 
        }
    </script>

    <body>     
        <?php include("setor_menu.php"); ?>


        <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        Histórico da Escola
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você tem informações gerais das OSs da escola.</li>
                    </ol> 
                                        
                </div>

                <div id="page-inner">               
                    <div class="card-content">
                        
                        <div class="card-action">

                            <!-- <form> 
                                <input class="waves-effect green btn custom-back" type="button" value="Voltar" onClick="history.go(-1)">
                                <input class="waves-effect red btn custom-back" type="button" value="Avançar" onCLick="history.forward()"> 
                                <input class="waves-effect waves-light btn custom-back" type="button" value="Atualizar" onClick="history.go(0)"> 
                            </form> -->

                            <?php 

                                // get active sede user
                                $query_get_sede = "SELECT sede, user_sede FROM sede WHERE user_sede = '$usuario'";
                                $result_id = $link->query($query_get_sede) or die(mysqli_error($link));
                                while($row_id = mysqli_fetch_assoc($result_id)){
                                    $sede = $row_id['sede'];
                                }
                            ?>
                            <input type="hidden" id="id_escola" value="<?php echo $id_escola; ?>">
                            <!-- ############################################### SEARCH SELECT ################################################## -->
                            <select name="escola" class="selectpicker" data-show-subtext="true" data-live-search="true" id="escola"">
                            <?php
                                echo "<option value='0'>Escola...</option>";

                                if ($sede === '0') {
                                    $sql = "SELECT * FROM escola AS E INNER JOIN gre AS G ON E.gre = G.id_gre WHERE E.ativo = 1";
                                }else{

                                    if($sede === '1'){

                                        $sql = "SELECT * FROM escola AS E INNER JOIN gre AS G ON E.gre = G.id_gre WHERE G.id_sede = '$sede' OR E.id_escola = 70 AND E.ativo = 1";

                                    }else{

                                        $sql = "SELECT * FROM escola AS E INNER JOIN gre AS G ON E.gre = G.id_gre WHERE G.id_sede = '$sede' AND E.ativo = 1";
                                    }                                    
                                }
                                                                
                                $dados = mysqli_query($link, $sql);

                                $c = 0;

                                if(mysqli_num_rows($dados) > 0) {

                                    while($row = mysqli_fetch_object($dados)) {
                                        if ($row->id_escola === $id_escola) { 
                                            echo "<option value='".$row->id_escola."' selected='selected'>".$row->nome_escola."</option>";
                                            echo "<script>chama_change();</script>";
                                            $id_escola = '';
                                        }else{
                                            echo "<option value='".$row->id_escola."'>".$row->nome_escola."</option>";
                                        }
                                        
                                    }
                                }
                            ?>         
                            </select>

                        </div>
                        
                    </div>

                    <!-- End Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                            <b><i class="material-icons left">assignment</i>Dados da Escola:</b>
                            <a href="javascript:;" onmousedown="toggleDiv('div_dados_escola');"><i class='material-icons dp48'>aspect_ratio</i></a>
                        </div>

                        <div class="divider"></div>

                        <div class="card-content" id="div_dados_escola">
                            <div class="table-responsive" id="vt7">
                            
                            </div>                                  
                        </div>
                    </div>

                    <div class="card">

                        <div class="card-action">
                            <b><i class="material-icons left">restore</i>Linha do Tempo:</b>
                            <a href="javascript:;" onmousedown="toggleDiv('div_timeline');"><i class='material-icons dp48'>aspect_ratio</i></a>
                        </div> 
                        
                        <div class="divider"></div>

                        <div class="card-content" id="div_timeline">
                            
                            <!-- ################################################################## TIMELINE ################################################################### -->

                                <center><span class="badge">CHAMADOS</span></center>
                                    <div id="vt6">
                                        
                                    </div><!-- End vt6 -->
                                
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

        <!-- Search select -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js" defer></script>
        <script src="dist/js/bootstrap-select.js" defer></script>
     

    </body>

    </html>

<?php 
}else{

    header("location: ./index.php");
    exit();
} 
?>