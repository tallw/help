<?php

$id_os = $_GET['id_os'];

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

//######################### DADOS OS ###################################

$query_OS = "SELECT * FROM ordem_servico WHERE id_os = '$id_os'";
$result_OS = $link->query($query_OS);
$row_OS = mysqli_fetch_object($result_OS);

$protocolo = $row_OS->protocolo;
$status = $row_OS->status;
$sla_a = $row_OS->sla_atendimento;
$sla_c = $row_OS->sla_conclusao;
$dt_abertura = date_create( $row_OS->dt_abertura);
$data_abertura = date_format($dt_abertura, 'd/m/Y H:i:s');

$dt_hr = explode(" ", $data_abertura);
$dt = $dt_hr[0];
$hr = $dt_hr[1];

$status = $row_OS->status;
$status_txt = '';
if ($status == '1') {
    $status_txt = 'Aberto';
}else if ($status == '2') {
    $status_txt = 'Em Atendimento';
}else{
    $status_txt = 'Finalizado';
}

$tipo = $row_OS->tipo_chamado;

$emergencial = 'X';
$programado = ' ';

if ($tipo === 1) {
	$emergencial = ' ';
	$programado = 'X';
}

$id_motivo = $row_OS->fk_id_motivo_os;
$query_motivo = "SELECT * FROM sub_motivo_chamado where id_sub_motivo = '$id_motivo'";
$result_motivo = $link->query($query_motivo);
$row_motivo = mysqli_fetch_object($result_motivo);
$motivo = $row_motivo->sub_motivo;

//###########################################################################


//######################### DADOS ESCOLA ####################################

$id_escola = $row_OS->fk_id_nome_escola;

$query_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'";
$result_escola = $link->query($query_escola);
$row_escola = mysqli_fetch_object($result_escola);

$circuito = $row_escola->circuito_oi;
$inep_escola = $row_escola->inep;
$cidade = $row_escola->cidade;
$nome_escola = $row_escola->nome_escola;
$gestor_escola = $row_escola->responsavel;
$endereco = $row_escola->endereco;
$gre = $row_escola->gre;
$telefone = $row_escola->contato01.' - '.$row_escola->contato02;

//############################################################################

if(isset($usuario)  && !is_numeric($usuario)){

  /* Carrega a classe DOMPdf */
require_once("dompdf/dompdf_config.inc.php");

/* Cria a instância */
$dompdf = new DOMPDF();


$html_form = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>Lista de Materiais</title><link rel="stylesheet" href="style/custom-os.css"></head><body>
<table class="tg" style="undefined;table-layout: fixed; width: 795px; margin-left: 10px; margin-top: 10px;">
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
    <td class="tg-center-tiny" colspan="12">Lista de Materiais e Equipamentos para Execução</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="6">1. IDENTIFICAÇÃO DA ESCOLA</td>
    <td class="tg-center-tiny" colspan="3">PROTOCOLO:</td>
    <td class="tg-center-tiny" colspan="3">'.$protocolo.'</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="2">Escola:</td>
    <td class="tg-left-tiny" colspan="6">'.$nome_escola.'</td>
    <td class="tg-left-tiny" colspan="1">Telefone:</td>
    <td class="tg-left-tiny" colspan="3">'.$telefone.'</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="2">Endereço:</td>
    <td class="tg-left-tiny" colspan="5">'.$endereco.'</td>
    <td class="tg-left-tiny" colspan="2">Município:</td>
    <td class="tg-left-tiny" colspan="3">'.$cidade.'</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="2">Nome / Resp:</td>
    <td class="tg-left-tiny" colspan="6">'.$gestor_escola.'</td>
    <td class="tg-left-tiny" colspan="1">Cargo:</td>
    <td class="tg-left-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4">DESCRIÇÃO</td>
    <td class="tg-center-tiny" colspan="2">QTD</td>
    <td class="tg-center-tiny" colspan="4">DESCRIÇÃO</td>
    <td class="tg-center-tiny" colspan="2">QTD</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center-tiny" colspan="4">REDE ELÉTRICA</td>
    <td class="tg-center-tiny" colspan="1">LAB</td>
    <td class="tg-center-tiny" colspan="1">ADM</td>
    <td class="tg-center-tiny" colspan="4">REDE LÓGICA</td>
    <td class="tg-center-tiny" colspan="1">LAB</td>
    <td class="tg-center-tiny" colspan="1">ADM</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Fio flexível 2,5mm preto 100mt</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Caixa sobrepor sistema X Keystone RJ45</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Fio flexível 2,5mm vermelho 100mt</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Cabo de transmissão de dados CAT5-e UTP</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Fio flexível 2,5mm azul 100mt</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Conector RJ45</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Fio flexível 2,5mm verde 100mt</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Módulo Keystone RJ45 CAT5 slim</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny custom-line" colspan="4">CANALETAS PVC/ELETRODUTOS</td>
    <td class="tg-center-tiny custom-line" colspan="1">LAB</td>
    <td class="tg-center-tiny custom-line" colspan="1">ADM</td>
    <td class="tg-left-tiny" colspan="4">Patch Cord 1,5Mt</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Canaleta sistema "X" 20x10 c/ adesivo e s/ divisória</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny custom-line" colspan="4">EQUIPAMENTOS PARA RACK</td>
    <td class="tg-center-tiny custom-line" colspan="1">LAB</td>
    <td class="tg-center-tiny custom-line" colspan="1">ADM</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Canaleta sistema "X" 20x20 ventilada</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Patch Panel de 24 portas</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Canaleta sistema "X" 30x30 ventilada</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Parafuso porca gaiola</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Canaleta sistema "X" 40x16 ventilada</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Rack de parede 10 U</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Canaleta sistema "X" 50x50 ventilada</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Rack de parede 3 U</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Luva para Eletroduto 3/4</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Rack de parede 5 U</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Luva para Eletroduto 50</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Organizador de Cabo 2U p/24 - Rack</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Curva PVC ríg.Sold eletroduto - 25mm - 3/4</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Organizador de Cabo 3U p/48 - Rack</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Tubo eletroduto 3/4 soldável (25mm)</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Roteador wireless - 300mbps</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Abraçadeira em PVC p/ eletroduto 20mm (TMC) preta</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Switch de 24 portas</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Abraçadeira em PVC p/ eletroduto 25mm (TMC) preta</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Bandeja p/ rack</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Abraçadeira de nylon 300 x 4,8m Branca</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Filtro de linha p/ rack régua</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny custom-line" colspan="4">TOMADAS ELÉTRICAS</td>
    <td class="tg-center-tiny custom-line" colspan="1">LAB</td>
    <td class="tg-center-tiny custom-line" colspan="1">ADM</td>
    <td class="tg-center-tiny custom-line" colspan="4">OUTROS</td>
    <td class="tg-center-tiny custom-line" colspan="1">LAB</td>
    <td class="tg-center-tiny custom-line" colspan="1">ADM</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Caixa de PVC - TAM: (_______)</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Estabilizadores/Módulo isolador</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Placa cega - TAM: (_______)</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Filtro de Linha</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Tomada 1 seção sistema x c/ espelho 10A completa</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Teclado USB</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Tomada 1 seção p/ embutir c/ espelho 10A</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Mouse USB</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Tomada 2 seções sistema x c/ espelho 10A completa</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Dijuntor monofásico</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Tomada 2 seções p/ embutir c/ espelho 10A</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny custom-line" colspan="4">PARAFUSOS E BUCHAS</td>
    <td class="tg-center-tiny custom-line" colspan="1">LAB</td>
    <td class="tg-center-tiny custom-line" colspan="1">ADM</td>
  </tr>
  <tr>
    <td class="tg-center-tiny custom-line" colspan="4">ATERRAMENTO</td>
    <td class="tg-center-tiny custom-line" colspan="1">LAB</td>
    <td class="tg-center-tiny custom-line" colspan="1">ADM</td>
    <td class="tg-left-tiny" colspan="4">Bucha S6</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Grampo p/ aterramento (c/parafuso) conector</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Buchas S8</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Grampo p/ aterramento (GTDU) conector 5/8 - tipo u</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Parafuso Phillips 4,0 x 35mm - P/S6</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Haste de cobre p/ aterramento - TAM: (_______)</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4">Parafuso Phillips 5,0 x 45mm - P/S8</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny custom-line" colspan="4">MATERIAL GERAL</td>
    <td class="tg-center-tiny custom-line" colspan="1">LAB</td>
    <td class="tg-center-tiny custom-line" colspan="1">ADM</td>
    <td class="tg-left-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Velcro de amarração de cabos 3mt</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Fita dupla face permanente 12mm x 20mt</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">Fita isolante alta fusão</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
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
  </tr>
  <tr>
    <td class="tg-blank-ass-center-checklist" colspan="5">ASSINATURA DO GESTOR</td>
    <td class="tg-blank-ass-center-checklist" colspan="7">TÉCNICO(S) / ASSINATURA DO EXECUTOR(ES)</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="12"><span>ECOS - Espaço Cidadania e Oportunidades Sociais</span></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="12"><span>Rua João Honório de Melo, 54 - Catolé - Campina Grande - PB</span></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="12"><span>Tel: (83) 3099 - 7296 / 7036 - E-mail: ti@ecospb.com.br</span></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="6"><span>ADM = Todo o setor administrativo da escola</span></td>
    <td class="tg-center-tiny" colspan="6"><span>LABs = Todos os laboratórios da escola</span></td>
  </tr>
</table>
</body></html>';

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


}else{

    header("location: ./index.php");
    exit();
}
?>