<?php

include("libraries/feriado.php");

/*require('config.php');*/
include('libraries/functions.php');
include('libraries/functions_date.php');

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($usuario) && !is_numeric($usuario)){

?>

    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <link rel="icon" href="image/ecos_logo.jpg" type="image/x-icon" />

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>HELP-ECOS | Sistema de Apoio às Escolas</title>

        <!-- ############################################### SEARCH SELECT ##################################################  ecos_logo
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />

         ############################################### SEARCH SELECT ################################################## -->
        
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="assets/materialize/css/materialize.min.css" media="screen,projection"/>
        <!-- Bootstrap Styles-->
        <link href="assets/css/bootstrap.css" rel="stylesheet"/>
        <!-- FontAwesome Styles-->
        <link href="assets/css/font-awesome.css" rel="stylesheet"/>
        <!-- Morris Chart Styles-->
        <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet"/>
        <!-- Custom Styles-->
        <link href="assets/css/custom-styles.css" rel="stylesheet"/>
        <!-- Google Fonts-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link rel="stylesheet" href="assets/js/Lightweight-Chart/cssCharts.css"> 



<style type="text/css">
    
    .pinkBg {
    background-color: #ed184f!important;
    background-image: linear-gradient(90deg, red, #fd8b55);
}
.intro-banner-vdo-play-btn{
    height:60px;
    width:60px;
    position:absolute;
    top:5%;
    left:95%;
    text-align:center;
    margin:-30px 0 0 -30px;
    border-radius:100px;
    z-index:1
}
.intro-banner-vdo-play-btn i{
    line-height:56px;
    font-size:30px
}
.intro-banner-vdo-play-btn .ripple{
    position:absolute;
    width:160px;
    height:160px;
    z-index:-1;
    left:50%;
    top:50%;
    opacity:0;
    margin:-80px 0 0 -80px;
    border-radius:100px;
    -webkit-animation:ripple 1.8s infinite;
    animation:ripple 1.8s infinite
}

@-webkit-keyframes ripple{
    0%{
        opacity:1;
        -webkit-transform:scale(0);
        transform:scale(0)
    }
    100%{
        opacity:0;
        -webkit-transform:scale(1);
        transform:scale(1)
    }
}
@keyframes ripple{
    0%{
        opacity:1;
        -webkit-transform:scale(0);
        transform:scale(0)
    }
    100%{
        opacity:0;
        -webkit-transform:scale(1);
        transform:scale(1)
    }
}
.intro-banner-vdo-play-btn .ripple:nth-child(2){
    animation-delay:.3s;
    -webkit-animation-delay:.3s
}
.intro-banner-vdo-play-btn .ripple:nth-child(3){
    animation-delay:.6s;
    -webkit-animation-delay:.6s
}

</style>

    </head>

    <script language="javascript">
        var win = null;
        function NovaJanela(pagina,nome,w,h,scroll){

            if (document.getElementById('sla_a').value > 0 || document.getElementById('sla_c').value > 0){
                LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
                TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
                settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
                win = window.open(pagina,nome,settings);
            }
        }
    </script>

    <script type="text/javascript">

        function alertaSLA(){

            if (document.getElementById('sla_a').value > 0 || document.getElementById('sla_c_em').value > 0){

                document.getElementById('alerta').style.display = 'block';

               //NovaJanela('alerta.html','nomeJanela','450','450','yes');  

            }
        }

              
    </script>

    <script type="text/javascript">
        
        $(function()
{

    $('.btn-alert').on('click', function(event) 
    { 

        if($(event.target).attr('id')=='alerta')
        {
    //  alert('alert1 clicked');
        var type = 'success';
        var message = 'action was a success!'
    
        //
        } 
        
        
        var alertType = 'alert-'+ type
        
       // alert('alert type is: '+ alertType);
        
        var htmlAlert = '<div class="alert '+ alertType +'"><h3>'+ type +' alert</h3><BR><p>'+ message +'</p></div>';
        
       // alert(htmlAlert);
        
        $(".alert-message").prepend(htmlAlert);
        
        $(".alert-message .alert").first().hide().fadeIn(200).delay(2000).fadeOut(1000, function () { $(this).remove(); });

    });

});
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

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    
    <body onload="alertaSLA()">
        

            <!-- /. NAV SIDE  -->

            <?php

                    include("setor_menu.php");

                    $link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
                    $link->set_charset('utf8');

                    if(!$link){

                        die('Connect Error (' . mysqli_connecterrno() . ')' .
                        mysqli_connect_error());

                    }else{

                        $query_get_sede = "SELECT sede, user_sede FROM sede WHERE user_sede = '$usuario'";
                        $result_id = $link->query($query_get_sede) or die(mysqli_error($link));
                        while($row_id = mysqli_fetch_assoc($result_id)){
                            $sede = $row_id['sede'];
                        }

                        //echo "<script>alert('$sede')</script>";

                        


                        $query_abertos = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 1";
                        $query_em_execucao = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 2";
                        $query_finalizados = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 3";
                        $query_atendimento = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 4";
                        $query_cotacao = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 5";
                        $query_OSs = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status != 3";

                        $query_com_pos_venda = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 3 and Os.pos_venda is not null";

                        $query_sem_pos_venda = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo WHERE Os.status = 3 and Os.pos_venda IS null";

                        $query_pendencias = "SELECT * FROM ordem_servico AS Os INNER JOIN escola AS E ON Os.fk_id_nome_escola = E.id_escola INNER JOIN sub_motivo_chamado AS R ON Os.fk_id_motivo_os = R.id_sub_motivo Where 1 IN (SELECT Pe.status_pendencia FROM execucao_diaria AS Pe Where Pe.fk_id_ordem_servico = Os.id_os and Os.status = 3)";

                        $pendencias_cg = mysqli_num_rows($link->query($query_pendencias." and fk_id_sede = '1'"));
                        $pendencias_jp = mysqli_num_rows($link->query($query_pendencias." and fk_id_sede = '2'"));
                        $pendencias_ss = mysqli_num_rows($link->query($query_pendencias." and fk_id_sede = '3'"));

                                                
                        if($sede != '0'){ // WHERE Os.tipo_chamado = 0 and Os.status < 3

                            $query_OSs.= " and fk_id_sede = '$sede'";
                            $query_abertos.=" and fk_id_sede = '$sede'";
                            $query_em_execucao.=" and fk_id_sede = '$sede'";
                            $query_finalizados.=" and fk_id_sede = '$sede'";
                            $query_sem_pos_venda.=" and fk_id_sede = '$sede'";
                            $query_com_pos_venda.=" and fk_id_sede = '$sede'";
                            $query_pendencias.=" and fk_id_sede = '$sede'";
                            $query_atendimento.=" and fk_id_sede = '$sede'";
                            $query_cotacao.=" and fk_id_sede = '$sede'";
                        }


                        $abertos = mysqli_num_rows($link->query($query_abertos));
                        $execucao = mysqli_num_rows($link->query($query_em_execucao));
                        $finalizados = mysqli_num_rows($link->query($query_finalizados));
                        $sem_pos_venda = mysqli_num_rows($link->query($query_sem_pos_venda));
                        $com_pos_venda = mysqli_num_rows($link->query($query_com_pos_venda));
                        $pendencias = mysqli_num_rows($link->query($query_pendencias));
                        $em_atendimento = mysqli_num_rows($link->query($query_atendimento));
                        $cotacao = mysqli_num_rows($link->query($query_cotacao));



                        //echo "<script>alert('$pendencias')</script>";

                        $sla_atendimento = 0;
                        $sla_conclusao_em = 0;
                        $sla_conclusao_ex = 0;
                
                        $result_OSs = $link->query($query_OSs);

                        if(mysqli_num_rows($result_OSs) > 0){
                            while($row_os = mysqli_fetch_object($result_OSs)){ 

                                $id = $row_os->id_os;
                                $status = $row_os->status; // 0=todos 1=aberto 2=em_atendimento 3=finalizado
                                $dt_abertura = $row_os->dt_abertura;
                                $tipo = $row_os->tipo_chamado;

                                $data = explode(' ', $dt_abertura)[0];

                                $ano = explode('-', $data)[0];
                                $mes = explode('-', $data)[1];
                                $dia = explode('-', $data)[2];

                                $temp = $dia.'/'.$mes.'/'.$ano;

                            

                                $hoje = date("d/m/Y");

                                


                                if ($tipo === '0' && $status === '1'){ // ###################### esquema novo

                                    
                                    $vencimento_sla_a = DiasUteis($temp,$hoje);


                                    if ($vencimento_sla_a > 1) {
                                        $sla_atendimento++;
                                    }

                                }else if ($status !== '3'){

                                    $vencimento_sla_c = DiasUteis($temp,$hoje);

                                    if ($tipo === '0' && $vencimento_sla_c >= 13){

                                        $sla_conclusao_em++;

                                    }else if($tipo === '1' && $vencimento_sla_c >= 27){

                                       $sla_conclusao_ex++;

                                    }


                                }
                            }  
                             
                        }
                        
                    }

                ?>
          
            <div id="page-wrapper">
                <div class="header"> 
                    <h1 class="page-header">
                        Painel
                    </h1> 
                    <ol class="breadcrumb">
                        <li>Departamento de Tecnologia da Informação - Informações sobre os chamados.</li>
                    </ol>                                   
                </div>



                <div id="page-inner">   

                        
                    <div  class="row" onclick="alertaSLA()">
                        <a id='alerta' class="intro-banner-vdo-play-btn pinkBg" target="_blank" style="display: none">
                            <div class="card-image">  
                                <div class="tooltip">
                                    <i style="color: white" class="material-icons dp8" aria-hidden="true">!</i>
                                    <span class="tooltiptextleft">Atenção, Chamados SLA para vencer ou vencidos, tratar com urgência!</span>
                                </div> <!--  -->
                            </div>
                            <span class="ripple pinkBg"></span>
                            <span class="ripple pinkBg"></span>
                            <span class="ripple pinkBg"></span>

                        </a>

                        
                    </div>

                    <!-- /. ROW  -->
                    <div class="fixed-action-btn horizontal click-to-toggle">
                    
                        <div class="tooltip">
                            <a href="setor_abrir_chamado.php" class="btn-floating btn-large red">
                                <!--<i class="fa fa-ticket"></i>-->
                                <i class="large material-icons">library_add</i>
                            </a>
                            
                            <span class="tooltiptextleft">Abrir Chamado</span>
                        </div>
                    
                    </div>




                    <div class="alert-message"> </div>

                <div class="card">

                    <div class="card-action">
                            <b><i class="material-icons left">dashboard</i>DASHBOARDS:</b>
                            <a href="javascript:;" onmousedown="toggleDiv('div_dashboards');"><i class='material-icons dp48'>aspect_ratio</i></a>
                    </div>

                    <div class="divider"></div>

                    <div class="row" id="div_dashboards">

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=1')">

                            <div class="card horizontal cardIcon waves-effect waves-dark">

                                <div class="card-image red">
                                    <i class="material-icons dp8">query_builder</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $abertos; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Abertos</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=2')">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image orange">
                                    <i class="material-icons dp8">done</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $em_atendimento; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Em Levantamento</strong>
                                    </div>
                                </div>
                            </div>       
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=8')">

                            <input type="hidden" name="pend" id="pend" value="<?php echo $pendencias; ?>">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image black">
                                    <i class="material-icons dp8">vpn_key</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $cotacao; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Em Cotação</strong>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=9')">

                            <input type="hidden" name="pend" id="pend" value="<?php echo $pendencias; ?>">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image pink">
                                    <i class="material-icons dp8">thumb_up</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $execucao; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Em Execução</strong>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=3')">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image green">
                                    <i class="material-icons dp8">done_all</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $finalizados; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Finalizados</strong>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=17')">

                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image grey">
                                    <i class="material-icons dp8">contact_phone</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $sem_pos_venda; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Sem Pós-venda</strong>
                                    </div>
                                </div>
                            </div>    
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=6')">

                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image brown">
                                    <i class="material-icons dp8">grade</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $com_pos_venda; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> Pós-venda OK</strong>
                                    </div>
                                </div>
                            </div>    
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=4')">

                            <input type="hidden" name="sla_a" id="sla_a" value="<?php echo $sla_atendimento; ?>">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image blue">
                                    <i class="material-icons dp8">report_problem</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $sla_atendimento; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> SLA Atendimento</strong>
                                    </div>
                                </div>
                            </div>    
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=5')">

                            <input type="hidden" name="sla_c_em" id="sla_c_em" value="<?php echo $sla_conclusao_em; ?>">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image light-blue">
                                    <i class="material-icons dp8">snooze</i>

                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <Table>
                                            <tr> 
                                                <td><h3><?php echo $sla_conclusao_em; ?></h3></td><?php 
                                                if($sla_conclusao_em > 0){ ?>
                                                    <td style="color: red"><b>RESOLVER URGENTE</b></td>
                                                 <?php } ?>
                                                
                                            </tr>
                                        </table>
                                         
                                        
                                    </div>
                                    <div class="card-action">
                                        <strong> SLA Conclusão (Emergencial)</strong>

                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=18')">

                            <input type="hidden" name="sla_c_ex" id="sla_c_ex" value="<?php echo $sla_conclusao_ex; ?>">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image deep-purple">
                                    <i class="material-icons dp8">av_timer</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $sla_conclusao_ex; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> SLA Conclusão (Programado)</strong>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3" onclick="window.open('setor_meus_chamados.php?filtro=7')">

                            <input type="hidden" name="pend" id="pend" value="<?php echo $pendencias; ?>">
                        
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image yellow">
                                    <i class="material-icons dp8">add_alert</i>
                                </div>
                                <div class="card-stacked">
                                    <div class="card-content">
                                        <h3><?php echo $pendencias; ?></h3> 
                                    </div>
                                    <div class="card-action">
                                        <strong> OS com Pendência</strong>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        

                    </div>
                </div>

                <?php

                if ($sede === '0') {

                    $tot_cg = mysqli_num_rows($link->query("SELECT fk_id_sede FROM ordem_servico WHERE fk_id_sede = '1' AND status != 3"));
                    $tot_jp = mysqli_num_rows($link->query("SELECT fk_id_sede FROM ordem_servico WHERE fk_id_sede = '2' AND status != 3")); 
                    $tot_ss = mysqli_num_rows($link->query("SELECT fk_id_sede FROM ordem_servico WHERE fk_id_sede = '3' AND status != 3")); 

                    $tot_sedes = $tot_cg + $tot_jp + $tot_ss;

                    $percent_cg = number_format($tot_cg / ($tot_sedes/100), 2, '.', '');
                    $percent_jp = number_format($tot_jp / ($tot_sedes/100), 2, '.', '');
                    $percent_ss = number_format($tot_ss / ($tot_sedes/100), 2, '.', '');

                    $pendencias_cg_p = number_format($pendencias_cg / ($pendencias/100), 2, '.', '');
                    $pendencias_jp_p = number_format($pendencias_jp / ($pendencias/100), 2, '.', '');
                    $pendencias_ss_p = number_format($pendencias_ss / ($pendencias/100), 2, '.', '');

                    $cg_array = "";
                    $jp_array = "";
                    $ss_array = "";

                    for ($i=1; $i < 13; $i++) { 
                        
                        $qtde_mes_cg = mysqli_num_rows($link->query("SELECT id_os FROM `ordem_servico` WHERE EXTRACT(YEAR FROM dt_conclusao) = 2018 AND EXTRACT(MONTH FROM dt_conclusao) = '$i' AND fk_id_sede = 1"));
                        $qtde_mes_jp = mysqli_num_rows($link->query("SELECT id_os FROM `ordem_servico` WHERE EXTRACT(YEAR FROM dt_conclusao) = 2018 AND EXTRACT(MONTH FROM dt_conclusao) = '$i' AND fk_id_sede = 2"));
                        $qtde_mes_ss = mysqli_num_rows($link->query("SELECT id_os FROM `ordem_servico` WHERE EXTRACT(YEAR FROM dt_conclusao) = 2018 AND EXTRACT(MONTH FROM dt_conclusao) = '$i' AND fk_id_sede = 3"));

                        $cg_array .= $qtde_mes_cg.'-';
                        $jp_array .= $qtde_mes_jp.'-';
                        $ss_array .= $qtde_mes_ss.'-';
                    }

                    $cg_array_19 = "";
                    $jp_array_19 = "";
                    $ss_array_19 = "";

                    for ($i=1; $i < 13; $i++) { 
                        
                        $qtde_mes_cg_19 = mysqli_num_rows($link->query("SELECT id_os FROM `ordem_servico` WHERE EXTRACT(YEAR FROM dt_conclusao) = 2019 AND EXTRACT(MONTH FROM dt_conclusao) = '$i' AND fk_id_sede = 1"));
                        $qtde_mes_jp_19 = mysqli_num_rows($link->query("SELECT id_os FROM `ordem_servico` WHERE EXTRACT(YEAR FROM dt_conclusao) = 2019 AND EXTRACT(MONTH FROM dt_conclusao) = '$i' AND fk_id_sede = 2"));
                        $qtde_mes_ss_19 = mysqli_num_rows($link->query("SELECT id_os FROM `ordem_servico` WHERE EXTRACT(YEAR FROM dt_conclusao) = 2019 AND EXTRACT(MONTH FROM dt_conclusao) = '$i' AND fk_id_sede = 3"));

                        $cg_array_19 .= $qtde_mes_cg_19.'-';
                        $jp_array_19 .= $qtde_mes_jp_19.'-';
                        $ss_array_19 .= $qtde_mes_ss_19.'-';
                    }
                    //echo "<script>alert('$ss_array')</script>"


                ?>

                <div>
                    <input type="hidden" id="array_cg" value="<?php echo $cg_array; ?>">
                    <input type="hidden" id="array_jp" value="<?php echo $jp_array; ?>">
                    <input type="hidden" id="array_ss" value="<?php echo $ss_array; ?>">
                </div>

                <div>
                    <input type="hidden" id="array_cg_19" value="<?php echo $cg_array_19; ?>">
                    <input type="hidden" id="array_jp_19" value="<?php echo $jp_array_19; ?>">
                    <input type="hidden" id="array_ss_19" value="<?php echo $ss_array_19; ?>">
                </div>

                <div class="card"> <!-- ############################################################## Não finalizadas ################################################### -->
                    <div class="card-action">
                        <b><i class="material-icons left">warning</i>Chamados Não Finalizados:</b>
                        <a href="javascript:;" onmousedown="toggleDiv('div_nf');"><i class='material-icons dp48'>aspect_ratio</i></a>
                    </div>
                    <div class="divider"></div>                    
                    <div class="row" id="div_nf">

                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>Todas as Sedes</h4>
                                <div class="easypiechart" id="easypiechart-orange" data-percent="100" ><span class="percent">100%</span>
                                </div> 
                                <div class='tooltip'>
                                    <a href='setor_meus_chamados.php?filtro=10' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $tot_sedes; ?> Chamados</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>Campina Grande</h4>
                                <div class="easypiechart" id="easypiechart-blue" data-percent="<?php echo $percent_cg; ?>" ><span class="percent"><?php echo $percent_cg; ?>%</span>
                                </div> 
                                <div class='tooltip'>
                                    <a href='setor_meus_chamados.php?filtro=11' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $tot_cg; ?> Chamados</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>João Pessoa</h4>
                                <div class="easypiechart" id="easypiechart-red" data-percent="<?php echo $percent_jp; ?>" ><span class="percent"><?php echo $percent_jp; ?>%</span>
                                </div>
                                <div class='tooltip'>
                                    <a href='setor_meus_chamados.php?filtro=12' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $tot_jp; ?> Chamados</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>Sousa</h4>
                                <div class="easypiechart" id="easypiechart-teal" data-percent="<?php echo $percent_ss; ?>" ><span class="percent"><?php echo $percent_ss; ?>%</span>
                                </div>
                                <div class='tooltip'>
                                    <a href='setor_meus_chamados.php?filtro=13' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $tot_ss; ?> Chamados</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>
                        </div>
                    </div><!--/.row-->
                </div>

                <div class="card"> <!-- ############################################################## Com pendência ################################################### -->
                    <div class="card-action">
                        <b><i class="material-icons left">warning</i>Chamados Com Pendências:</b>
                        <a href="javascript:;" onmousedown="toggleDiv('div_cp');"><i class='material-icons dp48'>aspect_ratio</i></a>
                    </div>
                    <div class="divider"></div>                    
                    <div class="row" id="div_cp">

                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>Todas as Sedes</h4>
                                <div class="easypiechart" id="easypiechart-black" data-percent="100" ><span class="percent">100%</span>
                                </div> 
                                <div class='tooltip'>
                                    <a href='setor_meus_chamados.php?filtro=7' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $pendencias; ?> Pendências</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>Campina Grande</h4>
                                <div class="easypiechart" id="easypiechart-brown" data-percent="<?php echo $pendencias_cg_p; ?>" ><span class="percent"><?php echo $pendencias_cg_p; ?>%</span>
                                </div> 
                                <div class='tooltip'>
                                    <a href='setor_meus_chamados.php?filtro=14' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $pendencias_cg; ?> Pendências</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>João Pessoa</h4>
                                <div class="easypiechart" id="easypiechart-purple" data-percent="<?php echo $pendencias_jp_p; ?>" ><span class="percent"><?php echo $pendencias_jp_p; ?>%</span>
                                </div>
                                <div class='tooltip'>
                                    <a href='setor_meus_chamados.php?filtro=15' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $pendencias_jp; ?> Pendências</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3"> 
                            <div class="card-panel text-center">
                                <h4>Sousa</h4>
                                <div class="easypiechart" id="easypiechart-yellow" data-percent="<?php echo $pendencias_ss_p; ?>" ><span class="percent"><?php echo $pendencias_ss_p; ?>%</span>
                                </div>
                                <div class='tooltip'>
                                    <a href='setor_meus_chamados.php?filtro=16' target='_blank'> <!-- <i class='material-icons dp48'>visibility</i> --> <?php echo $pendencias_ss; ?> Pendências</a><span class='tooltiptextbottom'>Detalhes</span>
                                </div>
                            </div>
                        </div>
                    </div><!--/.row-->
                </div>

                <!-- ##################################################################################################### Grafico Linha 2018 ################################################################### -->

                <script type="text/javascript" src="https://www.google.com/jsapi"></script>

                <script type="text/javascript">
                    google.load("visualization", "1", {packages:["corechart"]});
                    google.setOnLoadCallback(drawChart);

                    function drawChart() {

                        var cg_text = document.getElementById('array_cg').value;
        
                        var jp_text = document.getElementById('array_jp').value;
                        //alert(jp_text);
                        var ss_text = document.getElementById('array_ss').value;

                        var cg = cg_text.split('-');
                        var jp = jp_text.split('-');
                        var ss = ss_text.split('-');

                        //montando o array com os dados
                        var data = google.visualization.arrayToDataTable([
                            ['Mes', 'Campina Grande', 'João Pessoa','Sousa'],
                            ['JAN',  parseInt(cg[0]), parseInt(jp[0]), parseInt(ss[0])],
                            ['FEV',  parseInt(cg[1]), parseInt(jp[1]), parseInt(ss[1])],
                            ['MAR',  parseInt(cg[2]), parseInt(jp[2]), parseInt(ss[2])],
                            ['ABR',  parseInt(cg[3]), parseInt(jp[3]), parseInt(ss[3])],
                            ['MAI',  parseInt(cg[4]), parseInt(jp[4]), parseInt(ss[4])],
                            ['JUN',  parseInt(cg[5]), parseInt(jp[5]), parseInt(ss[5])],
                            ['JUL',  parseInt(cg[6]), parseInt(jp[6]), parseInt(ss[6])],
                            ['AGO',  parseInt(cg[7]), parseInt(jp[7]), parseInt(ss[7])],
                            ['SET',  parseInt(cg[8]), parseInt(jp[8]), parseInt(ss[8])],
                            ['OUT',  parseInt(cg[9]), parseInt(jp[9]), parseInt(ss[9])],
                            ['NOV',  parseInt(cg[10]), parseInt(jp[10]), parseInt(ss[10])],
                            ['DEZ',  parseInt(cg[11]), parseInt(jp[11]), parseInt(ss[11])]
                        ]);
         
                        //opções para o gráfico linhas
                        var options1 = {
                            title: 'Chamados Finalizados',
                            hAxis: {title: 'Meses',  titleTextStyle: {color: 'red'}}//legenda na horizontal
                        };
                        //instanciando e desenhando o gráfico linhas
                        var linhas = new google.visualization.LineChart(document.getElementById('linhas'));
                        linhas.draw(data, options1);
        
                    }

                <?php  

                $query_total_2018 = "SELECT COUNT(id_os) total FROM  `ordem_servico` WHERE STATUS = 3 AND dt_conclusao BETWEEN '2018-01-01' AND '2018-12-31'";
                $result_total_2018 = mysqli_fetch_assoc($link->query($query_total_2018))['total'];

                ?>

                </script>

                 <div class="card"> <!-- ############################################################## finalizados por mês 2018 ################################################### -->
                    <div class="table-responsive">
                    <div class="card-action">
                        <b><i class="material-icons left">warning</i>COMPARATIVO 2018 (Total: <?php echo $result_total_2018; ?> OS's Finalizadas)</b>
                        <a href="javascript:;" onmousedown="toggleDiv('div_cf');"><i class='material-icons dp48'>aspect_ratio</i></a>
                    </div>
                    <div class="divider"></div>                    
                    <div class="row" id="div_cf">
                        <div class="col-xs-12 col-sm-6 col-md-12"> 
                            <div class="card-panel text-center">
                                <center><div id="linhas" style="width: 900px; height: 500px;"></div></center>
                            </div>
                        </div>
                        
                    </div><!--/.row-->
                </div>

        <!-- ################################################################################################################################################################################################################## -->

        <!-- ##################################################################################################### Grafico Linha 2019 ################################################################### -->

            
                <script type="text/javascript">
                    google.load("visualization", "1", {packages:["corechart"]});
                    google.setOnLoadCallback(drawChart2);

                    function drawChart2() {

                        var cg_text_19 = document.getElementById('array_cg_19').value;
        
                        var jp_text_19 = document.getElementById('array_jp_19').value;
                        //alert(jp_text);
                        var ss_text_19 = document.getElementById('array_ss_19').value;

                        var cg_19 = cg_text_19.split('-');
                        var jp_19 = jp_text_19.split('-');
                        var ss_19 = ss_text_19.split('-');

                        //montando o array com os dados
                        var data = google.visualization.arrayToDataTable([
                            ['Mes', 'Campina Grande', 'João Pessoa','Sousa'],
                            ['JAN',  parseInt(cg_19[0]), parseInt(jp_19[0]), parseInt(ss_19[0])],
                            ['FEV',  parseInt(cg_19[1]), parseInt(jp_19[1]), parseInt(ss_19[1])],
                            ['MAR',  parseInt(cg_19[2]), parseInt(jp_19[2]), parseInt(ss_19[2])],
                            ['ABR',  parseInt(cg_19[3]), parseInt(jp_19[3]), parseInt(ss_19[3])],
                            ['MAI',  parseInt(cg_19[4]), parseInt(jp_19[4]), parseInt(ss_19[4])],
                            ['JUN',  parseInt(cg_19[5]), parseInt(jp_19[5]), parseInt(ss_19[5])],
                            ['JUL',  parseInt(cg_19[6]), parseInt(jp_19[6]), parseInt(ss_19[6])],
                            ['AGO',  parseInt(cg_19[7]), parseInt(jp_19[7]), parseInt(ss_19[7])],
                            ['SET',  parseInt(cg_19[8]), parseInt(jp_19[8]), parseInt(ss_19[8])],
                            ['OUT',  parseInt(cg_19[9]), parseInt(jp_19[9]), parseInt(ss_19[9])],
                            ['NOV',  parseInt(cg_19[10]), parseInt(jp_19[10]), parseInt(ss_19[10])],
                            ['DEZ',  parseInt(cg_19[11]), parseInt(jp_19[11]), parseInt(ss_19[11])]
                        ]);
         
                        //opções para o gráfico linhas
                        var options1 = {
                            title: 'Chamados Finalizados',
                            hAxis: {title: 'Meses',  titleTextStyle: {color: 'red'}}//legenda na horizontal
                        };
                        //instanciando e desenhando o gráfico linhas
                        var linhas = new google.visualization.LineChart(document.getElementById('linhas_19'));
                        linhas.draw(data, options1);
        
                    }

                <?php  

                $query_total_2019 = "SELECT COUNT(id_os) total FROM  `ordem_servico` WHERE STATUS = 3 AND dt_conclusao BETWEEN '2019-01-01' AND '2019-12-31'";
                $result_total_2019 = mysqli_fetch_assoc($link->query($query_total_2019))['total'];

                ?>
                    
                </script>

                <div class="card"> <!-- ############################################################## finalizados por mês 2019 ###################################################  -->
                    <div class="table-responsive">
                    <div class="card-action">
                        <b><i class="material-icons left">warning</i>COMPARATIVO 2019 (Total: <?php echo $result_total_2019; ?> OS's Finalizadas)</b>
                        <a href="javascript:;" onmousedown="toggleDiv('div_cf_19');"><i class='material-icons dp48'>aspect_ratio</i></a>
                    </div>
                    <div class="divider"></div>                    
                    <div class="row" id="div_cf_19">
                        <div class="col-xs-12 col-sm-6 col-md-12"> 
                            <div class="card-panel text-center">
                                <center><div id="linhas_19" style="width: 900px; height: 500px;"></div></center>
                            </div>
                        </div>
                        
                    </div><!--/.row-->
                </div>

        <!-- ################################################################################################################################################################################################################## -->

                <?php } ?>
                    <!-- /. ROW  --> 
                    </div>
                </div>
                <!-- /. PAGE INNER  -->
            </div>
            <!-- /. PAGE WRAPPER  -->
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