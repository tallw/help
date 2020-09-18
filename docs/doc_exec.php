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
$email = $row_escola->email;

//############################################################################

if(isset($usuario)  && !is_numeric($usuario)){

  /* Carrega a classe DOMPdf */
require_once("dompdf/dompdf_config.inc.php");

/* Cria a instância */
$dompdf = new DOMPDF();


$html_form = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><title>Execução Diária</title><link rel="stylesheet" href="style/custom-os.css"></head><body>
<table class="tg" style="undefined;table-layout: fixed; width: 795px; margin-left: 10px; margin-top: 10px;">
<colgroup>
<col style="width: 110px">
<col style="width: 339px">
<col style="width: 105px">
<col style="width: 42px">
<col style="width: 75px">
<col style="width: 104px">
<col style="width: 103px">
<col style="width: 42px">
<col style="width: 22px">
<col style="width: 133px">
</colgroup>
  <tr>
    <th class="tg-logo" colspan="10"><img class="custom-logo-ecos" src="logo/logo_ecos.png"></th>
  </tr>
  <tr>
    <th class="tg-center" colspan="10">RELATÓRIO DE EXECUÇÃO DE SERVIÇOS</th>
  </tr>
  <tr>
    <td class="tg-center" colspan="1">DATA:</td>
    <td class="tg-center" colspan="1"></td>
    <td class="tg-center" colspan="2">HORA DE INÍCIO:</td>
    <td class="tg-center" colspan="2"></td>
    <td class="tg-center" colspan="2">PROTOCOLO:</td>
    <td class="tg-center" colspan="2">'.$protocolo.'</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10">1. IDENTIFICAÇÃO DO SOLICITANTE</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="1">Escola:</td>
    <td class="tg-left" colspan="4">'.$nome_escola.'</td>
    <td class="tg-left" colspan="1">Municipio:</td>
    <td class="tg-left" colspan="4">'.$cidade.'</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="1">Endereço:</td>
    <td class="tg-left" colspan="4">'.$endereco.'</td>
    <td class="tg-left" colspan="1">GRE:</td>
    <td class="tg-left" colspan="4">'.$gre.'ª GRE</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="1">Nome/Resp.:</td>
    <td class="tg-left" colspan="4">'.$gestor_escola.'</td>
    <td class="tg-left" colspan="1">Cargo:</td>
    <td class="tg-left" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-left" colspan="1">Telefone:</td>
    <td class="tg-left" colspan="2">'.$telefone.'</td>
    <td class="tg-left" colspan="3">RG:</td>
    <td class="tg-left" colspan="4">CPF:</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="1">Email:</td>
    <td class="tg-left" colspan="5">'.$email.'</td>
    <td class="tg-left" colspan="1">INEP:</td>
    <td class="tg-center" colspan="1">'.$inep_escola.'</td>
    <td class="tg-left" colspan="1">Circuito:</td>
    <td class="tg-center" colspan="1">'.$circuito.'</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic" colspan="10">LOCAL DE EXECUÇÃO DO SERVIÇO:</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic" colspan="10">SERVIÇO REALIZADO:</td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic" colspan="10">OBSERVAÇÕES:</td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic" colspan="10">PENDÊNCIAS:</td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic" colspan="10">TÉCNICOS:</td>
  </tr>
  <tr>
    <td class="tg-line" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-blank-ass-center" colspan="4">ASSINATURA DO GESTOR</td>
    <td class="tg-blank-ass-center" colspan="6">CARIMBO ESCOLAR</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"><span>ECOS - Espaço Cidadania e Oportunidades Sociais</span></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"><span>Rua João Honório de Melo, 54 - Catolé - Campina Grande - PB</span></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"><span>Tel: (83) 3099 - 7296 / 7036 - E-mail: ti@ecospb.com.br</span></td>
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
        "Attachment" => true /* Para download, altere para true */
    )
);


}else{

    header("location: ./index.php");
    exit();
}
?>