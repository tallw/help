<?php

function array_to_csv_download($array, $filename, $delimiter=";") {

    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
} 

$filename = 'qtde_fotos_os.csv';
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="'.$filename.'";');


$path = "../galeria/";
$diretorio = dir($path);
 

array_to_csv_download(array(['PROTOCOLO', 'QTDE FOTOS']),$filename);

while($arquivo = $diretorio -> read()){

	

	$retirar = array('css', 'js', 'PHP_Compat-1.6.0a3', '_scripts.html', 'index.php', 'package.xml', 'postfileV2.php', '.', '..');

	
	if (!in_array($arquivo, $retirar)) {

		$diretorio_2 = scandir($path.$arquivo);
   		$qtd = count($diretorio_2) - 2;
   		array_to_csv_download(array([$arquivo, $qtd]),$filename);  
	}

	

}

$diretorio -> close();



?>