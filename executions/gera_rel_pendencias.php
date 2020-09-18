<?php

function array_to_csv_download($array, $filename, $delimiter=";") {

    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
} 

$year = $_POST['ano'];
$month = $_POST['mes'];
$status = $_POST['status'];

$query_condition_2 = " and 0 in (SELECT status_pendencia FROM `execucao_diaria` Where fk_id_ordem_servico = o.id_os) or 1 in (SELECT status_pendencia FROM `execucao_diaria` Where fk_id_ordem_servico = o.id_os)";
$query_condition_0 = " and 0 in (SELECT status_pendencia FROM `execucao_diaria` Where fk_id_ordem_servico = o.id_os)";
$query_condition_1 = " and 1 in (SELECT status_pendencia FROM `execucao_diaria` Where fk_id_ordem_servico = o.id_os)";

$query_condition_2_exec = " and (status_pendencia = 0 or status_pendencia = 1)";
$query_condition_0_exec = " and status_pendencia = 0";
$query_condition_1_exec = " and status_pendencia = 1";

if ($status === '2') {
    $query_condition = $query_condition_2;
    $query_condition_exec = $query_condition_2_exec;
}else if ($status === '0') {
    $query_condition = $query_condition_2;
    $query_condition_exec = $query_condition_0_exec;
}else if ($status === '1') {
    $query_condition = $query_condition_1;
    $query_condition_exec = $query_condition_1_exec;
}


$filename = 'Pendencia_'.$month.'_'.$year.'.csv';
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

    array_to_csv_download(array(['','']),$filename);
    array_to_csv_download(array([utf8_decode('PENDÊNCIAS')]),$filename);
    array_to_csv_download(array(['','']),$filename);
    array_to_csv_download(array(['SEQ','PROTOCOLO',utf8_decode('DATA FINALIZAÇÃO'),'GRE','NOME ESCOLA',utf8_decode('PENDÊNCIA')]),$filename);

    $query_os = "SELECT o.id_os, o.protocolo, e.nome_escola, e.gre, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola WHERE o.status = 3 and (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month')".$query_condition." ORDER BY o.dt_conclusao";


    $result_os = $link->query($query_os);

    $num = 1;

    if(mysqli_num_rows($result_os) > 0){

        while($row_os = mysqli_fetch_object($result_os)){

            $id_os = $row_os->id_os;
            $protocol = $row_os->protocolo;
            $school = utf8_decode($row_os->nome_escola);
            $gre = $row_os->gre;
            $dt_finish = ($row_os->dt_conclusao);

            $query_execs = "SELECT * FROM execucao_diaria WHERE fk_id_ordem_servico = '$id_os'".$query_condition_exec;

            $result_execs = $link->query($query_execs);

            $pendencias = '';

            $cont = 1;



            while($row_execs = mysqli_fetch_object($result_execs)){

                $pendencia = utf8_decode($row_execs->pendencias);
                $pendencias = $pendencias.$cont.' - '.$pendencia.PHP_EOL;
                $cont++;

            }

            array_to_csv_download(array([$num,$protocol,$dt_finish,$gre,$school,$pendencias]),$filename); //,$sub_motivos[$i]
            $num++;
        }
        

    }else{

        array_to_csv_download(array(['','Nenhuma pendência encontrada.']),$filename);
    }

}
?>
