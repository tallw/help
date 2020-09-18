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


</head>

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

<body>

<?php include("setor_menu.php");?>

        <div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">Relatório Fotográfico para o <small>Protocolo: <?php echo $protocolo; ?></small></h1>
                <ol class="breadcrumb"><li>Aqui você escolhe as fotos para o relatório..</li></ol>                         
            </div>
            <div id="page-inner">               
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-action" style="background: #1ebfae">
                                OS pertence a escola : <?php echo $nome_escola; ?>
                            </div> 
                            <div class="card-content">

							    <form method="POST" action="carrega_rel_os.php" role='form'>

							        <input type="hidden" id="id_os" name='id_os' value="<?php echo $id_os; ?>">


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