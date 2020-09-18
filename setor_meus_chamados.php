<?php

$filtro = $_GET['filtro'];

$titulo = '';

if ($filtro==='0') {
    $titulo = 'Meus Chamados';
}else if ($filtro==='1') {
    $titulo = 'Chamados Abertos';
}else if ($filtro==='2') {
    $titulo = 'Chamados Em Levantamento';
}else if ($filtro==='3') {
    $titulo = 'Chamados Finalizados';
}else if ($filtro==='4') {
    $titulo = 'Chamados SLA Atendimento';
}else if ($filtro==='5') {
    $titulo = 'Chamados SLA Conclusão (Emergencial)';
}else if ($filtro==='6') {
    $titulo = 'Chamados com Pós-Venda';
}else if ($filtro==='7') {
    $titulo = 'Chamados com Pendências';
}else if ($filtro==='8') {
    $titulo = 'Chamados Em Cotação';
}else if ($filtro==='9') {
    $titulo = 'Chamados Em execução';
}else if ($filtro==='10') {
    $titulo = 'Chamados não Finalizados (Sedes)';
}else if ($filtro==='11') {
    $titulo = 'Chamados não Finalizados (CG)';
}else if ($filtro==='12') {
    $titulo = 'Chamados não Finalizados (JP)';
}else if ($filtro==='13') {
    $titulo = 'Chamados não Finalizados (SS)';
}else if ($filtro==='14') {
    $titulo = 'Chamados com Pendência (CG)';
}else if ($filtro==='15') {
    $titulo = 'Chamados com Pendência (JP)';
}else if ($filtro==='16') {
    $titulo = 'Chamados com Pendência (SS)';
}else if ($filtro==='17') {
    $titulo = 'Chamados sem Pós-Venda';
}else if ($filtro==='18') {
    $titulo = 'Chamados SLA Conclusão (Programado)';
}



include("libraries/functions.php");
include("libraries/feriado.php");
include("libraries/functions_date.php");

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
                        <?php echo $titulo; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Lista de chamados.</li>
                    </ol>                         
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                             Tabela de Chamados
                        </div>
                        <div class="card-content">
                            <div class="table-responsive">
                                
                                <table class="table table-striped table-bordered table-hover meus-chamados" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Protocolo OS</th>
                                            <th>Data abertura</th>
                                            <th>Escola</th>
                                            <th>Motivo</th>
                                            <th>Data Conclusão</th>
                                            <th class="center">Status</th>
                                            <th class="center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
                                            $link->set_charset('utf8');

                                            if (!$link){

                                                die('Connect Error (' . mysqli_connecterrno() . ')' .
                                                mysqli_connect_error());

                                            }else{

                                                $query_get_sede = "SELECT sede, user_sede FROM sede WHERE user_sede = '$usuario'";
                                                $result_id = $link->query($query_get_sede) or die(mysqli_error($link));
                                                while($row_id = mysqli_fetch_assoc($result_id)){
                                                    $sede = $row_id['sede'];
                                                }

                                
                                                $query_abertos = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 1";
                                                $query_em_execucao = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 2";
                                                $query_em_atendimento = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 4";
                                                $query_em_cotacao = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 5";
                                                $query_finalizados = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 3";
                                                $query_OSs = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo";
                                                $query_sem_pos_venda = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 3 and Os.pos_venda is null";
                                                $query_com_pos_venda = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 3 and Os.pos_venda is not null";
                                                $query_pendencias = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo Where 1 IN (SELECT status_pendencia FROM execucao_diaria Where fk_id_ordem_servico = Os.id_os)  and Os.status = 3";
                                                $query_nfinalizados_sedes = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status != 3";
                                                $query_nfinalizados_cg = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.fk_id_sede = '1' AND Os.status != 3";
                                                $query_nfinalizados_jp = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.fk_id_sede = '2' AND Os.status != 3";
                                                $query_nfinalizados_ss = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.fk_id_sede = '3' AND Os.status != 3";

                                                $query_pendencias_cg = $query_pendencias." and fk_id_sede = '1'";
                                                $query_pendencias_jp = $query_pendencias." and fk_id_sede = '2'";
                                                $query_pendencias_ss = $query_pendencias." and fk_id_sede = '3'";



                                                
                                                if($sede != '0'){ // WHERE Os.tipo_chamado = 0 and Os.status < 3

                                                    $query_OSs.= " WHERE fk_id_sede = '$sede'";
                                                    $query_abertos.=" and fk_id_sede = '$sede'";
                                                    $query_em_atendimento.=" and fk_id_sede = '$sede'";
                                                    $query_em_cotacao.=" and fk_id_sede = '$sede'";
                                                    $query_em_execucao.=" and fk_id_sede = '$sede'";
                                                    $query_finalizados.=" and fk_id_sede = '$sede'";
                                                    $query_sem_pos_venda.=" and fk_id_sede = '$sede'";
                                                    $query_com_pos_venda.=" and fk_id_sede = '$sede'";
                                                    $query_pendencias.=" and fk_id_sede = '$sede'";
                                                }


                                                $conjunto ="";

                                                if($filtro === '0'){
                                                    $conjunto = $link->query($query_OSs);
                                                }else if ($filtro === '1') {
                                                    $conjunto = $link->query($query_abertos);
                                                }else if ($filtro === '2') {
                                                    $conjunto = $link->query($query_em_atendimento);
                                                }else if ($filtro === '3'){
                                                    $conjunto = $link->query($query_finalizados);
                                                }else if ($filtro === '6'){
                                                    $conjunto = $link->query($query_com_pos_venda);
                                                }else if ($filtro === '7'){
                                                    $conjunto = $link->query($query_pendencias);
                                                }else if ($filtro === '8'){
                                                    $conjunto = $link->query($query_em_cotacao);
                                                }else if ($filtro === '9'){
                                                    $conjunto = $link->query($query_em_execucao);
                                                }else if ($filtro === '10'){
                                                    $conjunto = $link->query($query_nfinalizados_sedes);
                                                }else if ($filtro === '11'){
                                                    $conjunto = $link->query($query_nfinalizados_cg);
                                                }else if ($filtro === '12'){
                                                    $conjunto = $link->query($query_nfinalizados_jp);
                                                }else if ($filtro === '13'){
                                                    $conjunto = $link->query($query_nfinalizados_ss);
                                                }else if ($filtro === '14'){
                                                    $conjunto = $link->query($query_pendencias_cg);
                                                }else if ($filtro === '15'){
                                                    $conjunto = $link->query($query_pendencias_jp);
                                                }else if ($filtro === '16'){
                                                    $conjunto = $link->query($query_pendencias_ss);
                                                }else if ($filtro === '17'){
                                                    $conjunto = $link->query($query_sem_pos_venda);
                                                }else{

                                                    if ($filtro === '4') {

                                                        if ($sede != '0') {
                                                            $query_OSs.= " and Os.status = 1 and Os.tipo_chamado = 0";
                                                        }else{
                                                            $query_OSs.= " WHERE Os.status = 1 and Os.tipo_chamado = 0";
                                                        }
                                                        
                                                    }else if ($filtro === '5' || $filtro === '18'){
                                                        if ($sede != '0') {
                                                            $query_OSs.= " and Os.status != 3";
                                                        }else{
                                                            $query_OSs.= " WHERE Os.status != 3";
                                                        } 
                                                    }

                                                    $conjunto = $link->query($query_OSs);
                                                }

                                                if(mysqli_num_rows($conjunto) > 0){
                                                    
                                                    while($row_os = mysqli_fetch_object($conjunto)){

                                                        $inserir = false;
                                                        $status = $row_os->status;
                                                        $status_txt = '';

                                                        if ($status === '1') {
                                                            $status_txt = 'Aberto';
                                                        }else if ($status === '2') {
                                                            $status_txt = 'Em Execução';
                                                        }else if ($status === '3') {
                                                            $status_txt = 'Finalizado';
                                                        }else if ($status === '4') {
                                                            $status_txt = 'Em Levantamento';
                                                        }else if ($status === '5') {
                                                            $status_txt = 'Em Cotação';
                                                        }

                                                        $dt_abertura = $row_os->dt_abertura;

                                                        $data = explode(' ', $dt_abertura)[0];

                                                        $ano = explode('-', $data)[0]; 
                                                        $mes = explode('-', $data)[1];
                                                        $dia = explode('-', $data)[2];
 
                                                        $temp = $dia.'/'.$mes.'/'.$ano;

                                                        $dt_fechamento = $row_os->dt_conclusao;
                                                        $tipo = $row_os->tipo_chamado;
 
                                                        if($filtro === '4'){
                                                            $hoje = date("d/m/Y");
                                                            $vencimento_sla_a = DiasUteis($temp,$hoje);
                                                            if($vencimento_sla_a > 1){
                                                                $inserir = true;
                                                            }
                                                        }else if($filtro === '5'){
                                                            $hoje = date("d/m/Y");
                                                            $vencimento_sla_c_em = DiasUteis($temp,$hoje);
                                                            if($tipo === '0' && $vencimento_sla_c_em >= 13){
                                                                $inserir = true;
                                                            }
                                                        }else if($filtro === '18'){
                                                            $hoje = date("d/m/Y");
                                                            $vencimento_sla_c_ex = DiasUteis($temp,$hoje);
                                                            if($tipo === '1' && $vencimento_sla_c_ex >= 27){
                                                                $inserir = true;
                                                            }
                                                        }else{
                                                            $inserir = true;
                                                        }

                                                        if ($inserir) {
                                                            echo "<tr class='odd gradeX'>
                                                                    <td>$row_os->id_os</td>
                                                                    <td>$row_os->protocolo</td>
                                                                    <td>$temp</td>
                                                                    <td>$row_os->nome_escola</td>
                                                                    <td>$row_os->sub_motivo</td>";
                                                                    if (!is_null($dt_fechamento)){

                                                                        $dt_fechamento = date('d/m/Y', strtotime($dt_fechamento));
                                                                        echo "<td>$dt_fechamento</td>";

                                                                    }else{

                                                                        echo "<td></td>";
                                                                    }
                                                                    if ($status === '1'){
                                                                        echo "<td class='center' style='color: #d9534f'><b>$status_txt</b></td>";
                                                                    }else if ($status === '2'){
                                                                        echo "<td class='center' style='color: #8C1717'><b>$status_txt</b></td>";
                                                                    }else if ($status === '3'){
                                                                        echo "<td class='center' style='color: #238E23'><b>$status_txt</b></td>";
                                                                    }else if ($status === '4'){
                                                                        echo "<td class='center' style='color: #FF6600'><b>$status_txt</b></td>";
                                                                    }else if ($status === '5'){
                                                                        echo "<td class='center' style='color: #000000Y'><b>$status_txt</b></td>";
                                                                    }
                                                        
                                                                    echo "<td class='center'>
                                                                            <a href='setor_ver_os.php?id_os=$row_os->id_os' target='_blank'><i class='material-icons dp48'>visibility</i></a>
                                                                          </td>
                                                                   </tr>";   
                                                        }

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

         <!-- DATA TABLE SCRIPTS -->
        <script src="assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
        </script>
        
        <!-- Custom Js -->
        <script src="assets/js/custom-scripts.js"></script> 

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