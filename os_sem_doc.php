<?php



$protocolos = array();
$path = "documents/";

$diretorio = dir($path);


while($arquivo = $diretorio -> read()){

	if ($arquivo !== '.' && $arquivo !== '..' && $arquivo !== 'index.php') {

		$protoc = explode('.', $arquivo)[0];

		array_push($protocolos, $protoc);

	}

}

function array_to_csv_download($array, $filename, $delimiter=";") {

    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
} 

$filename = 'OS_sem_DOC.csv';
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="'.$filename.'";');

$diretorio -> close();

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

if (!$link){
    die('Connect Error (' . mysqli_connecterrno() . ')' .mysqli_connect_error());
}else{ 

	array_to_csv_download(array(['DATA', 'PROTOCOLO', 'ESCOLA', 'SEDE', 'MOTIVO']),$filename);

	$query = "SELECT * from ordem_servico o, escola e, sub_motivo_chamado s WHERE o.status = 3 AND o.fk_id_nome_escola = e.id_escola AND o.fk_id_motivo_os = s.id_sub_motivo ORDER BY o.fk_id_sede, e.nome_escola, o.dt_abertura";
	$result = mysqli_query($link, $query); 

    while ($row = mysqli_fetch_object($result)){

    	$protocolo = $row->protocolo;

    	if(!in_array($protocolo, $protocolos)){

    		$escola = utf8_decode($row->nome_escola);
            $data = $row->dt_abertura;
            $sede = $row->fk_id_sede;
            $motivo = utf8_decode($row->sub_motivo);
              
            array_to_csv_download(array([$data, $protocolo, $escola, $sede, $motivo]),$filename);

		}

    }

}


?>