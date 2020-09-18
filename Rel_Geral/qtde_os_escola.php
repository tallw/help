<?php

function array_to_csv_download($array, $filename, $delimiter=";") {

    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
} 

$filename = 'qtde_os_escola.csv';
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="'.$filename.'";');


$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

if (!$link){
    die('Connect Error (' . mysqli_connecterrno() . ')' .mysqli_connect_error());
}else{ 

	$query = "SELECT DISTINCT e.id_escola, e.nome_escola, COUNT( o.id_os ) qtde, o.fk_id_sede FROM  `escola` e,  `ordem_servico` o WHERE o.fk_id_nome_escola = e.id_escola GROUP BY e.id_escola ORDER BY qtde";
	$result = mysqli_query($link, $query);

    array_to_csv_download(array(['ESCOLA', 'QTDE_OS', 'SEDE']),$filename);

    while ($row = mysqli_fetch_object($result)){

    	$escola = $row->nome_escola;
        $qtde_os = $row->qtde;
        $sede = $row->fk_id_sede;
    	
                                                                       
          
        array_to_csv_download(array([$escola, $qtde_os, $sede]),$filename);                                                                     
    }
}

?>