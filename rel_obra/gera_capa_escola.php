<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($_SESSION['user_name']) && !is_numeric($_SESSION['user_name'])){

	$id_escola = $_GET['id_escola'];

	$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
	$link->set_charset('utf8');

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

    
    <script type="text/javascript">


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

<?php include("setor_menu.php"); ?>

        <div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">Capa para o relatório Geral <small>Escola: <?php echo $nome_escola; ?></small></h1>
                <ol class="breadcrumb"><li>Aqui você escolhe a imagem para a capa do relatório geral da escola.</li></ol>                         
            </div>
            <div id="page-inner">               
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-action" style="background: #1ebfae">
                                Foto para a capa do relatório geral da escola: <?php echo $nome_escola; ?>
                            </div> 
                            <div class="card-content">

							    <form method="POST" action="carrega_capa_escola.php" role='form'>

							        <input type="hidden" id="id_escola" name='id_escola' value="<?php echo $id_escola; ?>">

                                    <input type="hidden" id="capa" name='capa' value="">

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