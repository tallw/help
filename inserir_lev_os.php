<?php

//$id_os = $_GET['id_os'];

require_once("config/db.php");

$id_os = $_GET['id_os'];

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$query_OS = "SELECT * FROM ordem_servico WHERE id_os = '$id_os'";
$result_OS = $link->query($query_OS);
$row_OS = mysqli_fetch_object($result_OS);
$protocolo = $row_OS->protocolo;

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];


if(isset($usuario) && isset($id_os) && !is_numeric($usuario)){ 

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


    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>  

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

        var ids = 1;
    
        function duplicarCampos(){

            var div_pai = document.getElementById('pai');

            var div_filho = document.createElement('div');
            div_filho.id = 'filho' + ids;

            div_filho.className = 'input-field col s12';

            var textArea = document.createElement('textarea');
            textArea.name = 'levantamento[]';
            textArea.placeholder = 'Próximo Local:';
            textArea.maxlength = '10000';
        
            textArea.style = 'width: 95%; height: 100px; margin-right: 20px';
            textArea.required = "true";
            
            var img_del = document.createElement('img');
            img_del.src = 'image/del.png';
            img_del.height = '20';
            img_del.width = '20';
            img_del.style = 'cursor: pointer;';
            img_del.addEventListener('click', function() { removerCampos('pai', div_filho.id) });

            div_filho.appendChild(textArea);
            //div_filho.appendChild(img_add);
            div_filho.appendChild(img_del);

            div_pai.appendChild(div_filho);

            ids++;  
    
        }

    </script>


    <script language='JavaScript'>

    function SomenteNumero(e){
        var tecla=(window.event)?event.keyCode:e.which;   
        if((tecla>47 && tecla<58)) return true;
        else{
        if (tecla==8 || tecla==0) return true;
        else  return false;
        }
    }

    function exibe(){
        alert('ok');
    }

    </script>

    <script type="text/javascript">

        var id_item = 1;
        var height_num = 130;

        $(document).ready(function() {

            $("#mais").click(function() {

                var select = document.getElementById('item');
                var input_qtde = document.getElementById('qtde');
                var input_ref = document.getElementById('ref');
                var select_s = document.getElementById('setor');

                var div_pai = document.getElementById('item_pai');

                var div_filho = document.createElement('div');
                div_filho.id = 'item_filho' + id_item;
                div_filho.className = 'input-field col s12';
                div_filho.style = 'display: flex;';

                var select_2 = document.createElement('select'); // ############################################################### SELECT
                select_2.innerHTML = select.innerHTML;
                select_2.style = 'width: 40%; margin-left: 20px; margin-bottom: 15px;';
                select_2.className = 'form-control';
                select_2.id = 'item' + id_item;
                select_2.name = 'item[]';

                var input_qtde_2 = document.createElement('input'); // ############################################################### INPUT QTDE
                input_qtde_2.innerHTML = input_qtde.innerHTML;
                input_qtde_2.style = 'width: 10%; margin-left: 20px; margin-bottom: 15px;';
                input_qtde_2.className = 'validate';
                input_qtde_2.id = 'qtde' + id_item;
                input_qtde_2.placeholder = 'Qtde:';
                input_qtde_2.type = "number";
                input_qtde_2.name = 'qtde[]';
                input_qtde_2.required = 'true';

                var input_ref_2 = document.createElement('input'); // ############################################################### INPUT REF
                input_ref_2.innerHTML = input_ref.innerHTML;
                input_ref_2.style = 'width: 20%; margin-left: 20px; margin-bottom: 15px;';
                input_ref_2.className = 'validate';
                input_ref_2.id = 'ref' + id_item;
                input_ref_2.placeholder = 'Referência:'; // class="form-control" id="setor" style="width: 20%; margin-left: 20px; margin-bottom: 15px;"
                input_ref_2.name = 'ref[]';
                input_ref_2.required = 'true';

                var select_2_s = document.createElement('select'); // ############################################################### SELECT SETOR
                select_2_s.innerHTML = select_s.innerHTML;
                select_2_s.style = 'width: 20%; margin-left: 20px; margin-bottom: 15px;';
                select_2_s.className = 'form-control';
                select_2_s.id = 'setor' + id_item;
                select_2_s.name = 'setor[]';

 
                var img_del = document.createElement('img');
                img_del.src = 'image/del.png';
                img_del.height = '20';
                img_del.width = '20';
                img_del.style = 'cursor: pointer; margin-left: 20px;';
                img_del.addEventListener('click', function() { removerCampos('item_pai', div_filho.id) });

                div_filho.appendChild(select_2);
                div_filho.appendChild(input_qtde_2);
                div_filho.appendChild(input_ref_2);
                div_filho.appendChild(select_2_s);
                div_filho.appendChild(img_del);

                div_pai.appendChild(div_filho);

                div_pai.style = 'height: ' + (height_num) + 'px;';

                id_item++;
                height_num+=65;

            });

    
        });

        function removerCampos(pai,id){

            var node1 = document.getElementById(pai);
            node1.removeChild(document.getElementById(id));

            if (pai.localeCompare('item_pai') == 0) {

                height_num -= 130;

                node1.style = 'height: ' + (height_num) + 'px;';

                height_num+=65;
            }
             
        }

        
    </script>

    <body>
        <?php include("setor_menu.php"); ?>
       
            <!-- /. NAV SIDE  -->

            <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        Inserir Levantamento de materiais do <small>Protocolo: <?php echo $protocolo; ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você insere as informações do levantamento.</li>
                    </ol>                         
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-action" style="background: #1ebfae">
                             Dados do Levantamento:
                        </div> 
                        <div class="card-content">
                            
                            <form method="post" action="executions/grava_levantamento.php" name="lev_os">

                                <div class="row">
                                    <div class="input-field col s3">
                                        <input type="date" name="data" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <input type="hidden" name="id_os" id="id_os" value="<?php echo $id_os; ?>">
                                    <input type="radio" name="myRadios" value="1" />
                                </div>

                                <div class="row" id="pai">
                                    <div class="input-field col s12" id="filho">
                                        <textarea style="width: 95%; height: 100px; margin-right: 20px" name="levantamento[]" placeholder="Local 1:" maxlength="10000" required=""></textarea>
                                        <label style="color: red">*</label>

                                        <img  src="image/add.png" height="20px" width="20px" style="cursor: pointer;" onclick="duplicarCampos();">
                                    </div>
                                </div>


                                               
                                <div class="card">

                                    <div class="card-action" style="background: #1ebfae">
                                        <b>Itens de Cotação<i class="material-icons left">input</i></b>
                                        <a href="javascript:;" onmousedown="toggleDiv('item_pai');"><i class='material-icons dp48'>aspect_ratio</i></a>

                                        <input type="button" name="" value="Nova linha" id="mais" class="waves-effect waves-light btn custom-back">
                                    </div>

                                    <div class="divider"></div><br>

                                    <div  id="item_pai" style="height: 65px;">

                                        <div class="item_filho" id="item_filho"  style="display: flex;">

                                            <select name="item[]" class="form-control" id="item" style="width: 40%; margin-left: 20px; margin-bottom: 15px;">
                                                            
                                                <?php //style="height: 2900px;"

                                                    $query_itens = "SELECT * FROM itens_de_cotacao order by nome_item";
                                                    $result_itens = $link->query($query_itens);

                                                    while ($row_itens = mysqli_fetch_object($result_itens)) { ?>

                                                        <option value="<?php echo $row_itens->id_item; ?>"><?php echo $row_itens->nome_item; ?></option>

                                                <?php
                                                    }
                                                ?>
                                                    
                                            </select>

                                            <input id="qtde" name='qtde[]' placeholder="Qtde:" type="text" class="validate" onkeypress='return SomenteNumero(event)' style="width: 10%; margin-left: 20px; margin-bottom: 15px;" required="">

                                            <input id="ref" name='ref[]' placeholder="Referência:" type="text" class="validate" style="width: 20%; margin-left: 20px; margin-bottom: 15px;" required="">

                                            <select name="setor[]" class="form-control" id="setor" style="width: 20%; margin-left: 20px; margin-bottom: 15px;">
                                                <option value="1">Laboratório de Informática</option>
                                                <option value="2">Áreas Administrativas</option>
                                                <option value="3">Toda escola</option>
                                            </select>

                                        </div>
                            
                                    </div>
                                </div>
                                           

                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s3">
                                            <button type="submit" name="salva_edit" class="waves-effect waves-light btn" onClick="return validarcampos()">Salvar</button>
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