<?php

$id_cot_insumos = $_GET['id_cot'];

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

if(isset($usuario)  && !is_numeric($usuario)){ // tratar para que não possa ser visto OSs de outro setores passando id na url (verificar se escola pertence ao user)

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

    <!-- For pending status -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>

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
           
        function botoes(){

            var id_cot = document.getElementById('id_cot').value;
            var status = document.getElementById('status').value;

            
            document.getElementById('btn_apagar').innerHTML = "<a id='btn_img' href='apagar_cot_ins.php?id_cot=" + id_cot + "' class='collection-item' ><span class='badgeimp_end'>APAGAR</span></a>";

            if (status.localeCompare('0') == 0){

                document.getElementById('btn_aprovar').innerHTML = "<a id='btn_img' href='aprovar_cot_ins.php?id_cot=" + id_cot + "' class='collection-item' ><span class='badgeimp_end'>APROVAR</span></a>";
            }
            
            

        }

    </script>

    <body onload="botoes()">
        
        <?php include("setor_menu.php"); ?>
       
            <!-- /. NAV SIDE  -->

            <div id="page-wrapper">

                <div class="header"> 
                    <h1 class="page-header">
                        Informações da Cotação de Insumos
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você tem informações sobre a Cotação de Insumos.</li>
                    </ol> 
                                        
                </div>

                <div id="page-inner">

                    <div class="row">
                        
                        <?php 

                            $query_cot_insumos = "SELECT * FROM `cotacoes_insumos` WHERE id_cot_insumos = '$id_cot_insumos'";
                            $result_cot_insumos = $link->query($query_cot_insumos);
                            $row_cot_insumo = mysqli_fetch_object($result_cot_insumos);

                            $status = $row_cot_insumo->status_cot_insumos;

                            $status_txt = '';
                            if ($status === '0') {
                                $status_txt = 'Aguardando';
                            }else if ($status === '1') {
                                $status_txt = 'Aprovada';
                            }

                            $id_user =  $row_cot_insumo->fk_id_user_insu;

                            $query_sede = "SELECT * FROM sede WHERE sede_id = '$id_user'";
                            $result_sede = $link->query($query_sede);
                            $row_sede = mysqli_fetch_object($result_sede);
                            $user_cot_insumo = $row_sede->user_sede;

                            $observacao = $row_cot_insumo->obs_cotacao;

                            $dt_abertura = $row_cot_insumo->dt_cot_insumos;

                            $data = explode(' ', $dt_abertura)[0];

                            $ano = explode('-', $data)[0]; 
                            $mes = explode('-', $data)[1];
                            $dia = explode('-', $data)[2];
 
                            $temp = $dia.'/'.$mes.'/'.$ano;

                        ?>
                        
                        <div class="col-md-7 col-sm-12 col-xs-12">

                            <div class="card">
                                <div class="card-action">
                                    <b><i class="material-icons left">info_outline</i>Dados da Cotação de Insumos</b>
                                    <a href="javascript:;" onmousedown="toggleDiv('div_dados_cot_ins');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                    
                                </div>

                                
                                <div class="divider"></div>
                                <div class="card-image" id="div_dados_cot_ins">
                                    <ul class="card-content">
                                        <li><p><strong>Data da Requisição: </strong><?php echo $temp;?></p></li>
                                        <li><p><strong>Usuário: </strong><?php echo $user_cot_insumo;?></p></li>
                                        <li><p><strong>Status: </strong><?php echo $status_txt;?></p></li>
                                        <li><p><strong>Observações: </strong><?php echo $observacao; ?></p></li>
                                    </ul>
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-action">
                                    <b>Ações<i class="material-icons left">input</i></b>
                                    <a href="javascript:;" onmousedown="toggleDiv('div_input');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                </div>
                                <div class="card-image" id="div_input">
                                    <div class="collection">

                                        <input type="hidden" name="status" id="status" value="<?php echo $status;?>">
                                        <input type="hidden" name="id_cot" id="id_cot" value="<?php echo $id_cot_insumos;?>">
                                        <div id='btn_apagar'></div>
                                        <div id='btn_aprovar'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        
                    <!-- ########################################################################## LEVANTAMENTO ######################################################################## -->

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12"> 
                                <div class="card"> 
                                    <div class="card-action">
                                        <b>Cotação de Insumos: <i class="fa fa-user material-icons left"></i></b>
                                        <a href="javascript:;" onmousedown="toggleDiv('div_levantamento');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="card-image" id="div_levantamento">
                                        <ul class="card-content">
                                            <?php
                                                

                                                $query_itens = "SELECT i.nome_insumo nome, i.tipo_insumo tipo, ic.qtde_insumo qtde, ic.referencia_insumo ref, ic.destino_insumo loc 
                                                                FROM insumos_cotados ic, insumos i 
                                                                WHERE ic.fk_id_cot_insumo = '$id_cot_insumos' and ic.fk_id_insumo = i.id_insumo";
                                                $result_itens = $link->query($query_itens);

                                                //echo "<li><p><strong><br></strong>".$obs_cotacao.'<br>';

                                                
                                                // #######################################################################

                                                $msg_itens = '<br><table class="table table-striped table-bordered table-hover">';

                                                $msg_itens.='<tr>
                                                                <th colspan = "5"><center><b>Listagem de itens</b></center></th>
                                                            </tr>
                                                            <tr>
                                                                <th><center><b>Item</b></center></th>
                                                                <th><center><b>Quantidade</b></center></th>
                                                                <th><center><b>Referência</b></center></th>
                                                                <th><center><b>Tipo</b></center></th>
                                                                <th><center><b>Destino</b></center></th>
                                                            </tr>';

                                                while ($row_itens = mysqli_fetch_object($result_itens)) { 

                                                    $msg_itens.='<tr>
                                                                    <td>'.$row_itens->nome.'</td>
                                                                    <td>'.$row_itens->qtde.'</td>
                                                                    <td>'.$row_itens->ref.'</td>
                                                                    <td>'.$row_itens->tipo.'</td>'; 

                                                                    if ($row_itens->loc === '1') {
                                                                        $local = 'Laboratorios';
                                                                    }else if($row_itens->loc === '2'){
                                                                        $local = 'Áreas Administrativas';
                                                                    }else{
                                                                        $local = 'Toda escola';
                                                                    }

                                                                    $msg_itens.='<td>'.$local.'</td>
                                                                </tr>';

                                                }

                                                $msg_itens.='</table>';

                                                
                                                if ($msg_itens !== '<br><table class="table table-striped table-bordered table-hover"><tr>
                                                                <th colspan = "4"><center><b>Listagem de itens</b></center></th>
                                                            </tr>
                                                            <tr>
                                                                <th><center><b>Item</b></center></th>
                                                                <th><center><b>Quantidade</b></center></th>
                                                                <th><center><b>Referência</b></center></th>
                                                                <th><center><b>Tipo</b></center></th>
                                                                <th><center><b>Destino</b></center></th>
                                                            </tr></table>') {

                                                    $lev_ok = true;

                                                    echo $msg_itens."<br>";
                                                    
                                                }
                                                
                                                
                                            
                                              
                                                
                                            ?>
                                        </ul>

                                        <div class="card-content">
                                        <?php if ($lev_ok) { ?>
                                            <div class="take-down">       
                                                <a href="gera_insumos_xlsx.php?id_cot_ins=<?php echo $id_cot_insumos; ?>" class='collection-item'><span class='badgeimp_exe'>Carregar Planilha</span></a>
                                            </div> <?php
                                        } ?>
                                        
                                    </div>
                                    </div>

                                    
                        
                                </div>
                            </div>
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
        
        <!-- Custom Js -->
        <script src="../assets/js/custom-scripts.js"></script>
        
    </body>

    </html>

<?php
}else{

    header("location: ./index.php");
    exit();
}
?>