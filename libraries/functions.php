<?php



// check if there is any open ticket
function checkExistenciaChamado($id_escola, $id_sub_motivo, $link)
{
    $query_check_chamado = "SELECT id_os, status, fk_id_motivo_os FROM ordem_servico WHERE fk_id_nome_escola = '$id_escola'";
    $result_check = $link->query($query_check_chamado);

    if(mysqli_num_rows($result_check) > 0) {
        while($row = mysqli_fetch_object($result_check)) {
            $status = $row->status;
            $fk_id_motivo_os = $row->fk_id_motivo_os;

            if(($id_sub_motivo === $fk_id_motivo_os) && ($status !== '3')){
                $chamado_aberto = 'S';
                break;
            }else{
                $chamado_aberto = 'N';
            }
        }
    }else{
        $chamado_aberto = 'N';
    }

    return $chamado_aberto;
}

// check the last protocol
function checkUltimoProtocolo($tipo, $sede, $id_escola, $link)
{
    $actual_bigger_protocol = 0;

    if ($tipo === '0'){

        $start = 2;

    }else {

        $start = 4;
    }

    
    $query_check_protocol = "SELECT * FROM ordem_servico WHERE tipo_chamado = '$tipo' AND fk_id_sede = '$sede'";
    $result_check_protocol = $link->query($query_check_protocol);

    if(mysqli_num_rows($result_check_protocol) > 0) {
        while($row = mysqli_fetch_object($result_check_protocol)) {
            $last_protocol = $row->protocolo;

            // verify the bigger normal or EX protocol

            $last_protocol = substr($last_protocol, $start);

            $last_protocol = intval($last_protocol);

            if ($last_protocol > $actual_bigger_protocol) {

                $actual_bigger_protocol = $last_protocol;
            }
        }

        $next_protocol_number = $actual_bigger_protocol + 1;

        $next_protocol_txt = strval($next_protocol_number);


        if (strlen($next_protocol_txt) < 5) {

            $qtde_zeros = 5 - strlen($next_protocol_txt);

            for ($i=0; $i < $qtde_zeros; $i++) {

                $next_protocol_txt = '0'.$next_protocol_txt;
            }
        }


        if ($sede === '1') {

            if ($tipo === '1') { // if it is "EX"

                $next_protocol = 'CGEX'.$next_protocol_txt;
            }else {

                $next_protocol = 'CG'.$next_protocol_txt;
            }

        }elseif ($sede === '2') {
            
            if ($tipo === '1') { // if it is "EX"

                $next_protocol = 'JPEX'.$next_protocol_txt;
            }else {

                $next_protocol = 'JP'.$next_protocol_txt;
            }

        }elseif ($sede === '3') {
            
            if ($tipo === '1') { // if it is "EX"

                $next_protocol = 'SSEX'.$next_protocol_txt;
            }else {

                $next_protocol = 'SS'.$next_protocol_txt;
            }

        }else{

            $next_protocol = '?';
        }
    }
      
    return $next_protocol;
}

//######################################################################## FUNCOES DE MANIPULACAO DE DATAS #########################################################################################

function somar_dias_uteis($str_data,$int_qtd_dias_somar,$feriados) {
    $ano = explode('/', $str_data)[2];
    $str_data = substr($str_data,0,10);
    if ( preg_match("@/@",$str_data) == 1 ) {
        $str_data = implode("-", array_reverse(explode("/",$str_data)));
    }
    
    $pascoa_dt = dataPascoa(date('Y'));
    $aux_p = explode("/", $pascoa_dt);
    $aux_dia_pas = $aux_p[0];
    $aux_mes_pas = $aux_p[1];
    $pascoa = "$aux_mes_pas"."-"."$aux_dia_pas"; // crio uma data somente como mes e dia
    
    $carnaval_dt = dataCarnaval(date('Y'));
    $aux_carna = explode("/", $carnaval_dt);
    $aux_dia_carna = $aux_carna[0];
    $aux_mes_carna = $aux_carna[1];
    $carnaval = "$aux_mes_carna"."-"."$aux_dia_carna"; 

    $CorpusChristi_dt = dataCorpusChristi(date('Y'));
    $aux_cc = explode("/", $CorpusChristi_dt);
    $aux_cc_dia = $aux_cc[0];
    $aux_cc_mes = $aux_cc[1];
    $Corpus_Christi = "$aux_cc_mes"."-"."$aux_cc_dia"; 

    $sexta_santa_dt = dataSextaSanta(date('Y'));
    $aux = explode("/", $sexta_santa_dt);
    $aux_dia = $aux[0];
    $aux_mes = $aux[1];
    $sexta_santa = "$aux_mes"."-"."$aux_dia"; 

    $feriados = array("01-01", $carnaval, $sexta_santa, $pascoa, $Corpus_Christi, "04-21", "05-01", "06-12" ,"07-09", "07-16", "09-07", "10-12", "11-02", "11-15", "12-24", "12-25", "12-31");

    $array_data = explode('-', $str_data);
    $count_days = 0;
    $int_qtd_dias_uteis = 0;

    while ( $int_qtd_dias_uteis < $int_qtd_dias_somar ) {

        $count_days++;
        $day = date('m-d',strtotime('+'.$count_days.'day',strtotime($str_data))); 

        if(($dias_da_semana = gmdate('w', strtotime('+'.$count_days.' day', gmmktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]))) ) != '0' && $dias_da_semana != '6' && !in_array($day,$feriados)) {
            $int_qtd_dias_uteis++;
        }
    }
    return gmdate('Y/m/d',strtotime('+'.$count_days.' day',strtotime($str_data)));
}

//################################################################################### RETORNA ARRAY COM INDEX DOS MESES DE UM INTERVALO #################################################################################


function get_meses_index($dt_inicial, $dt_final){

    $meses_index = array();
    
    $mes_ini = explode("-", $dt_inicial)[1];
    $ano_ini = explode("-", $dt_inicial)[0];

    $mes_fin = explode("-", $dt_final)[1];
    $ano_fin = explode("-", $dt_final)[0];

    $qtde_anos = ($ano_fin - $ano_ini) + 1;

    if ($qtde_anos === 1) {
        for ($i=$mes_ini; $i <= $mes_fin;$i++) { 
            array_push($meses_index, ($i-1)."#".$ano_ini);
        }
    }else{
        for ($i=$mes_ini; $i <= 12; $i++) { 
            array_push($meses_index, ($i-1)."#".$ano_ini);
        }

        for ($i=1; $i <= ($qtde_anos-1); $i++) { 
            for ($x=1; $x <= 12; $x++) {
                array_push($meses_index, ($x-1)."#".($ano_ini+$i));
                //echo "<script>alert(' Ano: $i e ($qtde_anos-1) # Mes: $x e $mes_fin')</script>";
                if ($i === ($qtde_anos-1) && $x === intval($mes_fin)){
                    break;
                }
            }
        }
    }

    return $meses_index;

}