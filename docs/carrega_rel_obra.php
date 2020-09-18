<?php

// ################################################################################## CONEXAO BANCO

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

// ################################################################################## DADOS DE ENTRADA 

$fotos = $_POST['fotos']; 

$id_os = $_POST['id_os'];

$capa =$_POST['capa'];

$imagem_map_cot = isset($_FILES['img_map_cot']) && !empty($_FILES['img_map_cot']) ? $_FILES['img_map_cot'] : NULL;

$imagem_cot = isset($_FILES['img_cot']) && !empty($_FILES['img_cot']) ? $_FILES['img_cot'] : NULL;

$imagem_os = isset($_FILES['img_os']) && !empty($_FILES['img_os']) ? $_FILES['img_os'] : NULL;

$img_aut =$_POST['img_aut_seg'];

//echo "<script>alert('$mapa_cot')</script>";

// ################################################################################## ATRIBUTOS

// ############################################## Dados OS

$query_os = "SELECT * FROM ordem_servico WHERE id_os = '$id_os'"; 
$result_os = $link->query($query_os);
$row_os = mysqli_fetch_object($result_os);
$id_escola = $row_os->fk_id_nome_escola;
$protocolo = $row_os->protocolo;

// ############################################## Dados Cotacao

$query_cot = "SELECT * FROM cotacoes_os WHERE fk_id_os_cotada = '$id_os'";
$result_cot = $link->query($query_cot);
$row_cot = mysqli_fetch_object($result_cot);
$id_cot = $row_cot->id_cotacao;


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


$html_form = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><title>Rel. Obra</title><link rel="stylesheet" href="style/custom-os.css"></head><style type="text/css">
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
<center><h1>INTERVENÇÕES EM ESCOLAS ESTADUAIS NA PARAÍBA</h1></center><br><br><br>
<center><img src="'.$capa.'" width="500" height="400"></center><br><br><br>
<center><h1>OBJETO: Instalação de Infraestrutura de TI na "'.$nome_escola.'"</h1></center><br>
<center><h1>ENDEREÇO: '.$endereco.', '.$cidade.' - PB</h1></center><br><br><br><br><br><br><br><br><br><center><h1>João Pessoa, '.$dia.$meses[intval($mes-1)].$ano.'</h1></center><div style="page-break-after: always"></div>';

// ###################################################################################################### SEGUNDA CAPA NOME ESCOLA

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
$html_form.='<div align="center"><font size="6"><b>"'.$nome_escola.'"</b></font></div>';
$html_form.='<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><center><h1>João Pessoa, '.$dia.$meses[intval($mes-1)].$ano.'</h1></center><div style="page-break-after: always"></div>';


// ###################################################################################################### INDICE

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header>';
$html_form.='<table border="0" style="padding: 50px;">';
$html_form.='<tr><td><br></td></tr>';
$html_form.='<tr><td><h1>ÍNDICE</h1></td></tr>';
$html_form.='<tr><td><br></td></tr>';
$html_form.='<tr><td style="text-align:left;"><h1 style="margin-bottom: 10px; padding: 10px;">1 - ORDEM SERVIÇO</h1></td></tr>
<tr><td style="text-align:left;"><h1 style="margin-bottom: 10px; padding: 10px;">2 - RELATÓRIO FOTOGRÁFICO</h1></td></tr>
<tr><td style="text-align:left;"><h1 style="margin-bottom: 10px; padding: 10px;">3 - PLANILHA DE CUSTOS (MATERIAL E MÃO DE OBRA)</h1></td></tr>
<tr><td style="text-align:left;"><h1 style="margin-bottom: 10px; padding: 10px;">4 - MAPA DE COTAÇÕES</h1></td></tr>
<tr><td style="text-align:left;"><h1 style="margin-bottom: 10px; padding: 10px;">5 - COTAÇÕES</h1></td></tr>
<tr><td style="text-align:left;"><h1 style="margin-bottom: 10px; padding: 10px;">6 - ESPECIFICAÇÃO TÉCNICA</h1></td></tr>
<tr><td style="text-align:left;"><h1 style="margin-bottom: 10px; padding: 10px;">7 - AUTORIZAÇÃO</h1></td></tr><div style="page-break-after: always"></div>';
$html_form.="</table>";

// ###################################################################################################### CAPA OS

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
$html_form.='<div align="center"><font size="6"><b>Ordem Serviço: '.$protocolo.'</b></font></div>';
$html_form.='<div style="page-break-after: always"></div>';

// ###################################################################################################### os completa

//$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header>';


$x=0;

 foreach($imagem_os['name'] as $img_os){

  //echo $imagem['tmp_name'][$x];
  $html_form.='<center><img src="'.$imagem_os['tmp_name'][$x].'" width="90%" height="90%"></center><br><br><br>';
  $html_form.='<div style="page-break-after: always"></div>';
  $x++;

                                               
}



// ###################################################################################################### CAPA RELATÓRIO FOTOGRÁFICO

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
$html_form.='<div align="center"><font size="6"><b>RELATÓRIO FOTOGRÁFICO</b></font></div>';
$html_form.='<div style="page-break-after: always"></div>';

// ###################################################################################################### LISTA DE FOTOS

$cont = 0;
 
foreach ($fotos as $key => $caminho) {

  $caminho = '../'.$caminho;

  //echo "<script>alert('$caminho')</script>";

  $nome_f1 = explode('(', $caminho)[0];
 
  $nome_foto = explode('/', $nome_f1)[3];

  if ($nome_foto !== '.' && $nome_foto !== '..') {

    if ($cont%2==0) {
      $html_form.="<header>
      <center><img src='logo/topo.png' width='500' height='80'></center>
      </header><table border='0'>";
      if ($cont==0) {
        $html_form.='<tr><th><b>RELATÓRIO FOTOGRÁFICO DO(A) '.$nome_escola.'</b></th></tr>';
        $html_form.='<tr><td><br></td></tr>';
      }
      
    }

    $tam_img = getimagesize($caminho);



    //Exibindo as informações como width e height;
    $Width = $tam_img[0];
    $Height= $tam_img[1];

    if ($Width > $Height) {
      $html_form.="<tr><td><center><img src='".$caminho."' width='450' height='350'></center></tr></td>";
    }else{
      $html_form.="<tr><td><center><img src='".$caminho."' width='350' height='450'></center></tr></td>";
    }

    if (($cont+1) < 10) {
      $html_form.="<tr><td><center><h1>Foto 0".($cont+1)." - ".$nome_foto."</h1></center></tr></td>"; //<div style='page-break-after: always'></div>
    //$html_form.=$divider;
    }else{
      $html_form.="<tr><td><center><h1>Foto ".($cont+1)." - ".$nome_foto."</h1></center></tr></td>"; //<div style='page-break-after: always'></div>
    //$html_form.=$divider;
    }
    $html_form.='<tr><td><br></td></tr>';

    

    if ($cont%2!=0) {
      $html_form.="</table>";
      $html_form.="<div style='page-break-after: always'></div>";
    }

    $cont++;
  }  
}

if ($cont%2!=0) {
  $html_form.="</table>";
  $html_form.="<div style='page-break-after: always'></div>";
}



// ###################################################################################################### CAPA PLANILHA DE CUSTOS

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
$html_form.='<div align="center"><font size="6"><b>PLANILHA DE CUSTOS</b></font></div>';
$html_form.='<div style="page-break-after: always"></div>';

// ################################################################################################################################# LISTA DE ITENS COTADOS

$query_itens = "SELECT itdc.nome_item nome, itdc.tipo_item tipo, itc.quantidade qtde, itc.referencia ref FROM itens_cotados itc, itens_de_cotacao itdc WHERE itc.fk_id_cotacao = '$id_cot' and itc.fk_id_item = itdc.id_item";
$result_itens = $link->query($query_itens);

$msg_itens = '<header>
      <center><img src="logo/topo.png" width="500" height="80"></center>
      </header><table class="table" border="1" style="padding: 50px;">';

$msg_itens.='<tr><th colspan = "8"><center><b>Listagem de itens</b></center></th></tr><tr><th colspan = "1"><center><b>Item</b></center></th><th colspan = "3"><center><b>Descrição</b></center></th><th colspan = "2"><center><b>Quantidade</b></center></th><th colspan = "2"><center><b>Referência</b></center></th></tr>'; // <th><center><b>Tipo</b></center></th>

$cont = 1;

while ($row_itens = mysqli_fetch_object($result_itens)) { 
  $msg_itens.='<tr>
                  <td colspan = "1">'.$cont.'</td>
                  <td colspan = "3">'.$row_itens->nome.'</td>
                  <td colspan = "2">'.$row_itens->qtde.'</td>
                  <td colspan = "2">'.$row_itens->ref.'</td>
                  
                </tr>'; // <td>'.$row_itens->tipo.'</td>
                $cont++;
}
$msg_itens.='</table><br>';

$html_form.=$msg_itens;
$html_form.='<div style="page-break-after: always"></div>';

// ###################################################################################################### CAPA ESPECIFICAÇÃO TÉCNICA

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
$html_form.='<div align="center"><font size="6"><b>ESPECIFICAÇÃO TÉCNICA</b></font></div>';
$html_form.='<div style="page-break-after: always"></div>';

// ###################################################################################################### img_esp_tec

//$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header>';
//$html_form.='<center><img src="'.$img_esp_tec.'" width="90%" height="90%"></center><br><br><br>';
//$html_form.='<div style="page-break-after: always"></div>';

// ###################################################################################################### INSTALAÇÃO INFRA 

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><table style="padding: 50px;">';
$html_form.='<tr><th><b>INSTALAÇÃO DE INFRAESTRUTURA DE REDE NO(A) '.$nome_escola.'</b></th></tr>';
//$html_form.='<tr><td><br></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>APRESENTAÇÃO</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">Este documento fixa determinações a serem adotadas para a instalação de infraestrutura de rede obedecendo as normas técnicas da ABNT.</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>1.0 - OBJETIVO</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">O presente memorial descritivo tem por objetivo definir os materiais a serem empregados na realização dos serviços, assim como também orientar sobre o correto uso dos mesmos.</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>2.0 - ESPECIFICAÇÕES</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">2.1 - Os serviços contratados serão executados rigorosamente de acordo com a presente Especificação Técnica e com os documentos nela referidos;</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">2.2 - Fica estabelecido que todo material e mão de obra, salvo disposto em contrário, serão fornecidos pela ECOS/PB;</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">2.3 - Os serviços serão executados em total observância às indicações constantes nos Projetos fornecidos pela ECOS/PB e referidos nestas Especificações;</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>3.0 - NORMAS TÉCNICAS</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">A execução de todos os serviços que compõem deverá obedecer às Normas da ABNT em vigor, inclusive às das Concessionárias locais.</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>4.0 – DESENVOLVIMENTO DA EXECUÇÃO DE INFRAESTRUTURA</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>4.1.1 – CABEAMENTO</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>4.1.1.1 – CABEAMENTO DE REDE</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">4.1.1.2 – Todo o cabeamento de dados será utilizado cabos CAT5E, passado em canaletas ventiladas ou eletroduto caso seja necessário passagem pela área externa</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>4.1.2 – CABEAMENTO ELÉTRICO</u></td></tr>';
$html_form.='</table><div style="page-break-after: always"></div>';
$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><table style="padding: 50px;">';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>4.1.2.1 – CABEAMENTO ELÉTRICO</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">4.1.2.1 -  Todo o cabeamento elétrico será utilizado fio flexível de bitola equivalente ao consumo estimado para aquele circuito, e será passado em canaletas ventiladas, e todas as tomadas será aterradas, ou com aterramento já existente na escola ou será feito o aterramento devido</td></tr>';


$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">4.1.2.2 – Poderá ser instalado caixa de disjuntores, ou aproveitado caixa e disjuntores já pré-existentes na escola</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>4.2 – Instalação do RACK</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>4.2.1 – DISPOSIÇÕES GERAIS</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">4.2.1.1 – O rack será instalado no local onde está o link da operadora, salvo sob algum impedimento que venha a ocorrer.</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">4.2.1.2 – Serão Utilizados Patch panel organizador de cabos e Switch de 24 portas, em um rack de 8U ou 16U dependendo do tamanho da Infraestrutura</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">4.2.1.3 – Poderá ser utilizado mais de um rack, caso a escola tenha mais de um link ativo em locais físicos distintos</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">4.2.1.4 –  A Altura padrão do Rack deverá ficar a 2 metros do chão partindo de sua base inferior</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>4.3 – Tomadas de rede</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">4.3.1 – Poderá ser utilizado tomadas de rede duplas ou simples com apenas 1 conector, de acordo com a necessidade, de marca a serem definidas no momento da compra e da disponibilidade da distribuidora</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>4.4 – Tomadas Elétricas</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">4.4.1 – As Tomadas elétricas podem ser simples ou duplas do novo padrão brasileiro seguindo a NBR 14136, dependendo da necessidade e da disponibilidade no momento da compra</td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;"><u>5.0 – VERIFICAÇÃO FINAL</u></td></tr>';
$html_form.='<tr><td style="text-align:left; margin-bottom: 10px; padding: 10px;">Será procedida cuidadosa verificação, por parte da FISCALIZAÇÃO, das perfeitas condições de funcionamento e segurança de todos os serviços executados.</td></tr>';
$html_form.='<tr><td><br></td></tr>';
//$html_form.='<tr><td><br></td></tr>';
$html_form.='<tr><td style="text-align:right; margin-bottom: 10px; padding: 10px;">João Pessoa/PB, '.$dia.$meses[intval($mes-1)].$ano.'</td></tr>';
$html_form.='</table><div style="page-break-after: always"></div>';


// ###################################################################################################### CAPA MAPA DE COTAÇÕES

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
$html_form.='<div align="center"><font size="6"><b>MAPA DE COTAÇÕES</b></font></div>';
$html_form.='<div style="page-break-after: always"></div>';

// ###################################################################################################### mapa cotacoes

//$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header>';


$x=0;

 foreach($imagem_map_cot['name'] as $img_mc){

  //echo $imagem['tmp_name'][$x];
  $html_form.='<center><img src="'.$imagem_map_cot['tmp_name'][$x].'" width="90%" height="90%"></center><br><br><br>';
  $html_form.='<div style="page-break-after: always"></div>';
  $x++;

                                               
}


// ###################################################################################################### CAPA COTAÇÕES

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
$html_form.='<div align="center"><font size="6"><b>COTAÇÕES</b></font></div>';
$html_form.='<div style="page-break-after: always"></div>';

// ###################################################################################################### img_cot

$y=0;

 foreach($imagem_cot['name'] as $img_c){

  //echo $imagem['tmp_name'][$x];
  $html_form.='<center><img src="'.$imagem_cot['tmp_name'][$y].'" width="90%" height="90%"></center><br><br><br>';
  $html_form.='<div style="page-break-after: always"></div>';
  $y++;

                                               
}

// ###################################################################################################### CAPA AUTORIZAÇÃO

$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
$html_form.='<div align="center"><font size="6"><b>AUTORIZAÇÃO</b></font></div>';
$html_form.='<div style="page-break-after: always"></div>';

// ###################################################################################################### img_cot

//$html_form.='<header><center><img src="logo/topo.png" width="500" height="80"></center></header>';
$html_form.='<center><img src="'.$img_aut.'" width="90%" height="90%"></center><br><br><br>';
$html_form.='<div style="page-break-after: always"></div>';


// ################################################################################################################################## RODAPÉ

$html_form.='<table class="fundodetudo">
   <tr>
    <td valign="bottom">
     <div id="rodape">João Pessoa, 7 de Novembro de 2018</div>
    </td>
   </tr>
  </table>
</body></html>';

// ################################################################################################################################# IMPRIMINDO

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

// #################################################################################################################################### FIM


?>