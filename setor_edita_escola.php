<?php

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
                            
        function verifica() { //'MP', 'VT', 'I', 'IR', 'LI', 'B','CA','SA' salva_edit_escola altera_bio

            if (document.getElementById('alterar').checked) {

                document.getElementById('qtde_1').disabled = true;
                document.getElementById('qtde_2').disabled = true;
                document.getElementById('qtde_3').disabled = true;
                document.getElementById('qtde_4').disabled = true;
                document.getElementById('qtde_5').disabled = true;
                

                document.getElementById('MP').disabled = true;
                document.getElementById('VT').disabled = true;
                document.getElementById('I').disabled = true;
                document.getElementById('IR').disabled = true;
                document.getElementById('LI').disabled = true;
                document.getElementById('B').disabled = true;
                document.getElementById('CA').disabled = true;
                document.getElementById('SA').disabled = true;
                document.getElementById('salva_edit_escola').disabled = true;
                document.getElementById('alterar_s').style.display = "inline";

            }else{

                document.getElementById('qtde_1').disabled = false;
                document.getElementById('qtde_2').disabled = false;
                document.getElementById('qtde_3').disabled = false;
                document.getElementById('qtde_4').disabled = false;
                document.getElementById('qtde_5').disabled = false;
                

                document.getElementById('MP').disabled = false;
                document.getElementById('VT').disabled = false;
                document.getElementById('I').disabled = false;
                document.getElementById('IR').disabled = false;
                document.getElementById('LI').disabled = false;
                document.getElementById('B').disabled = false;
                document.getElementById('CA').disabled = false;
                document.getElementById('SA').disabled = false;
                document.getElementById('salva_edit_escola').disabled = false;
                document.getElementById('alterar_s').style.display = "none";

            }
        
        }

    </script>

    <body>     
        <?php include("setor_menu.php"); ?>


        <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        Edição De CheckList Escola
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você edita os pontos do Checlist Infraestrutura da escola.</li>
                    </ol> 
                                        
                </div>

                <div id="page-inner">               
                    <div class="card-content"> 
                        <div class="card-action">


                            <?php 

                                $id_escola = $_GET['id_escola'];

                                $query_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'";
                                $result_escola = $link->query($query_escola) or die(mysqli_error($link));

                                if (mysqli_num_rows($result_escola) > 0) {

                                    $row_escola = mysqli_fetch_object($result_escola);

                                    $query_infra = "SELECT * FROM infraestrutura WHERE fk_id_escola = '$id_escola'";
                                    $result_infra = $link->query($query_infra);
                                    $row_infra = mysqli_fetch_object($result_infra);

                                    ?>
                                
                                    <table class="table table-striped table-bordered table-hover">
                                        <tr>
                                            <td><b>Escola: </b></td>
                                            <td colspan="7"><?php echo $row_escola->nome_escola; ?></td>                                
                                        </tr>
                                        <tr>
                                            <td><b>GRE: </b></td>
                                            <td><?php echo $row_escola->gre; ?>ª GRE</td>
                                            <td><b>Cidade: </b></td>
                                            <td colspan="5"><?php echo $row_escola->cidade; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Endereço: </b></td>
                                            <td colspan="7"><?php echo $row_escola->endereco; ?></td>     
                                        </tr>
                                        <tr>
                                            <td><b>Responsável: </b></td>
                                            <td colspan="3"><?php echo $row_escola->responsavel; ?></td>
                                            <td><b>Contato 1: </b></td>
                                            <td colspan="3"><?php echo $row_escola->contato01; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Contato 2: </b></td>
                                            <td colspan="2"><?php echo $row_escola->contato02; ?></td>
                                            <td><b>E-mail: </b></td>
                                            <td colspan="4"><?php echo $row_escola->email; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><center><b>CheckList Infraestrutura: </b></center></td>                      
                                        </tr>
                                    </table>

                                    <form method="post" action="executions/salva_edit_escola.php" name="edita_escola">
                                    <div class="row">
                                        <table class="table table-striped table-bordered table-hover">

                                    <?php

                                    $labels = array('MP', 'VT', 'I', 'IR', 'B', 'LI','CA','SA');
                                    $titulos = array('Manutenção Preventiva: ', 'Vistoria Técnica: ', 'Internet: ', 'Infraestrutura Administrativa: ', 'Biometria: ', 'Laboratório de Informática: ', 'Possui Câmera: ', 'Saber Atualizado: ');
                                    $radios = array($row_infra->mnt_preventiva, $row_infra->vistoria, $row_infra->internet, $row_infra->infra_rede, $row_infra->biometria, $row_infra->lab_info, $row_infra->camera, $row_infra->saber);

                                    for ($i=0; $i < count($radios); $i= $i+=4) { ?>
                                        <tr>
                                            <td style="text-align: center; line-height: 50px"><b><?php echo $titulos[$i]; ?></b></td> <?php
                                        if ($radios[$i] === 'X') { ?>
                                            <td style="text-align: center; vertical-align: middle;"><p><input disabled class="filled-in" type="checkbox" name="<?php echo $labels[$i]; ?>" id="<?php echo $labels[$i]; ?>" checked="checked" /><label for="<?php echo $labels[$i]; ?>"> </label></p></td> <?php
                                            
                                        }else{ ?>
                                            <td style="text-align: center; vertical-align: middle;"><p><input disabled class="filled-in" type="checkbox" name="<?php echo $labels[$i]; ?>" id="<?php echo $labels[$i]; ?>" /><label for="<?php echo $labels[$i]; ?>"> </label></p></td> <?php 
                                        } ?>
                                            <td style="text-align: center; line-height: 50px"><b><?php echo $titulos[$i+1]; ?></b></td> <?php
                                        if ($radios[$i+1] === 'X') { ?>
                                            <td style="text-align: center; vertical-align: middle;"><p><input disabled class="filled-in" type="checkbox" name="<?php echo $labels[$i+1]; ?>" id="<?php echo $labels[$i+1]; ?>" checked="checked" /><label for="<?php echo $labels[$i+1]; ?>"> </label></p></td> <?php
                                            
                                        }else{ ?>
                                            <td style="text-align: center; vertical-align: middle;"><p><input disabled class="filled-in" type="checkbox" name="<?php echo $labels[$i+1]; ?>" id="<?php echo $labels[$i+1]; ?>" /><label for="<?php echo $labels[$i+1]; ?>"> </label></p></td> <?php
                                        } ?>
                                        <td style="text-align: center; line-height: 50px"><b><?php echo $titulos[$i+2]; ?></b></td> <?php
                                        if ($radios[$i+2] === 'X') { ?>
                                            <td style="text-align: center; vertical-align: middle;"><p><input disabled class="filled-in" type="checkbox" name="<?php echo $labels[$i+2]; ?>" id="<?php echo $labels[$i+2]; ?>" checked="checked" /><label for="<?php echo $labels[$i+2]; ?>"> </label></p></td> <?php
                                            
                                        }else{ ?>
                                            <td style="text-align: center; vertical-align: middle;"><p><input disabled class="filled-in" type="checkbox" name="<?php echo $labels[$i+2]; ?>" id="<?php echo $labels[$i+2]; ?>" /><label for="<?php echo $labels[$i+2]; ?>"> </label></p></td> <?php 
                                        } ?>
                                            <td style="text-align: center; line-height: 50px"><b><?php echo $titulos[$i+3]; ?></b></td> <?php
                                        if ($radios[$i+3] === 'X') { ?>
                                            <td style="text-align: center; vertical-align: middle;"><p><input disabled class="filled-in" type="checkbox" name="<?php echo $labels[$i+3]; ?>" id="<?php echo $labels[$i+3]; ?>" checked="checked" /><label for="<?php echo $labels[$i+3]; ?>"> </label></p></td> <?php
                                            
                                        }else{ ?>
                                            <td style="text-align: center; vertical-align: middle;"><p><input disabled class="filled-in" type="checkbox" name="<?php echo $labels[$i+3]; ?>" id="<?php echo $labels[$i+3]; ?>" /><label for="<?php echo $labels[$i+3]; ?>"> </label></p></td> <?php
                                        } ?>

                                        <tr> <?php
                                    }

                                    $tit_qtdes = array('Quantidade de Laboratórios de TI na escola:', 'Quantidade de Computadores na escola:', 'Quantidade de computadores ativos na escola:', 'Quantidade de computadores em Laboratórios de TI na escola:', 'Quantidade de computadores ativos em Laboratórios de TI na escola:');
                                    $qtde_labs_info = $row_infra->qtde_labs_info;
                                    $qtde_pcs = $row_infra->qtde_pcs;
                                    $qtde_pcs_atv = $row_infra->qtde_pcs_atv;
                                    
                                    $qtde_pcs_labs_info = $row_infra->qtde_pcs_labs_info;
                                    $qtde_pcs_labs_info_atv = $row_infra->qtde_pcs_labs_info_atv;
                                    
                                    $qtdes = array($qtde_labs_info, $qtde_pcs, $qtde_pcs_atv, $qtde_pcs_labs_info, $qtde_pcs_labs_info_atv);

                                    echo '<tr style="color: #FF8000"><td><center><b>'.$tit_qtdes[0].':</b></center></td><td><input disabled type="number" name="qtde_1" id="qtde_1" value="'.$qtdes[0].'"></td><td><center><b>'.$tit_qtdes[1].':</b></center></td><td><input disabled type="number" id="qtde_2" name="qtde_2" value="'.$qtdes[1].'"></td><td><center><b>'.$tit_qtdes[2].':</b></center></td><td><input disabled type="number" id="qtde_3" name="qtde_3" value="'.$qtdes[2].'"></td><td><center><b>'.$tit_qtdes[3].':</b></center></td><td><input disabled type="number" id="qtde_4" name="qtde_4" value="'.$qtdes[3].'"></td></tr>';
                                    echo '<tr style="color: #FF8000"><td><center><b>'.$tit_qtdes[4].':</b></center></td><td><input disabled type="number" id="qtde_5" name="qtde_5" value="'.$qtdes[4].'"></td><td></td><td></td><td></td><td></td></tr>';


                                    $id_biometria = $row_escola->fk_id_biometria;

                                    if ($id_biometria === '0') {
                                        $serial_bio = "Não possui...";
                                    }else{
                                        $serial_bio = mysqli_fetch_object($link->query("SELECT * FROM biometrias WHERE id_biometria = '$id_biometria'"))->serial_bio;
                                    }

                                     ?>

                                     <tr>
                                        <td colspan="1" style="text-align: center; line-height: 50px"><center><b>Serial da Biometria:</b></center></td>
                                        <td colspan="7" style="text-align: center; line-height: 50px text-align: center; vertical-align: middle;">
                                            <div class="input-field col s3">
                                                <label><font size="4"> SERIAL: <?php echo $serial_bio; ?></font></label>
                                            </div>
                                        
                                            <div class="input-field col s3">
                                                <p>
                                                    <input type="radio" name="tipo" id="nalterar" value="nalterar" onclick="verifica();">
                                                    <label for="nalterar">Não Alterar</label>
                                                    <input type="radio" name="tipo" checked="checked" id="alterar" value="alterar" onclick="verifica();">
                                                    <label for="alterar">Alterar</label>
                                                </p>
                                            </div>
                                            <div class="input-field col s2" id="alterar_s" name="alterar_s">
                                                <input type="button" class="waves-effect waves-light btn" value="alterar" onclick="window.location.href='altera_serial2.php?id_escola=<?php echo $id_escola; ?>'">
                                            </div>
                                        </td>
                                         
                                     </tr>

                                    </table> </div>
                                    <div class="row">
                                    <div class="col s6">
                                        <div class="row">
                                            <input type="hidden" name="id_escola" id="id_escola" value="<?php echo $id_escola; ?>">
                                        </div>
                                        <div class="input-field col s3">
                                            <button disabled type="submit" id="salva_edit_escola" name="salva_edit_escola" class="waves-effect waves-light btn">Salvar Edição</button>
                                        </div>
                                    </div>
                                </div>  
                                </form><?php

                                }else{
                                    echo "<h4>Essa escola não está cadastrada no banco de dados do sistema...</h4>";
                                }
                            ?>

                            <!-- ############################################### TABELA ################################################## -->
                            

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

