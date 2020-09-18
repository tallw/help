<?php

function array_to_csv_download($array, $filename, $delimiter=";") {

    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
} 


$filename = 'relacao_CG.csv';
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="'.$filename.'";');


$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$query = "SELECT o.dt_abertura, o.protocolo, e.nome_escola, m.sub_motivo 
FROM ordem_servico o, escola e, sub_motivo_chamado m 
WHERE o.fk_id_sede = 1 AND o.fk_id_motivo_os = m.id_sub_motivo AND o.fk_id_motivo_os = 21 AND o.fk_id_nome_escola = e.id_escola
ORDER BY e.nome_escola, o.dt_abertura";

$result = mysqli_query($link, $query);

$cont=1;

array_to_csv_download(array(['ITEM', 'DATA ABERTURA', 'PROTOCOLO', 'ESCOLA', 'MOTIVO']),$filename);



while ($row = mysqli_fetch_object($result) ) {

    $data = $row->dt_abertura;
    $protocolo = $row->protocolo;
    $escola = utf8_decode($row->nome_escola); 
    $motivo = utf8_decode($row->sub_motivo); 


    array_to_csv_download(array([$cont, $data, $protocolo, $escola, $motivo]),$filename);
    $cont++;

}

//fclose($filename);

echo "Terminou";

?>