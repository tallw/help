<?php
/* Carrega a classe DOMPdf */
require_once("dompdf/dompdf_config.inc.php");

/* Cria a instância */
$dompdf = new DOMPDF();

/*Carrega o html*/
//$html_form = file_get_contents('dompdf/forms/os_model.html');

//$html_form = file_get_contents('dompdf/forms/execucao_model.html');

$html_form = '';

//$html_form = file_get_contents('dompdf/forms/preventiva_model.html');

/* Carrega seu HTML */
$dompdf->load_html($html_form);


/* Renderiza */		
$dompdf->render();

/* Exibe */
$dompdf->stream(
    "saida.pdf", /* Nome do arquivo de saída */
    array(
        "Attachment" => false /* Para download, altere para true */
    )
);
?>