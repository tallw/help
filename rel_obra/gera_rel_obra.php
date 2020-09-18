<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($_SESSION['user_name']) && !is_numeric($_SESSION['user_name'])){

	$id_os = $_GET['id_os'];

	$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
	$link->set_charset('utf8');

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

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="js/canvas-to-blob.min.js"></script>
    <script src="js/resize.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>

    <script type="text/javascript" src="../jquery.min.js"></script>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

    <script type="text/javascript">

        function limpa_pasta(caminho){

             $.ajax({
                type: 'post',
                url: 'limpa.php',
                data: {
                    caminho: caminho
                },
                success: function (data) {
                    continue;
                }
            });
            return false;
        }

        // Iniciando biblioteca
        var resize = new window.resize();
        resize.init();

        // Declarando variáveis
        var imagens;
        var imagem_atual;

        // Quando carregado a página
        $(function ($) {

            // Quando selecionado as imagens OS
            $('#imagem_os').on('change', function () {

                //limpa_pasta("img/fotos_os/");

                enviar('#imagem_os', "img/fotos_os/", '#progresso_os');
            });

            // Quando selecionado as imagens OS
            $('#imagem_mapa').on('change', function () {

                //limpa_pasta("img/fotos_mapa/");

                enviar('#imagem_mapa', "img/fotos_mapa/", '#progresso_mapa');
            });

            // Quando selecionado as imagens OS
            $('#imagem_cot').on('change', function () {

                //limpa_pasta("img/fotos_cotacao/");

                enviar('#imagem_cot', "img/fotos_cotacao/", '#progresso_cot');
            });

            

        });

        /*
         Envia os arquivos selecionados
         */
        function enviar(input, pasta, barra)
        {
            // Verificando se o navegador tem suporte aos recursos para redimensionamento
            if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                alert('O navegador não suporta os recursos utilizados pelo aplicativo');
                return;
            }

            // Alocando imagens selecionadas
            imagens = $(input)[0].files;

            //alert(imagens.length);

            // Se selecionado pelo menos uma imagem
            if (imagens.length > 0)
            {
                // Definindo progresso de carregamento
                $(barra).attr('aria-valuenow', 0).css('width', '0%');

                // Escondendo campo de imagem
                $(input).hide();

                // Iniciando redimensionamento
                imagem_atual = 0;
                redimensionar(input, pasta, barra);
            }
        }

        /*
         Redimensiona uma imagem e passa para a próxima recursivamente
         */
        function redimensionar(input, pasta, barra)
        {
            // Se redimensionado todas as imagens
            if (imagem_atual > imagens.length)
            {
                // Definindo progresso de finalizado
                $(barra).html('Imagen(s) enviada(s) com sucesso');

                // Limpando imagens
                limpar(input);

                // Exibindo campo de imagem
                $(input).show();

                // Finalizando

                
                return;
            }

            // Se não for um arquivo válido
            if ((typeof imagens[imagem_atual] !== 'object') || (imagens[imagem_atual] == null))
            {
                // Passa para a próxima imagem
                imagem_atual++;
                redimensionar(input, pasta, barra);
                return;
            }

            // Redimensionando
            resize.photo(imagens[imagem_atual], 800, 'dataURL', function (imagem) {

                // Salvando imagem no servidor

                var nome_arquivo = imagens[imagem_atual]['name'].split("/").pop();

                var nome = nome_arquivo.split('.')[0];

                //alert(nome );

                $.post('ajax/salvar.php?nome='+nome+'&pasta='+pasta, {imagem: imagem}, function() {

                    // Definindo porcentagem
                    var porcentagem = (imagem_atual + 1) / imagens.length * 100;

                    // Atualizando barra de progresso
                    $(barra).text(Math.round(porcentagem) + '%').attr('aria-valuenow', porcentagem).css('width', porcentagem + '%');

                    // Aplica delay de 1 segundo
                    // Apenas para evitar sobrecarga de requisições
                    // e ficar visualmente melhor o progresso
                    setTimeout(function () {
                        // Passa para a próxima imagem
                        imagem_atual++;
                        redimensionar(input, pasta, barra);
                    }, 1000);

                });

            });
        }

        /*
         Limpa os arquivos selecionados
         */
        function limpar(input)
        {
            var input = $(input);
            input.replaceWith(input.val('').clone(true));
        }

        function toggleDiv(divid){
            if(document.getElementById(divid).style.display == 'none'){
                document.getElementById(divid).style.display = 'block';
            }else{
                document.getElementById(divid).style.display = 'none';
            }
        }

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

</head>

<body>

<?php include("setor_menu.php"); include("bt_limpa.php"); ?>

        <div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">Relatório de execução para o <small>Protocolo: <?php echo $protocolo; ?></small></h1>
                <ol class="breadcrumb"><li>Aqui você insere as informações do Relatório para execução.</li></ol>                         
            </div>
            <div id="page-inner">               
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-action" style="background: #1ebfae">
                                Dados do Relatório para execução na escola : <?php echo $nome_escola; ?>
                            </div> 
                            <div class="card-content">

							    <form method="POST" action="carrega_rel_obra.php" role='form'>

							        <input type="hidden" id="id_os" name='id_os' value="<?php echo $id_os; ?>">

                                    <input type="hidden" id="capa" name='capa' value="">

                                    <input type="hidden" id="img_aut_seg" name='img_aut_seg' value="">

                                    <div class="row">
                                        <p>TIPO RELATÓRIO: 
                                            <input type="radio" name="tipo" checked="checked" id="obra" value="obra" >
                                            <label for="obra">OBRA</label>
                                            <input type="radio" name="tipo" id="adequacao" value="adequacao" >
                                            <label for="adequacao">ADEQUAÇÃO</label>
                                        </p>
                                    </div>


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

                                    <!-- ############################################### Fotos OS ##################################################### -->

                                    <div class="card">
                                        
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Anexar Fotos OS<i class="fa fa-paperclip material-icons left"></i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_fotos_os');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="card" id="div_fotos_os">
                                            <div class="progress">
									            <div id="progresso_os" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
									                 aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
									        </div>
                                            <div class="card-content">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione a foto</span>
                                                        <input id="imagem_os" type="file" accept="image/*" class="uploadClassificado" multiple/>
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

                                    <script type="text/javascript">
                            
                                        $(document).ready(function(e) { 
                                            $("#todos").click(function(e) { 

                                                var cont_id = document.getElementById('cont_id').value;

                                                if($(this).is(':checked')) { //Retornar true ou false  

                                                    for (var i = 1; i <= cont_id; i++) {
                                                        document.getElementById(i).checked = 'checked';
                                                    }

                                                } else {

                                                    for (var i = 1; i <= cont_id; i++) {
                                                        document.getElementById(i).checked = false;
                                                    }
                                                }
                                            }); 
                                        });

                                    </script>

                                    <div class="card">
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Fotos Rel. Fotográfico<i class="material-icons left">input</i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_fotos');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div id="div_fotos">
                                            <table class="table table-striped table-bordered table-hover">
                                                

                                                <tr>
                                                    <td><a href="img/tudo.png"><img width="100" height="100" src="img/tudo.png"/></a></td>
                                                    <td>SELECIONAR TODAS AS IMAGENS</td>
                                                    <td><p><input type="checkbox" class="filled-in" id="todos" value="todos"/><label for="todos">Todos</label></p></td>
                                                </tr>
  
                                                <?php

                                                $pasta = '../galeria/'.$protocolo.'/';
                                                $arquivos = glob("$pasta{*.jpeg,*.jpg,*.png,*.gif,*.bmp,*.jfif}", GLOB_BRACE);

                                                $cont_id = 1;

                                                foreach($arquivos as $img){

                                                    $descricao = explode('.', explode('/', $img)[3])[0]; ?>
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

                                        <input type="hidden" id="cont_id" name='cont_id' value="<?php echo $cont_id; ?>">
                            
                                    </div>

                                    <div class="divider"></div>

                                    <!-- ############################################### MAPA COTACAO ##################################################### -->

                                    <div class="card">
                                        
                                        <div class="card-action" style="background: #1ebfae">
                                            <b>Anexar Fotos Mapa Cotação<i class="fa fa-paperclip material-icons left"></i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_mapa_cot');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="card" id="div_mapa_cot">
                                            <div class="progress">
									            <div id="progresso_mapa" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
									                 aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
									        </div>
                                            <div class="card-content">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione a foto</span>
                                                        <input id="imagem_mapa" type="file" accept="image/*" class="uploadClassificado" multiple/>
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
                                            <b>Anexar Foto Cotações<i class="fa fa-paperclip material-icons left"></i></b>
                                            <a href="javascript:;" onmousedown="toggleDiv('div_cot');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="card" id="div_cot">
                                            <div class="progress">
									            <div id="progresso_cot" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
									                 aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
									        </div>
                                            <div class="card-content">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione a foto</span>
                                                        <input id="imagem_cot" type="file" accept="image/*" class="uploadClassificado" multiple/>
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Arquivos...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                           
                                    <div class="divider"></div>

                                    <!-- ############################################### AUTORIZAÇÃO ##################################################### -->

                                    <!-- <div class="card">
                                        
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
                           
                                    <div class="divider"></div> -->


                                    <!-- ############################################### Botao Enviar ##################################################### -->

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

		<!-- <script src="../assets/js/jquery-1.10.2.js"></script> -->
        
        
        <script src="../assets/js/bootstrap.min.js"></script>
        
        <script src="../assets/materialize/js/materialize.min.js"></script>
        
        
        <script src="../assets/js/jquery.metisMenu.js"></script>
        
        <script src="../assets/js/morris/raphael-2.1.0.min.js"></script>
        <script src="../assets/js/morris/morris.js"></script>
        
        
        <script src="../assets/js/easypiechart.js"></script>
        <script src="../assets/js/easypiechart-data.js"></script>
        
        <script src="../assets/js/Lightweight-Chart/jquery.chart.js"></script>
        
        
        <script src="../assets/js/custom-scripts.js"></script> 

        
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js" defer></script>
        <script src="../dist/js/bootstrap-select.js" defer></script>

</body>
</html>

<?php
}else{

    header("location: ../index.php");
    exit();

}
?>