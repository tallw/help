<?php

$filtro = $_GET['filtro'];

$titulo = '';

if ($filtro==='0') {
    $titulo = 'Todas Biometrias';
}else if ($filtro==='1') {
    $titulo = 'Em Estoque';
}else if ($filtro==='2') {
    $titulo = 'Em Escola';
}else if ($filtro==='3') {
    $titulo = 'Em Sede JP (Defeito)';
}else if ($filtro==='4') {
    $titulo = 'Em Garantia (Defeito)';
}else if ($filtro==='5') {
    $titulo = 'Todas Em Escola';
}else if ($filtro==='6') {
    $titulo = 'Em Escola (Sede CG)';
}else if ($filtro==='7') {
    $titulo = 'Em Escola (Sede JP)';
}else if ($filtro==='8') {
    $titulo = 'Em Escola (Sede SS)';
}

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($usuario) && !is_numeric($usuario)){

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
    </head>
    <body>
        <?php include("setor_menu.php"); ?>

            <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        <?php echo $titulo; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Lista de Biometrias.</li>
                    </ol>                         
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                             Tabela de Biometrias
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                
                                <table class="table table-striped table-bordered table-hover meus-chamados" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th class="center">ID</th>
                                            <th class="center">Sede</th>
                                            <th>Serial</th>
                                            <th>Escola</th>
                                            <th class="center">Status</th>
                                            <th class="center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
                    						$link->set_charset('utf8');

                    						if (!$link){

                        						die('Connect Error (' . mysqli_connecterrno() . ')' .
                        						mysqli_connect_error());

                    						}else{

                                                $query_todos = "SELECT * FROM `biometrias`";
                                                $query_estoque = "SELECT * FROM `biometrias` WHERE status_bio = 1";
                                                $query_escola = "SELECT * FROM `biometrias` WHERE status_bio = 2";
                                                $query_sedejp = "SELECT * FROM `biometrias` WHERE status_bio = 3";
                                                $query_garantia = "SELECT * FROM `biometrias` WHERE status_bio = 4";

                                                if($sede !== '0') {

                                                    $query_todos .= " WHERE sede_bio = '$sede'";
                                                    $query_estoque .= " AND sede_bio = '$sede'";
                                                    $query_escola .= " AND sede_bio = '$sede'";
                                                    $query_sedejp .= " AND sede_bio = '$sede'";
                                                    $query_garantia .= " AND sede_bio = '$sede'";
                                                }

                                                $conjunto ="";

                                                if($filtro === '0'){
                                                    $conjunto = $link->query($query_todos);
                                                }else if ($filtro === '1') {
                                                    $conjunto = $link->query($query_estoque);
                                                }else if ($filtro === '2') {
                                                    $conjunto = $link->query($query_escola);
                                                }else if ($filtro === '3'){
                                                    $conjunto = $link->query($query_sedejp);
                                                }else if ($filtro === '4'){
                                                    $conjunto = $link->query($query_garantia);
                                                }else if ($filtro === '5'){
                                                    $conjunto = $link->query($query_escola);
                                                }else if ($filtro === '6'){
                                                    $conjunto = $link->query($query_escola." AND sede_bio = 1");
                                                }else if ($filtro === '7'){
                                                    $conjunto = $link->query($query_escola." AND sede_bio = 2");
                                                }else if ($filtro === '8'){
                                                    $conjunto = $link->query($query_escola." AND sede_bio = 3");
                                                }

                                                if(mysqli_num_rows($conjunto) > 0){
                                                    
                            						while($row_bio = mysqli_fetch_object($conjunto)){

                                                        $id_bio = $row_bio->id_biometria;
                                                        $id_sede_bio = $row_bio->sede_bio;
                                                        if ($id_sede_bio === '1') {
                                                            $sede_bio = 'CG';
                                                        }else if ($id_sede_bio === '2') {
                                                            $sede_bio = 'JP';
                                                        }else if ($id_sede_bio === '3') {
                                                            $sede_bio = 'SS';
                                                        }

                                                        $serial_bio = $row_bio->serial_bio;

                                                        $result_escola_bio = $link->query("SELECT * FROM escola WHERE fk_id_biometria = '$id_bio'");
                                                        if (mysqli_num_rows($result_escola_bio) > 0) {
                                                            $escola_bio = mysqli_fetch_object($result_escola_bio)->nome_escola;
                                                            //echo nl2br($escola_bio."-".$id_bio."\n");

                                                        }else{
                                                            $escola_bio = "Não possui...";
                                                        }

                                                        $status_bio = $row_bio->status_bio;
                                                        $status_txt = '';

                                                        if ($status_bio === '1') {
                                                            $status_txt = 'Estoque';
                                                        }else if ($status_bio === '2') {
                                                            $status_txt = 'Escola';
                                                        }else if ($status_bio === '3') {
                                                            $status_txt = 'Sede JP';
                                                        }else if ($status_bio === '4') {
                                                            $status_txt = 'Garantia';
                                                        }

                                                        echo "<tr class='odd gradeX'>
                                                                <td>$id_bio</td>
                                                                <td>$sede_bio</td>
                                                                <td>$serial_bio</td>
                                                                <td>$escola_bio</td>";
                                                                
                                                                    
                                                                if ($status_bio === '1'){
                                                                    echo "<td class='center' style='color: orange'><b>$status_txt</b></td>";
                                                                }else if ($status_bio === '2'){
                                                                    echo "<td class='center' style='color: green'><b>$status_txt</b></td>";
                                                                }else if ($status_bio === '3'){
                                                                    echo "<td class='center' style='color: red'><b>$status_txt</b></td>";
                                                                }else if ($status_bio === '4'){
                                                                    echo "<td class='center' style='color: black'><b>$status_txt</b></td>";
                                                                }
                                                        
                                                                echo "<td class='center'>
                                                                        <a href='ver_bio.php?id_bio=$id_bio' target='_blank'><i class='material-icons dp48'>visibility</i></a>
                                                                    </td>
                                                            </tr>";   
                                                        

                            						}
                            					}
                    						}

                                        ?>      
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
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

         <!-- DATA TABLE SCRIPTS -->
        <script src="../assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="../assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
        </script>
        
        <!-- Custom Js -->
        <script src="../assets/js/custom-scripts.js"></script> 

        <!-- PORTUGUESE DATA ORDER -->
        <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
     

    </body>

    </html>

<?php
}else{

    header("location: ./index.php");
    exit();

}


?>