<?php

//$id_os = $_GET['id_os'];

require_once("config/db.php");

$id_os = $_POST['id_os']; // tratar depois (não pode ver os dos outros)

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
    <body>
        <?php include("setor_menu.php"); ?>
       
            <!-- /. NAV SIDE  -->

            <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        Escolha de Técnicos para OS: <?php echo $protocolo; ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você escolhe os técnicos para ficarem responsáveis pela data atual.</li>
                    </ol>                         
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-action"> 
                             Técnicos:
                        </div> 
                        <div class="card-content">
                            
                            <form method="post" action="executions/grava_tecnicos.php" name="abrir_chamado">
                                
                                <div class="row">
                                    <input type="hidden" name="id_os" id="id_os" value="<?php echo $id_os; ?>">
                                </div>

                                <div class="row">
                                    <div class="input-field">
                                        <select multiple class="form-control" name="tecnicos[]" required="">
                                            <?php

                                            $query_get_sede = "SELECT sede, user_sede FROM sede WHERE user_sede = '$usuario'";
                                            $result_id = $link->query($query_get_sede) or die(mysqli_error($link));
                                            while($row_id = mysqli_fetch_assoc($result_id)){
                                                $sede = $row_id['sede'];
                                            }

                                            if ($sede === '0') {
                                                $sql = "SELECT * FROM tecnico ORDER BY nome_tecnico";
                                            }else{
                                                $sql = "SELECT * FROM tecnico where fk_sede = '$sede' ORDER BY nome_tecnico";
                                            }
                                            
                                            
                                            $dados = mysqli_query($link, $sql);

                                            if(mysqli_num_rows($dados) > 0) {
                                                while($row = mysqli_fetch_object($dados)) {
                                                    echo "<option value='".$row->id_tecnico."'>".$row->nome_tecnico."</option>";
                                                }
                                            }
                                            ?> 
                                        </select>
                                    </div>       
                                </div>

                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s3">
                                            <button type="submit" name="salva_edit" class="waves-effect waves-light btn" onClick="return validarcampos()">Gravar Escolha</button>
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