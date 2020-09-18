<?php

if(!isset($_SESSION)){ 

    session_start(); 
}

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');

$usuario = $_SESSION['user_name'];
$id_os = $_GET['id_os'];



// ############################################## Dados OS

$query_OS = "SELECT * FROM ordem_servico WHERE id_os = '$id_os'";
$result_OS = $link->query($query_OS);
$row_OS = mysqli_fetch_object($result_OS);
$id_escola = $row_OS->fk_id_nome_escola;
$protocolo = $row_OS->protocolo;

// ############################################## Dados Escola

$query_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'"; 
$result_escola = $link->query($query_escola);
$row_escola = mysqli_fetch_object($result_escola);
$nome_escola = $row_escola->nome_escola;


                      
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
            
            var img_del = document.createElement('img');
            img_del.src = 'image/del.png';
            img_del.height = '20';
            img_del.width = '20';
            img_del.style = 'cursor: pointer;';
            img_del.addEventListener('click', function() { removerCampos(div_filho.id) });

            div_filho.appendChild(textArea);
            //div_filho.appendChild(img_add);
            div_filho.appendChild(img_del);

            div_pai.appendChild(div_filho);

            ids++;  
    
        }

        function removerCampos(id){
            var node1 = document.getElementById('pai');
            node1.removeChild(document.getElementById(id));
             
        }

    </script>

    <script language="javascript">
        function toggleDiv(divid){
            if(document.getElementById(divid).style.display == 'none'){
                document.getElementById(divid).style.display = 'block';
            }else{
                document.getElementById(divid).style.display = 'none';
            }
        }
    </script>

    <script language="javascript" type="text/javascript">

        function readURL(input, id, seg_img) {

           if (input.files && input.files[0]) {
               var reader = new FileReader();

               reader.onload = function (e) {
                
                   $('#'+id).attr('src', e.target.result);
                   document.getElementById(id).style.display='block';
                   document.getElementById(seg_img).value = e.target.result;
               }

               reader.readAsDataURL(input.files[0]);
           }
       }

    </script>

    <body>     
        <?php include("setor_menu.php"); ?>

        <div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">Relatório de Obra do <small>Protocolo: <?php echo $protocolo; ?></small></h1>
                <ol class="breadcrumb"><li>Aqui você insere as informações do Relatório de Obra.</li></ol>                         
            </div>
            <div id="page-inner">               
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-action" style="background: #1ebfae">
                                Dados do Relatório de Obra na escola : <?php echo $nome_escola; ?>
                            </div> 
                            <div class="card-content">

                                <!-- ############################################### INICIO FORM ##################################################### enctype="multipart/form-data" -->

                                <form method="POST" action="docs/carrega_rel_obra.php" enctype="multipart/form-data">

                                    <input type="hidden" id="id_os" name='id_os' value="<?php echo $id_os; ?>">

                                    <input type="hidden" id="capa" name='capa' value="">

                                    <input type="hidden" id="img_aut_seg" name='img_aut_seg' value="">


                                    <!-- ############################################### FOTO CAPA ##################################################### -->

                                    <div class="card">
                                        
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Anexar Foto Capa<i class="fa fa-paperclip material-icons left"></i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_foto_capa');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="card" id="div_foto_capa">
                                            <div class="card-action">
                                                <center><img width="400" height="400" src="" id="mini_foto_new" name="mini_foto_new" style="display: none" /></center>
                                            </div>
                                            <div class="card-content">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione a foto da Capa</span>
                                                        <input type="file" id="img_capa" name="img_capa" class="uploadClassificado" onchange="javascript:readURL(this,'mini_foto_new','capa');">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Arquivos...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                           
                                    <div class="divider"></div>

                                     <!-- ############################################### FOTOS OS ##################################################### -->


                                    <div class="card">
                                        
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Anexar Foto DA OS<i class="fa fa-paperclip material-icons left"></i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_img_os');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="card" id="div_img_os">
                                            <div class="card-content">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione a foto</span>
                                                        <input type="file" id="img_os" name="img_os[]" class="uploadClassificado" multiple>
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Arquivos...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                           
                                    <div class="divider"></div>

                                    <!-- ############################################### FOTOS REL FOTOGRAFICO ##################################################### -->

                                    <div class="card">
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Fotos Rel. Fotográfico<i class="material-icons left">input</i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_fotos');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div id="div_fotos">
                                            <table class="table table-striped table-bordered table-hover">
  
                                                <?php

                                                $pasta = 'galeria/'.$protocolo.'/';
                                                $arquivos = glob("$pasta{*.jpeg,*.jpg,*.png,*.gif,*.bmp,*.jfif}", GLOB_BRACE);

                                                $cont_id = 1;

                                                foreach($arquivos as $img){

                                                    $descricao = explode('.', explode('/', $img)[2])[0]; ?>
                                                    <tr>
                                                        <td><a href="<?php echo $img; ?>"><img width="100" height="100" src="<?php echo $img; ?>"/></a></td>
                                                        <td><?php echo $descricao; ?></td>
                                                        <td><p><input type="checkbox" id="<?php echo $cont_id; ?>" name="fotos[]" value="<?php echo $img; ?>"/><label for="<?php echo $cont_id; ?>"></label></p></td>
                                                    </tr>

                                                    <?php $cont_id++;
                                                }

                                                ?>
                                                    
                                            </table>
                                        </div>
                            
                                    </div>

                                    <!-- ############################################### MAPA COTACAO ##################################################### -->


                                    <div class="card">
                                        
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Anexar Fotos Mapa Cotação<i class="fa fa-paperclip material-icons left"></i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_mapa_cot');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="card" id="div_mapa_cot">
                                            <div class="card-content">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione a foto</span>
                                                        <input type="file" id="img_map_cot" name="img_map_cot[]" class="uploadClassificado" multiple>
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Arquivos...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                           
                                    <div class="divider"></div>

                                    <!-- ############################################### COTAÇÕES ##################################################### -->

                                    <div class="card">
                                        
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Anexar Fotos Cotações<i class="fa fa-paperclip material-icons left"></i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_cot');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="card" id="div_cot">
                                            <div class="card-content">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione a foto</span>
                                                        <input type="file" id="img_cot" name="img_cot[]" class="uploadClassificado" multiple>
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Arquivos...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                           
                                    <div class="divider"></div>

                                    <!-- ############################################### ESPECIFICAÇÃO TÉCNICA ##################################################### 

                                    <div class="card">
                                        
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Anexar Foto Especificação Técnica<i class="fa fa-paperclip material-icons left"></i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_esp_tec');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="card" id="div_esp_tec">
                                            <div class="card-action">
                                                <center><img width="400" height="400" src="" id="mini_foto_new_esp" name="mini_foto_new_esp" style="display: none" /></center>
                                            </div>
                                            <div class="card-content">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione a foto</span>
                                                        <input type="file" id="img_esp_tec" name="img_esp_tec" class="uploadClassificado" onchange="javascript:readURL(this,'mini_foto_new_esp','img_esp_seg');">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Arquivos...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                           
                                    <div class="divider"></div> -->

                                    <!-- ############################################### AUTORIZAÇÃO ##################################################### -->

                                    <div class="card">
                                        
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Anexar Foto Autorização<i class="fa fa-paperclip material-icons left"></i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_aut');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="card" id="div_aut">
                                            <div class="card-action">
                                                <center><img width="400" height="400" src="" id="mini_foto_new_aut" name="mini_foto_new_aut" style="display: none" /></center>
                                            </div>
                                            <div class="card-content">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione a foto</span>
                                                        <input type="file" id="img_aut" name="img_aut" class="uploadClassificado" onchange="javascript:readURL(this,'mini_foto_new_aut','img_aut_seg');">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Arquivos...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                           
                                    <div class="divider"></div>
                                            
                                    <!-- ############################################### CAMPO TEXTO #####################################################

                                    <div class="row" id="pai">
                                        <div class="input-field col s12" id="filho">
                                            <textarea style="width: 95%; height: 100px; margin-right: 20px" name="levantamento[]" placeholder="Local 1:" maxlength="10000"></textarea>
                                            <label style="color: red">*</label>

                                            <img  src="image/add.png" height="20px" width="20px" style="cursor: pointer;" onclick="duplicarCampos();">
                                        </div>
                                    </div>  -->

                                    <div class="take-down">   
                                        <input type="submit" name="enviar" value="GERAR" id="click" class="waves-effect waves-ligh btn" />
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