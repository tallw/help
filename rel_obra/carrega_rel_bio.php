<?php

// ################################################################################## CONEXAO BANCO

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

// ################################################################################## DADOS DE ENTRADA

$foto = $_POST['foto']; 

$id_escola = $_POST['id_escola'];


// ################################################################################## ATRIBUTOS

// ############################################## Dados Escola

$query_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'"; 
$result_escola = $link->query($query_escola);
$row_escola = mysqli_fetch_object($result_escola);
$nome_escola = $row_escola->nome_escola;

$divider = '<p class="whitespace"> </p>';

  /* Carrega a classe DOMPdf */
require_once("dompdf/dompdf_config.inc.php");

/* Cria a instância */
$dompdf = new DOMPDF();

// ################################################################################## CRIANDO HTML

// ########################### CAPA


$html_form = '
<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><title>Rel. bIO</title><link rel="stylesheet" href="style/custom-os.css"></head><style type="text/css">
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
</style><body><center><img src="logo/topo.png" width="500" height="80"></center><br><br><br>';

// ###################################################################################################### CAPA RELATÓRIO FOTOGRÁFICO

// ###################################################################################################### LISTA DE FOTOS

  $caminho = '../galeria/'.$foto;

  $nome_f1 = explode('(', $caminho)[0];
 
  $nome_foto = explode('/', $nome_f1)[5];



  if ($nome_foto !== '.' && $nome_foto !== '..') {

    
      $html_form.="<header>
      </header><table border='0'>";
      
      $html_form.='<tr><th><b><h5>BIOMETRIA DO(A) '.$nome_escola.'</h5></b></th></tr>';
       

    $tam_img = getimagesize($caminho);



    //Exibindo as informações como width e height;
    $Width = $tam_img[0];
    $Height= $tam_img[1];

    if ($Width > $Height) {
      $html_form.="<tr><td><center><img src='".$caminho."' width='400' height='335'></center></tr></td>";
    }else{
      $html_form.="<tr><td><center><img src='".$caminho."' width='400' height='335'></center></tr></td>";
    }

    $nome_foto = explode('.', $nome_foto);
    $nome_foto = $nome_foto[0];

    
    $html_form.="<tr><td><center><h5>Foto Biometria</h5></center></tr></td>"; //<div style='page-break-after: always'></div>
  
    $html_form.="</table>";
      
   
  } 


// ################################################################################################################################# IMPRIMINDO

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