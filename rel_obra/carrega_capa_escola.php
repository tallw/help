<?php

// ################################################################################## CONEXAO BANCO

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

// ################################################################################## DADOS DE ENTRADA


$id_escola = $_POST['id_escola'];

$capa =$_POST['capa'];


// ################################################################################## ATRIBUTOS

// ############################################## Dados Escola

$query_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'"; 
$result_escola = $link->query($query_escola);
$row_escola = mysqli_fetch_object($result_escola);
$nome_escola = $row_escola->nome_escola;
$endereco = $row_escola->endereco;
$cidade = $row_escola->cidade;

$DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
$DT_atual = $DT_atual->format('Y-m-d');

$ano = explode('-', $DT_atual)[0];
$mes = explode('-', $DT_atual)[1];
$dia = explode('-', $DT_atual)[2];

$meses = array(' de Janeiro de ',' de Fevereiro de ',' de Março de ',' de Abril de ',' de Maio de ',' de Junho de ',' de Julho de ',' de Agosto de ',' de Setembro de ',' de Outubro de ',' de Novembro de ',' de Dezembro de ');


$divider = '<p class="whitespace"> </p>';

  /* Carrega a classe DOMPdf */
require_once("dompdf/dompdf_config.inc.php");

/* Cria a instância */
$dompdf = new DOMPDF();

// ################################################################################## CRIANDO HTML

// ########################### CAPA


$html_form = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><title>Capa Escola</title><link rel="stylesheet" href="style/custom-os.css"></head><style type="text/css">
   #materia {
    font-size:92pt;
    color:#d80;
   }
   #rodape {
    font-size:12pt;
    font-weight:bold;
    text-align:center;
    color:#999;
   }
   .centrodetudo {
    position:absolute;
    top:0px;
    left:0px;
    height:100%;
    width:100%;
    z-index:1;
   }
   .fundodetudo {
    position:absolute;
    top:0px;
    left:0px;
    height:100%;
    width:100%;
    z-index:2;
   }
   p {
    font-size:14pt;
    font-weight:bold;
    color:#222;
   }
   table{
  width: 100%;
  margin-bottom : .5em;
  table-layout: fixed;
  text-align: center;
}
</style><body>
<center><img src="logo/topo.png" width="500" height="80"></center><br><br><br>
<center><h4>'.$nome_escola.'</h4></center><br><br><br>
<center><img src="'.$capa.'" width="500" height="400"></center><br><br><br>
<center><h4>Ordens de Serviço realizadas pelo departamento de TI da ECOS entre os anos 2017 e 2019</h4></center><br>
<center><h6>ENDEREÇO: '.$endereco.', '.$cidade.' - PB</h6></center><br><center><h6>João Pessoa, '.$dia.$meses[intval($mes-1)].$ano.'</h6></center><div style="page-break-after: always"></div>';

/* Carrega seu HTML */
$dompdf->load_html($html_form);

/* Renderiza */   
$dompdf->render();

$canvas = $dompdf->get_canvas(); 
$font = Font_Metrics::get_font("helvetica", "bold"); 
//$canvas->page_text(510, 18, "Pág. {PAGE_NUM}/{PAGE_COUNT}", $font, 6, array(0,0,0)); //header
$canvas->page_text(270, 792, "Copyright © 2015 - Empresa XPTO", $font, 6, array(0,0,0)); //footer

/* Exibe */
$dompdf->stream(
    "saida.pdf", /* Nome do arquivo de saída */
    array(
        "Attachment" => false /* Para download, altere para true */
    )
);

// #################################################################################################################################### FIM


?>