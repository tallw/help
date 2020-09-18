<?php

$titulo = 'Cotações de Insumos Ecos-PB'; 

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
 
       
            <!-- /. NAV SIDE  -->

            <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        <?php echo $titulo; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Lista de Cotações de Insumos.</li>
                    </ol>                         
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                             Tabela de Cotações de Insumos
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                
                                <table class="table table-striped table-bordered table-hover meus-chamados" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuário Resp.</th>
                                            <th>Data Cotação</th>
                                            <th>Observação</th>
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

                                                $query_cots_insumos = "SELECT * FROM `cotacoes_insumos` order by dt_cot_insumos";
                                                $conjunto = $link->query($query_cots_insumos);

                                                if(mysqli_num_rows($conjunto) > 0){
                                                    
                            						while($row = mysqli_fetch_object($conjunto)){

                                                        $status = $row->status_cot_insumos;
                                                        $status_txt = '';

                                                        if ($status === '0') {
                                                            $status_txt = 'Aguardando';
                                                        }else if ($status === '1') {
                                                            $status_txt = 'Aprovada';
                                                        }

                                                        $dt_abertura = $row->dt_cot_insumos;

                                                        $data = explode(' ', $dt_abertura)[0];

                                                        $ano = explode('-', $data)[0]; 
                                                        $mes = explode('-', $data)[1];
                                                        $dia = explode('-', $data)[2];
 
                                                        $temp = $dia.'/'.$mes.'/'.$ano;

                                                        $id_cot_insumos = $row->id_cot_insumos;

                                                        $id_user =  $row->fk_id_user_insu;

                                                        $query_sede = "SELECT * FROM sede WHERE sede_id = '$id_user'";
                                                        $result_sede = $link->query($query_sede);
                                                        $row_sede = mysqli_fetch_object($result_sede);
                                                        $nome_sede = $row_sede->user_sede;

                                                        $observacao = $row->obs_cotacao;

                                                        
                                                        echo "<tr class='odd gradeX'>
                                                                <td>$id_cot_insumos</td>
                                                                <td>$nome_sede</td>
                                                                <td>$temp</td>
                                                                <td>$observacao</td>";
                                                            
                                                        if ($status === '0'){
                                                            echo "<td class='center' style='color: #d9534f'><b>$status_txt</b></td>";
                                                        }else if ($status === '1'){
                                                            echo "<td class='center' style='color: #8C1717'><b>$status_txt</b></td>";
                                                        }

                                                        echo "<td class='center'>
                                                                    <a href='ver_insumos.php?id_cot=".$id_cot_insumos."' target='_blank'><i class='material-icons dp48'>visibility</i></a>
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