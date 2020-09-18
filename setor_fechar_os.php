<?php

$id_os = $_GET['id_os'];

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];


if(isset($usuario)  && !is_numeric($usuario)){ // tratar para que não possa ser visto OSs de outro setores passando id na url (verificar se escola pertence ao user)

    $link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');

    if (!$link){

        die('Connect Error (' . mysqli_connecterrno() . ')' .
        mysqli_connect_error());

    }else{

        $query_protocolo = mysqli_query($link, "SELECT * from ordem_servico where id_os = '$id_os'");
        $protocolo = mysqli_fetch_object($query_protocolo)->protocolo;

    }

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

        <!-- ################################ DA TIMELINE ################################# -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css" />
        <link rel="stylesheet" href="dist/vertical-timeline.css">
        <!-- ################################################################################ -->
    </head>

    <body onload="botoes()">
        <?php include("setor_menu.php"); ?>

       
            <!-- /. NAV SIDE  -->

            <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        Confirmação do fechamento da OS: <strong><?php echo $protocolo; ?></strong>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você deve confirmar o fechamento da OS ou não.</li>
                    </ol> 
                                        
                </div>
                <div id="page-inner">               
                    <div class="row">
                        
                        <div class="card-content">
                        <div class="card">
                            <div class="card-action">
                                <b>ESCOLHA A DATA E CONFIRME O FECHAMENTO DA OS: </b>
                            </div>
                            <div class="card-content">
                                <form method="post" action="executions/fechar_os.php" name="fechar_os" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="input-field col s3">
                                            <input type="date" name="data" required>
                                        </div>
                                    </div>
                                
                                    <div class="row">
                                        <input type="hidden" name="id_os" id="id_os" value="<?php echo $id_os; ?>">
                                    </div>

                                    <div class="card-action">
                                        <b>Informe a nota de avaliação da OS:</b>
                                    </div>

                                    <div class="row">
                                        <p>
                                            <input type="radio" name="nota" id="dois" value="dois" >
                                            <label for="dois"><--2    |</label>
                                            <input type="radio" name="nota" id="quatro" value="quatro" >
                                            <label for="quatro"><--4    |</label>
                                            <input type="radio" name="nota" id="seis" value="seis" >
                                            <label for="seis"><--6    |</label>
                                            <input type="radio" name="nota" id="oito" value="oito" >
                                            <label for="oito"><--8    |</label>
                                            <input type="radio" name="nota" id="dez" value="dez" >
                                            <label for="dez"><--10    |</label>
                                        </p>
                                    </div>

                                       <div class="card-action">
                                        <b>Documento da OS:</b>
                                    </div>

                                    <div class="file-field input-field">
                                        <div class="btn">
                                            <span>Doc OS</span>
                                            <input type="file" id="arquivo" name="arquivo"> 
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text" placeholder="Insira o arquivo pdf contendo a capa e execuções da OS">
                                        </div>
                                    </div>
 

                                    <div class="card-action">
                                        <b>Confirme o Fechamento:</b> 
                                    </div>

                                    <div class="row">
                                        <div class="col s6">
                                            <div class="input-field col s3">
                                                <button type="submit" name="fechar" class="waves-effect waves-light btn" onClick="return validarcampos()">FECHAR OS</button>
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

                    <!-- End Advanced Tables -->

                    
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