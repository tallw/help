<?php

if(!isset($_SESSION)){ 

    session_start(); 

    $usuario = $_SESSION['user_name'];

    $head = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>Ordem de Serviço</title><link rel="stylesheet" href="style/custom-os.css"></head><body>';

    $body = '';

    $divider = '<p class="whitespace"> </p>';

    $footer = '</body></html>';


    if(isset($usuario)  && !is_numeric($usuario)){

      $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
      $link->set_charset('utf8');

    
      /* Carrega a classe DOMPdf */
      require_once("dompdf/dompdf_config.inc.php");

      /* Cria a instância */
      $dompdf = new DOMPDF();



//$query_itens_e = "SELECT * FROM itens_de_cotacao WHERE tipo_item = 'eletrica' order by nome_item";
$query_insumos_ti = "SELECT * FROM insumos WHERE tipo_insumo = 'computacao' order by nome_insumo";


//$result_itens_e = $link->query($query_itens_e);
$result_insumos_ti = $link->query($query_insumos_ti);

//$itens_e = array();
$insumos_ti = array();

//while ($row_e = mysqli_fetch_object($result_itens_e)) {
  //array_push($itens_e, $row_e->nome_item);
//}

while ($row_insumos_ti = mysqli_fetch_object($result_insumos_ti)) {
  array_push($insumos_ti, $row_insumos_ti->nome_insumo);
}

$lista_insumos_html_form = '<table class="tg" style="undefined;table-layout: fixed; width: 795px; margin-left: 10px; margin-top: 10px;">
<colgroup>
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
  <col style="width: 79.4px">
</colgroup>
  <tr>
    <th class="tg-logo" colspan="12"><img class="custom-logo-ecos-list" src="logo/logo_ecos.png"></th>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="12">Lista de Insumos para Execução</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4">DESCRIÇÃO</td>
    <td class="tg-center-tiny" colspan="2">DADOS</td>
    <td class="tg-center-tiny" colspan="4">DESCRIÇÃO</td>
    <td class="tg-center-tiny" colspan="2">DADOS</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center-tiny" colspan="4">ITEM</td>
    <td class="tg-center-tiny" colspan="2">REFERÊNCIA/QTDE</td>  
    <td class="tg-center-tiny" colspan="4">ITEM</td>
    <td class="tg-center-tiny" colspan="2">REFERÊNCIA/QTDE</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[0].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[1].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[2].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[3].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[4].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[5].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[6].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[7].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[8].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[9].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[10].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[11].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[12].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[13].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[14].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[15].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[16].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[17].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[18].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[19].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[20].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[21].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[22].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[23].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[24].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[25].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[26].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[27].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[28].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[29].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[30].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[31].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[32].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[33].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[34].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[35].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[36].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[37].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[38].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[39].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[40].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[41].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[42].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[43].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[44].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[45].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[46].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[47].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[48].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[49].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[50].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[51].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[52].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[53].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[54].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[55].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[56].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[57].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[58].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[59].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[60].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[61].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[62].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[63].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[64].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[65].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[66].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$insumos_ti[67].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="12">Observações:</td>
  </tr>
  <tr>
    <td class="tg-line-tiny" colspan="12"></td>
  </tr>
  <tr>
    <td class="tg-line-tiny" colspan="12"></td>
  </tr>
  <tr>
    <td class="tg-line-tiny" colspan="12"></td>
  </tr>
  <tr>
    <td class="tg-line-tiny" colspan="12"></td>
  </tr>
  <tr>
    <td class="tg-line-tiny" colspan="12"></td>
  </tr>
  <tr>
    <td class="tg-line-tiny" colspan="12"></td>
  </tr>
  <tr>
    <td class="tg-line-tiny" colspan="12"></td>
  </tr>
</table>'; 

      
      $body .= $lista_insumos_html_form;
          

      $arquivo = $head.$body.$footer;

      /* Carrega seu HTML */
      $dompdf->load_html($arquivo);

      /* Renderiza  */
      $dompdf->render();

      /* Exibe */
      $dompdf->stream(
          "Ordem_de_Serviço_".$protocolo.".pdf", /* Nome do arquivo de saída */
          array(
              "Attachment" => false /* Para download, altere para true */
          )
      );

      }else{

        echo "Nenhum documento foi escolhido para impressão";
      }
}else{

    header("location: ./index.php");
    exit();
}
?>