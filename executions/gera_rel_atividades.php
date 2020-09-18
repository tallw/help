<?php

function array_to_csv_download($array, $filename, $delimiter=";") {

    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
} 


function in_array_ecos($array, $string){

    if (count($array) === 0) {
        return -1;
    }else{

        for ($i=0; $i < count($array); $i++) { 

            if ($array[$i] === $string) {
                return $i;
            }
        }

        return -1;

    }

    
}

function grava_table($filename, $titulo, $ponto, $query, $link){

    array_to_csv_download(array(['','']),$filename);
    array_to_csv_download(array([$ponto.'.0',utf8_decode($titulo)]),$filename);
    array_to_csv_download(array(['','']),$filename);
    array_to_csv_download(array(['SEQ','ESCOLA',utf8_decode('MUNICÍPIO'),'GRE','PROTOCOLO','MEDIA']),$filename);

    $result = $link->query($query);

    if(mysqli_num_rows($result) > 0){

        $escolas = array();
        $cidades = array();
        $gres = array();
        $protocolos = array();
        $notas = array();
        //$sub_motivos = array();

        

        while($row = mysqli_fetch_object($result)){

            $school = utf8_decode($row->nome_escola);
            $protocol = $row->protocolo;
            $nota = $row->avaliacao;

            $key = in_array_ecos($escolas, $school);

            if($key > -1){
                $protocolos[$key].="-".$protocol;
                $notas[$key].="-".$nota;
            }else{
                array_push($escolas, $school);
                array_push($cidades,utf8_decode($row->cidade));
                array_push($gres,utf8_decode($row->gre));
                array_push($protocolos,$protocol);
                array_push($notas,$nota);
                //array_push($sub_motivos,utf8_decode($row->sub_motivo));
            }
            $dt_finish = ($row->dt_conclusao);

        }

        for ($i=0; $i < count($escolas); $i++) {

            $sel_cat = $notas[$i];
            $categoria = '-'; 
            if(strpos("[".$sel_cat."]", "$categoria")){
                
                $notas_escola = explode("-", $sel_cat);
                $soma = 0;
                for ($x=0; $x < count($notas_escola); $x++) { 
                    $soma+=intval($notas_escola[$x]);
                }
                $media = $soma/count($notas_escola);
            }else{
                $media = intval($sel_cat);
            }
 



            array_to_csv_download(array([$i+1,$escolas[$i],$cidades[$i],$gres[$i],$protocolos[$i],number_format($media, 1, ',', ' ')]),$filename); //,$sub_motivos[$i]
        }
        

    }else{

        array_to_csv_download(array(['','Nenhum protocolo encontrado.']),$filename);
    }

}

$year = $_POST['ano'];
$month = $_POST['mes'];

$querys = array(
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE o.tipo_chamado = 1 AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE o.tipo_chamado = 0 AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (o.fk_id_motivo_os = 4 OR o.fk_id_motivo_os = 5)  AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (o.fk_id_motivo_os = 20 OR o.fk_id_motivo_os = 22 OR o.fk_id_motivo_os = 23) AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (o.fk_id_motivo_os = 11 OR o.fk_id_motivo_os = 12 OR o.fk_id_motivo_os = 13 OR o.fk_id_motivo_os = 24 OR o.fk_id_motivo_os = 25 OR o.fk_id_motivo_os = 26) AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (o.fk_id_motivo_os = 14 OR o.fk_id_motivo_os = 15 OR o.fk_id_motivo_os = 24 OR o.fk_id_motivo_os = 25 OR o.fk_id_motivo_os = 26) AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (o.fk_id_motivo_os = 16 OR o.fk_id_motivo_os = 17 OR o.fk_id_motivo_os = 18 OR o.fk_id_motivo_os = 19) AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (o.fk_id_motivo_os = 6 OR o.fk_id_motivo_os = 7 OR o.fk_id_motivo_os = 8 OR o.fk_id_motivo_os = 9 OR o.fk_id_motivo_os = 10) AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (o.fk_id_motivo_os = 21) AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '11') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') AND o.id_os = 0 ORDER BY gre",
    "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao, o.avaliacao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (o.fk_id_motivo_os = 20 OR o.fk_id_motivo_os = 22 OR o.fk_id_motivo_os = 23 OR o.fk_id_motivo_os = 11 OR o.fk_id_motivo_os = 12 OR o.fk_id_motivo_os = 13 OR o.fk_id_motivo_os = 24 OR o.fk_id_motivo_os = 25 OR o.fk_id_motivo_os = 26 OR o.fk_id_motivo_os = 14 OR o.fk_id_motivo_os = 15 OR o.fk_id_motivo_os = 16 OR o.fk_id_motivo_os = 17 OR o.fk_id_motivo_os = 18 OR o.fk_id_motivo_os = 19 OR o.fk_id_motivo_os = 6 OR o.fk_id_motivo_os = 7 OR o.fk_id_motivo_os = 8 OR o.fk_id_motivo_os = 9 OR o.fk_id_motivo_os = 10 OR o.fk_id_motivo_os = 21) AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre");

$titulos = array(
    "VISTORIAS TÉCNICAS PROGRAMADAS EM ESCOLAS",
    "VISITAS TÉCNICAS EMERGENCIAIS EM ESCOLAS",
    "ELABORAÇÃO DE COTAÇÕES E/OU LEVANTAMENTO DE MATERIAIS PARA EXECUÇÃO NAS ESCOLAS",
    "ELABORAÇÃO DE RELATÓRIOS FOTOGRÁFICOS DAS VISTORIAS DAS ESCOLAS",
    "EXECUÇÃO DA MONTAGEM E/OU ADEQUAÇÃO(Otimização) LABORATÓRIO DE TI, SALAS, SECRETARIA E DIRETORIA",
    "EXECUÇÃO DA MONTAGEM E/OU REPARO REDE ELÉTRICA DO LABORATÓRIO DE TI, SALAS, SECRETARIA E DIRETORIA",
    "EXECUÇÃO DA MONTAGEM E/OU REPARO REDE LÓGICA(CABEAMENTO ESTRUTURADO) DO LABORATÓRIO DE TI, SALAS, SECRETARIA E DIRETORIA",
    "INSTALAÇÃO DE PONTO BIOMÉTRICO",
    "CHAMADOS DE INTERNET(REPAROS, TROCA DE MODEM, LINHAS TELEFÔNICAS)",
    "VISITA TÉCNICA PROGRAMADA MANUTENÇÃO",
    "VISITA TÉCNICA CONFIGURAÇÃO DE CÂMERAS",
    "ELABORAÇÃO DE RELATÓRIOS FOTOGRÁFICOS DAS EXECUÇÕES NAS ESCOLAS"
);


$filename = 'Atividades_Mensais_'.$month.'_'.$year.'.csv';
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="'.$filename.'";');


$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$dt = $year.'-'.$month;

if (!$link){

  die('Connect Error (' . mysqli_connecterrno() . ')' .
    mysqli_connect_error());

}else{

    array_to_csv_download(array(['',$dt]),$filename);

    for ($i=0; $i < 12; $i++) {

        grava_table($filename, $titulos[$i], $i+1, $querys[$i], $link);
    }
  
}
?>
