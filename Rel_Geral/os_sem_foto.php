<?php

function array_to_csv_download($array, $filename, $delimiter=";") {

    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
} 

$filename = 'OS_sem_foto.csv';
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="'.$filename.'";');


$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

if (!$link){
    die('Connect Error (' . mysqli_connecterrno() . ')' .mysqli_connect_error());
}else{ 

	$query = "SELECT * from ordem_servico o, escola e, sub_motivo_chamado s WHERE o.fk_id_nome_escola = e.id_escola AND o.fk_id_motivo_os = s.id_sub_motivo AND o.status = 3 ORDER BY o.fk_id_sede, e.nome_escola, o.dt_abertura";
    $result = mysqli_query($link, $query);

    array_to_csv_download(array(['DATA', 'PROTOCOLO', 'ESCOLA', 'SEDE', 'MOTIVO', 'RELATOS']),$filename);

    while ($row = mysqli_fetch_object($result)){

        $id_os = $row->id_os;
        $protocolo = $row->protocolo;
        $caminho = '../galeria/'.$protocolo.'/';
                                                                       
        if (!file_exists($caminho)) {

            $relatos = '';

            $query_execs = "SELECT * FROM execucao_diaria WHERE fk_id_ordem_servico = '$id_os'";
            $result_execs = mysqli_query($link, $query_execs);

            if (mysqli_num_rows($result_execs) > 0){
                
                while ($row_execs = mysqli_fetch_object($result_execs)) {

                    $local = utf8_decode($row_execs->local);
                    $servico = utf8_decode($row_execs->servico);
                    $observacao = utf8_decode($row_execs->observacao);
                    $pendencias = utf8_decode($row_execs->pendencias);

                    $relatos.= $local.' [ ] '.$servico.' [ ] '.$observacao.' [ ] '.$pendencias.' [ ][ ] ';

                }
            }

            $escola = utf8_decode($row->nome_escola);
            $data = $row->dt_abertura;
            $sede = $row->fk_id_sede;
            $motivo = utf8_decode($row->sub_motivo);
              
            array_to_csv_download(array([$data, $protocolo, $escola, $sede, $motivo, $relatos]),$filename);                           
                                                
        }
    }
}

?>