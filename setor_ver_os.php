<?php

$id_os = $_GET['id_os'];

if(!isset($_SESSION)){ 

    session_start(); 
}

include('libraries/functions_date.php');

$usuario = $_SESSION['user_name'];

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');

$sede = '';

$query_user = "SELECT * FROM sede WHERE user_sede = '$usuario'";
$result_user = $link->query($query_user);
$row_user = mysqli_fetch_object($result_user);
$sede = $row_user->sede;

$query_OS = "SELECT * FROM ordem_servico WHERE id_os = '$id_os'";
$result_OS = $link->query($query_OS);
$row_OS = mysqli_fetch_object($result_OS);
$id_escola = $row_OS->fk_id_nome_escola;
$protocolo = $row_OS->protocolo;
$status = $row_OS->status;
$obs_operador = $row_OS->obs_operador;
$sla_a = 0;
$sla_c = 0;

$dt_abertura = date_create($row_OS->dt_abertura); // ################################################### tirar de baixo e por aqui
$data_abertura = $dt_abertura->format('d/m/Y'); // ################################################### tirar de baixo e por aqui

$DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
$hoje = $DT_atual->format('d/m/Y');

// ########################################################################## modificado aqui #######################################################################

if ($status === '1') {

    $sla_a = DiasUteis($data_abertura,$hoje); // contabiliza
    $sla_c = DiasUteis($data_abertura,$hoje); // contabiliza
   
}else if ($status !== '1' && $status !== '3') {

    $sla_a = $row_OS->sla_atendimento; 
    $sla_c = DiasUteis($data_abertura,$hoje); // contabiliza

}else if ($status === '3') {
    
    $sla_a = $row_OS->sla_atendimento;
    $sla_c = $row_OS->sla_conclusao;
}

// ########################################################################## modificado aqui #######################################################################

$data_conclusao = $row_OS->dt_conclusao;
$pos_venda = $row_OS->pos_venda;
$pre_venda = $row_OS->pre_venda;
//$levantamento = $row_OS->levantamento;

//echo "<script>alert('$pos_venda')</script>";

$query_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'"; 
$result_escola = $link->query($query_escola);
$row_escola = mysqli_fetch_object($result_escola);
$inep_escola = $row_escola->inep;
$gre = $row_escola->gre;
$cidade = $row_escola->cidade;
$nome_escola = $row_escola->nome_escola;
$gestor_escola = $row_escola->responsavel;
$contato01 = $row_escola->contato01;
$contato02 = $row_escola->contato02;

$id_serial = $row_escola->fk_id_biometria;

$query_bio = "SELECT * FROM biometrias WHERE id_biometria = '$id_serial'"; 
$result_bio = $link->query($query_bio);
if (mysqli_num_rows($result_bio) > 0) {
    $row_bio = mysqli_fetch_object($result_bio);
    $serial_b = $row_bio->serial_bio;
}else{
    $serial_b = "Não possui...";
}



$query_levt = "SELECT * FROM cotacoes_os WHERE fk_id_os_cotada = '$id_os'";
$result_levt = mysqli_num_rows($link->query($query_levt));

if(isset($usuario)  && !is_numeric($usuario)){ // tratar para que não possa ser visto OSs de outro setores passando id na url (verificar se escola pertence ao user)

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

    <!-- For pending status -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".alerta-pendencia").click(function(){
              var posts = $(this).attr('id-exec');
              var status = $(this).attr('status-pendencia');
              $.post('executions/update_pending_status.php', {key: posts, status: status}, function(retorno){
                     window.location.reload(true);
               });
            });
        });
    </script>

    <script>
        function myFunction() {
            var txt;
            var r = confirm("Press a button!");
            if (r == true) {
                txt = "You pressed OK!";
            } else {
                txt = "You pressed Cancel!";
            }
        }
    </script>

    <script type='text/javascript'>//<![CDATA[

        $(window).load(function(){
        $('#click').click(function(){
            var files = $('input#files')[0].files;

            if (files.length > 20) {
                document.getElementById('foto_ok').value = '1';
                //return false;
            }
       
        });
        });//]]>
 
    </script>

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

            var status = document.getElementById('status').value;
            var pos_venda = document.getElementById('pos_venda').value;
            var id_os = document.getElementById('id_os').value;
            var user = document.getElementById('usuario').value;
            var v_lev_h = document.getElementById('v_lev_h').value;

            if (status.localeCompare('1') == 0){

                document.getElementById("btn5").innerHTML = "<a id='btn5' href='inserir_pre_venda.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Pré-venda</span></a>";

            }else if (status.localeCompare('2') == 0) {

                document.getElementById("btn1").innerHTML = "<a id='btn1' href='setor_inserir_execucao.php?id_os=<?php echo $id_os.'&protocolo='.$protocolo;?>' class='collection-item'><span class='badgeimp_exe'>Inserir Execução </span></a>";
                document.getElementById("btn2").innerHTML = "<a id='btn2' href='setor_fechar_os.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Fechar OS</span></a>";
                if (parseInt(v_lev_h) < 1) {

                    document.getElementById("btn_vlev").innerHTML = "<a id='btn_vlev' href='voltar_levantamento.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Voltar Levant.</span></a>";
                }
                

            }else if (status.localeCompare('3') == 0) {

                

                if (user.localeCompare('0') == 0) {
                    document.getElementById("btn_rel_obra").innerHTML = "<a id='btn_rel_obra' href='rel_obra/gera_rel_obra.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Gera Rel.</span></a>";
                }

                if (pos_venda.localeCompare('') == 0) {
                    document.getElementById("btn4").innerHTML = "<a id='btn4' href='inserir_pos_venda.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Pós-venda</span></a>";
                }

                var protoc = document.getElementById('protocolo').value; 

                document.getElementById('btn_doc').innerHTML = "<a id='doc_btn' href='documents/" + protoc + ".pdf' class='collection-item'><span class='badgeimp_end'>DOC</span></a>";    
                
            }else if (status.localeCompare('4') == 0) {

                document.getElementById("btn_list").innerHTML = "<a id='btn_list' href='inserir_lev_os.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Levantamento</span></a>";
                

                document.getElementById("btn2").innerHTML = "<a id='btn2' href='setor_fechar_os.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Fechar OS</span></a>";
                
                document.getElementById("btn_exec").innerHTML = "<a id='btn_exec' href='ir_exec.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Ir p/ Exec</span></a>";

                
            }else if (status.localeCompare('5') == 0) {

                if (user.localeCompare('0') == 0) {
                    document.getElementById("btn_rel_obra").innerHTML = "<a id='btn_rel_obra' href='rel_obra/gera_rel_obra.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Gera Rel. Obra</span></a>";
                }

                document.getElementById("btn2").innerHTML = "<a id='btn2' href='setor_fechar_os.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Fechar OS</span></a>";
                document.getElementById("btn_cot").innerHTML = "<a id='btn_cot' href='executions/aprovar_cotacao.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>Aprovar Cotação</span></a>";
                document.getElementById("add_item_cot").innerHTML = "<a id='add_item_cot' href='novo_item_cotacao.php?id_os= <?php echo $id_os; ?>' class='collection-item'><span class='badgeimp_end'>ADD Itens</span></a>";
            }

            document.getElementById('btn_img').innerHTML = "<a id='btn_img' href='galeria/index.php?id_os=" + id_os + "' class='collection-item' target='_blank'><span class='badgeimp_end'>IMG</span></a>";

        }

    </script>

    <body onload="botoes()">
        
        <?php include("setor_menu.php"); ?>
       
            <!-- /. NAV SIDE  -->

            <div id="page-wrapper">

                <div class="header"> 
                    <h1 class="page-header">
                        Informações do protocolo: <strong><?php echo $protocolo; ?></strong>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você tem informações gerais do chamado.</li>
                    </ol> 
                                        
                </div>

                <div id="page-inner">

                    <div class="row">
                        
                        <?php 

                            $id_escola = $row_OS->fk_id_nome_escola;

                            $dt_abertura = date_create($row_OS->dt_abertura);
                            $data_abertura = date_format($dt_abertura, 'd/m/Y');

                            $status = $row_OS->status;

                            if ($status === '3'){
                                $dt_conclusao = date_create($row_OS->dt_conclusao);
                                $data_conclusao = date_format($dt_conclusao, 'd/m/Y');
                            }else{
                                $data_conclusao = "";
                            }


                            $status_txt = '';
                            if ($status == '1') {
                                $status_txt = 'Aberto';
                            }else if ($status == '2') {
                                $status_txt = 'Em Execução';
                            }else if ($status == '4') {
                                $status_txt = 'Em Levantamento';
                            }else if ($status == '5') {
                                $status_txt = 'Em Cotação';
                            }else{
                                $status_txt = 'Finalizado';
                            }

                            $id_motivo = $row_OS->fk_id_motivo_os;
                            $query_motivo = "SELECT * FROM sub_motivo_chamado where id_sub_motivo = '$id_motivo'";
                            $result_motivo = $link->query($query_motivo);
                            $row_motivo = mysqli_fetch_object($result_motivo);
                            $motivo = $row_motivo->sub_motivo;

                            $nome_escola = $row_escola->nome_escola;
                            $gestor_escola = $row_escola->responsavel;
                            $avaliacao = $row_OS->avaliacao;

                            $query_get_sede = "SELECT sede, user_sede FROM sede WHERE user_sede = '$usuario'";
                            $result_id = $link->query($query_get_sede) or die(mysqli_error($link));
                            while($row_id = mysqli_fetch_assoc($result_id)){
                                $sede = $row_id['sede'];
                            }

                        ?>
                        
                        <div class="col-md-7 col-sm-12 col-xs-12">

                            <div class="card">
                                <div class="card-action">
                                    <b><i class="material-icons left">info_outline</i>Dados do Chamado</b>
                                    <a href="javascript:;" onmousedown="toggleDiv('div_dados_os');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                    
                                </div>

                                
                                <div class="divider"></div>
                                <div class="card-image" id="div_dados_os">
                                    <ul class="card-content">
                                        <li><p><strong>Nº Protocolo: </strong><?php echo $protocolo;?></p></li>
                                        <li><p><strong>Motivo: </strong><?php echo $motivo;?></p></li>
                                        <li><p><strong>Status: </strong><?php echo $status_txt;?></p></li>
                                        <li><p><strong>Aberto em: </strong><?php echo $data_abertura;?></p></li>
                                        <li><p><strong>Concluído em: </strong><?php echo $data_conclusao;?></p></li>
                                        <li><p><strong>SLA Atendimento: </strong><?php echo $sla_a;?></p></li>
                                        <li><p><strong>SLA Conclusão: </strong><?php echo $sla_c;?></p></li>
                                        <div class='tooltip'>
                                            <li><p><strong>Escola: </strong><a target="_blank" href="timeline_tudao.php?id_escola=<?php echo $id_escola; ?>"><?php echo $nome_escola;?></a><span class='tooltiptextbottom'>Histórico da Escola</span></p></li>
                                        </div>
                                        
                                        <li><p><strong>Gestor: </strong><?php echo $gestor_escola;?></p></li>
                                        <li><p><strong>Telefones: </strong><?php echo $contato01.' '.$contato02;?></p></li>
                                        <li><p><strong>Cidade: </strong><?php echo $cidade.' - '.$gre.'ª GRE';?></p></li>
                                        <li><p><strong>Avaliação: </strong><?php echo $avaliacao; ?></p></li>
                                        <?php
                                            if (!is_null($obs_operador)) { ?>
                                                <li><p><strong>OBS Operador: </strong><?php echo $obs_operador; ?></p></li>
                                            <?php }
                                            if (!is_null($serial_b)) { ?>
                                                <li><p><strong>Serial Biometria: </strong><?php echo $serial_b; ?></p></li>
                                            <?php }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div> 

                        <script type="text/javascript">
                            
                            $(document).ready(function(e) { 
                                $("#todos").click(function(e) { 
                                    if($(this).is(':checked')) { //Retornar true ou false  
                                        document.getElementById('os').checked = 'checked';
                                        document.getElementById('execucao').checked = 'checked';
                                        document.getElementById('checklist').checked = 'checked';
                                        document.getElementById('levantamento').checked = 'checked';
                                        document.getElementById('lista_materiais').checked = 'checked';
                                        document.getElementById('preventiva').checked = 'checked';
                                        //document.getElementById('lista_insumos').checked = 'checked';
                                    } else {
                                        document.getElementById('os').checked = false;
                                        document.getElementById('execucao').checked = false;
                                        document.getElementById('checklist').checked = false;
                                        document.getElementById('levantamento').checked = false;
                                        document.getElementById('lista_materiais').checked = false;
                                        document.getElementById('preventiva').checked = false;
                                        //document.getElementById('lista_insumos').checked = false;
                                    }
                                }); 
                            });

                        </script>  

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-action">
                                    <b>Impressões<i class="material-icons left">print</i></b>
                                    <a href="javascript:;" onmousedown="toggleDiv('div_print');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                </div>
                                <div class="divider"></div>
                                <div class="card-image"  id="div_print">
                                    <div class="card-content">
                                        <form name="form-multiple_print" action="docs/print_forms.php" method="post" target="_blank">
                                            <div>
                                                <p><input type="checkbox" class="filled-in" id="todos" value="todos"/><label for="todos">Todos</label></p>
                                                <p><input type="checkbox" class="filled-in" id="os" name="multiples_imp[]" value="os"/><label for="os">Ordem de Serviço</label></p>
                                                <?php

                                                    if ($pre_venda != ""){
                                                        ?>
                                                        <p><input type="checkbox" class="filled-in" id="execucao" name="multiples_imp[]" value="execucao"/><label for="execucao">Execução</label></p>
                                                        <p><input type="checkbox" class="filled-in" id="checklist" name="multiples_imp[]" value="checklist"/><label for="checklist">Checklist Laboratórios</label></p>
                                                        <p><input type="checkbox" class="filled-in" id="levantamento" name="multiples_imp[]" value="levantamento"/><label for="levantamento">Levantamento</label></p>
                                                        <p><input type="checkbox" class="filled-in" id="lista_materiais" name="multiples_imp[]" value="lista_materiais"/><label for="lista_materiais">Lista de Materiais</label></p>
                                                        <!-- <p><input type="checkbox" class="filled-in" id="lista_insumos" name="multiples_imp[]" value="lista_insumos"/><label for="lista_insumos">Lista de Insumos</label></p> -->
                                                        <p><input type="checkbox" class="filled-in" id="preventiva" name="multiples_imp[]" value="preventiva"/><label for="preventiva">Checklist Mnt. (Preventiva/Corretiva)</label></p>

                                                        <?php
                                                    }
                                                ?>
                                                
                                            </div>
                                            <div class="take-down">
                                                <input type="hidden" id="usuario" value="<?php echo $sede; ?>">
                                                <input type="hidden" name="id_os" value="<?php echo $id_os; ?>">
                                                <button type="submit" class="waves-effect waves-ligh btn" id="submit">Gerar Arquivo</button>
                                            </div>
                                        </form>
                                    </div>
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
                                        <input type="hidden" name="pos_venda" id="pos_venda" value="<?php echo $pos_venda;?>">
                                        <input type="hidden" name="protocolo" id="protocolo" value="<?php echo $protocolo;?>">
                                        <input type="hidden" name="v_lev_h" id="v_lev_h" value="<?php echo $result_levt;?>">
                                        <!-- <input type="hidden" id="num_det" value="<?php echo $num_det; ?>"> -->
                                        <div id='btn1'></div>
                                        <div id='btn2'></div>
                                        <div id='btn3'></div>
                                        <div id='btn4'></div>
                                        <div id='btn5'></div>
                                        <div id='btn_lev'></div>
                                        <div id='btn_doc'></div>
                                        <div id='btn_img'></div>
                                        <div id='btn_list'></div>
                                        <div id='btn_exec'></div>
                                        <div id='btn_rel_obra'></div>
                                        <div id='btn_vlev'></div> 
                                        

                                        <?php   

                                        if ($sede === '0') {
                                        ?> 
                                            <div id='btn_cot'></div> 
                                        <?php
                                        }
                                        ?> 
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                

                    <?php //################################################################################################################################################################

                    //if ($status != 3 && $status != 1) {
                    ?>

                    <!--<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12"> 
                            <div class="card"> 
                                    <div class="card-action">
                                        <b>Técnicos p/ APP: <i class="fa fa-user material-icons left"></i></b>
                                        <a href="javascript:;" onmousedown="toggleDiv('div_tecnico');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="card-image" id="div_tecnico">
                                        <ul class="card-content"> -->
                                            <?php
                                                /** $query_ultima_data = "SELECT max(data) DT FROM tecnicos_os where fk_id_os = '$id_os'";
                                                $result_ultima_data = $link->query($query_ultima_data) or die(mysqli_error($link));
                                                $row_ultima_data = mysqli_fetch_object($result_ultima_data);
                                                $data = $row_ultima_data->DT;
                                                if (is_null($data)) {
                                                    echo "<li><p><strong>Atenção: </strong>Não foram informados técnicos para essa OS!</p></li>";  
                                                }else{
                                                    $sql_tecnicos = "SELECT T.nome_tecnico NT FROM tecnico T, tecnicos_os T_O where T_O.fk_id_os = '$id_os' and T_O.fk_id_tec = T.id_tecnico and T_O.data = '$data'";
                                                    $result_tecnicos = $link->query($sql_tecnicos) or die(mysqli_error($link));
                                                    while($row_tecnicos = mysqli_fetch_object($result_tecnicos)) {
                                                        echo "<li><p><strong>Técnico: </strong>".$row_tecnicos->NT."</p></li>";
                                                    }
                                                } **/
                                            ?>
                                        <!-- </ul>
                                        <div class="card-content">
                                            <form name="photo-attacement" method="POST" action="escolhe_tecnicos.php">
                                                <div class="row">
                                                    <input type="hidden" name="id_os" id="id_os" value="<?php echo $id_os; ?>">
                                                </div>
                                                <div class="take-down">
                                                    <input type="submit" name="enviar" class="waves-effect waves-ligh btn" value="Modificar"/>
                                                    
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    <?php 

                    //}  //######################################################################################################################################################################

                    ?>

                    <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                    <div class="card-action">
                                        <b>Anexar Fotos<i class="fa fa-paperclip material-icons left"></i></b>
                                        <a href="javascript:;" onmousedown="toggleDiv('div_fotos');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="card-image" id="div_fotos">
                                        <div class="card-content">
                                            <form name="photo-attacement" method="POST" enctype="multipart/form-data" action="executions/salvar_foto_os.php">
                                                <div class="file-field">
                                                    <div class="btn custom-back">
                                                        <span>Selecione</span>
                                                        <input type="file" id="files" name="imagem[]" class="uploadClassificado" multiple>
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Arquivos...">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" name="id_os" id="id_os" value="<?php echo $id_os; ?>"> 
                                                    <input type="hidden" name="foto_ok" id="foto_ok" value="0">
                                                </div>
                                                <div class="take-down">   
                                                    <input type="submit" name="enviar" value="ENVIAR" id="click" class="waves-effect waves-ligh btn" />
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 

                    <?php //########################################################################## PRE VENDA #################################################################################

                    if ($status > 1) {
                    ?>

                    <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12"> 
                            <div class="card"> 
                                    <div class="card-action">
                                        <b>Pré-venda: <i class="fa fa-user material-icons left"></i></b>
                                        <a href="javascript:;" onmousedown="toggleDiv('div_prevenda');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="card-image" id="div_prevenda">
                                        <ul class="card-content">
                                            <?php
                                                
                                                echo "<li><p><strong><br></strong>".$pre_venda."</p></li>";  
                                                
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <?php 

                    }  //##################################################################################################################################################################

                    ?>

                    <?php //########################################################################## LEVANTAMENTO ########################################################################

                                  
                    if ($status != 1 && $result_levt > 0) {
                    ?>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12"> 
                                <div class="card"> 
                                    <div class="card-action">
                                        <b>Levantamento de materiais para Obra: <i class="fa fa-user material-icons left"></i></b>
                                        <a href="javascript:;" onmousedown="toggleDiv('div_levantamento');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="card-image" id="div_levantamento">
                                        <ul class="card-content">
                                            <?php
                                                
                                            $lev_ok = false;

                                            $query_cot_os = "SELECT * FROM cotacoes_os WHERE fk_id_os_cotada = '$id_os'";
                                            $result_cot_os = $link->query($query_cot_os);
                                            if (mysqli_num_rows($result_cot_os) > 0){

                                                
                                                $row_cot_os = mysqli_fetch_object($result_cot_os);
                                                $obs_cotacao = $row_cot_os->obs_cotacao;
                                                $id_cotacao = $row_cot_os->id_cotacao;

                                                $query_itens = "SELECT itdc.nome_item nome, itdc.tipo_item tipo, itc.quantidade qtde, itc.referencia ref, itc.local_destino loc FROM itens_cotados itc, itens_de_cotacao itdc WHERE itc.fk_id_cotacao = '$id_cotacao' and itc.fk_id_item = itdc.id_item";
                                                $result_itens = $link->query($query_itens);

                                                echo "<li><p><strong><br></strong>".$obs_cotacao.'<br>';

                                                
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
                                                
                                                
                                            }
                                              
                                                
                                            ?>
                                        </ul>

                                        <div class="card-content">
                                        <?php if ($lev_ok) { ?>
                                            <div class="take-down">       
                                                <a href="gera_lev_os.php?id_os=<?php echo $id_os; ?>" class='collection-item'><span class='badgeimp_exe'>Carregar Planilha</span></a>
                                            </div> <br> <div id='add_item_cot'></div> 

                                            <?php
                                        } ?>
                                        
                                    </div>
                                    </div>

                                    
                        
                                </div>
                            </div>
                        </div> 
                    <?php 

                    }  //#####################################################################################################################################################################

                    ?>

                    <!-- End Advanced Tables -->

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="card-action">
                                     <b><i class="material-icons left">restore</i>Linha do Tempo:</b>
                                     <a href="javascript:;" onmousedown="toggleDiv('div_timeline');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                </div> 
                                <div class="divider"></div>
                                <div class="card-content" id="div_timeline">
                                    
                                    <!-- ################################################################## TIMELINE ################################################################### -->

                                    <center><span class="badge"><?php echo $protocolo; ?></span></center>
                                    <div id="vt6">
                                        <?php

                                            $query_execs = "SELECT * FROM execucao_diaria where fk_id_ordem_servico = '$id_os' ORDER BY data_execucao";
                                            $result_execs = $link->query($query_execs);

                                            if(mysqli_num_rows($result_execs) > 0){
        
                                                while($row_execs = mysqli_fetch_object($result_execs)) { 

                                                    $data = date_create($row_execs->data_execucao);
                                                    $data_execucao = date_format($data, 'd/m/Y');

                                                    $id_exec = $row_execs->id_execucao;
                                                    $tecnicos = $row_execs->tecnicos;
                                                    $local = $row_execs->local;
                                                    $servico = $row_execs->servico;
                                                    $observacao = $row_execs->observacao;

                                                    $relato = $tecnicos."<br>".$local."".$servico."".$observacao;
                                                    $relato2 = "<br><b>Executado por: </b>".$tecnicos."<br><b>Local da execução: </b>".$local."<br><b>Serviço realizado: </b>".$servico."<br><b>Observações: </b>".$observacao;

                                                    $agulha   = 'Observações:';

                                                    $pos = strpos($relato, $agulha);

                                                    echo "<div data-vtdate='$data_execucao'>
                                                            <h4>Serviços realizados</h4>
                                                            <br>";

                                                    $pending_topic = "<b>Pendências: </b>";

                                                    $pendencias = $row_execs->pendencias;
                                                    $tem_pendencia = $row_execs->status_pendencia;


                                                    if ($pos === false) {
                                                        echo "<p>$relato2</p>";
                                                        $pendencias =  $pending_topic.$pendencias;
                                                    } else {
                                                        echo "<p>$relato</p>";
                                                    }

                                                    if(!is_null($tem_pendencia)){

                                                        if($tem_pendencia == 0){

                                                            echo "<p><a href='#!' class='alerta-pendencia green' id-exec='".$id_exec."' status-pendencia='1'></a>" . $pendencias ."</p>";

                                                        }else{

                                                            echo "<p><a href='#!' class='alerta-pendencia red' id-exec='".$id_exec."' status-pendencia='0'></a>" . $pendencias ." </p>";
                                                        }     
                                                    }
                                                    echo "</div>";
                                                    
                                                }  
                                            }
                                        ?>     
                                    </div><!-- End vt6 -->
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
                                    <script src="dist/vertical-timeline.js"></script>
                                    <script>
                                        $('#vt1').verticalTimeline();
                                        $('#vt2').verticalTimeline();
                                        $('#vt3').verticalTimeline({
                                            startLeft: false
                                        });
                                        $('#vt4').verticalTimeline({
                                            startLeft: false,
                                            arrows: false,
                                            alternate: false

                                        });
                                        $('#vt5').verticalTimeline({
                                            animate: 'fade'
                                        });
                                        $('#vt6').verticalTimeline({
                                            animate: 'slide'
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php //########################################################################## POS VENDA #################################################################################

                    if ($status > 2) {
                    ?>

                    <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12"> 
                            <div class="card"> 
                                    <div class="card-action">
                                        <b>Pós-venda: <i class="fa fa-user material-icons left"></i></b>
                                        <a href="javascript:;" onmousedown="toggleDiv('div_posvenda');"><i class='material-icons dp48'>aspect_ratio</i></a>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="card-image" id="div_posvenda">
                                        <ul class="card-content">
                                            <?php
                                                
                                                echo "<li><p><strong><br></strong>".$pos_venda."</p></li>";  
                                                
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <?php 

                    }  //#####################################################################################################################################################################

                    ?>
                   
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