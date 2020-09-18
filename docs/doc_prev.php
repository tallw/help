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

$dompdf->set_paper('a4', 'landscape');

$html_form ='<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>PREVENTIVA</title><link rel="stylesheet" href="style/custom-os.css"></head><body>
<table class="tg" style="undefined;table-layout: fixed; width: 1100px; margin-left: 10px; margin-top: 25px;">
  <colgroup>
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
    <col style="width: 91.66px">
  </colgroup>
  <tr>
    <td class="tg-logo" colspan="8" rowspan="3"><img class="custom-logo-preventiva" src="logo/logo_gov_preventiva.png"></td>
    <td class="tg-center" colspan="3">ABERTURA DA SOLICITAÇÃO</td>
    <td class="tg-center-protocol" colspan="1" rowspan="3"><p class="custom-protocol">'.$protocolo.'</p></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="3">TI - ORDEM DE SERVIÇO Nº</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="1">DATA: </td>
    <td class="tg-center" colspan="2">'.$dt.'</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="8">1. IDENTIFICAÇÃO DO SOLICITANTE</td>
    <td class="tg-center" colspan="4">OS: ECOS ( X )  inSAÚDE ( )</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="1">Escola:</td>
    <td class="tg-center" colspan="7">'.$nome_escola.'</td>
    <td class="tg-center" colspan="2">Município:</td>
    <td class="tg-center" colspan="2">'.$cidade.'</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="1">Endereço:</td>
    <td class="tg-center" colspan="5">'.$endereco.'</td>
    <td class="tg-center" colspan="1">Telefone:</td>
    <td class="tg-center" colspan="2">'.$telefone.'</td>
    <td class="tg-center" colspan="1">GRE:</td>
    <td class="tg-center" colspan="2">'.$gre.'ª GRE</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="1">Nome/Resp:</td>
    <td class="tg-center" colspan="5">'.$gestor_escola.'</td>
    <td class="tg-center" colspan="1">RG:</td>
    <td class="tg-center" colspan="2"></td>
    <td class="tg-left" colspan="3">Cargo:</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="6">2. CHAMADO DE EMERGÊNCIA ( )</td>
    <td class="tg-center" colspan="6">SERVIÇO PROGRAMADO ( X )</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="12">3. SERVIÇO A EXECUTAR</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-left" colspan="6">DISCRIMINAÇÃO DOS SERVIÇOS</td>
    <td class="tg-center" colspan="3">EXECUTADO</td>
    <td class="tg-center" colspan="3">JUSTIFICATIVA</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center" colspan="1">1</td>
    <td class="tg-left" colspan="5">LIMPEZA</td>
    <td class="tg-center" colspan="1">S</td>
    <td class="tg-center" colspan="1">N</td>
    <td class="tg-center" colspan="1">N/A</td>
    <td class="tg-center" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">1.1</td>
    <td class="tg-left-tiny" colspan="5">Remoção de excesso de poeira</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">1.2</td>
    <td class="tg-left-tiny" colspan="5">Limpeza de coolers e verificação de sua eficiência de rotação</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">1.3</td>
    <td class="tg-left-tiny" colspan="5">Troca de pasta térmica do processador</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">1.4</td>
    <td class="tg-left-tiny" colspan="5">Desfragmentação de disco</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">1.5</td>
    <td class="tg-left-tiny" colspan="5">Limpeza do rack</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center" colspan="1">2</td>
    <td class="tg-left" colspan="5">REALIZAÇÃO DE TESTE</td>
    <td class="tg-center" colspan="1">S</td>
    <td class="tg-center" colspan="1">N</td>
    <td class="tg-center" colspan="1">N/A</td>
    <td class="tg-center" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">2.1</td>
    <td class="tg-left-tiny" colspan="5">Atualização de drivers de dispositivos</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">2.2</td>
    <td class="tg-left-tiny" colspan="5">Teste de desempenho Hardware</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center" colspan="1">3</td>
    <td class="tg-left" colspan="5">MANUTENÇÃO DE SOFTWARE</td>
    <td class="tg-center" colspan="1">S</td>
    <td class="tg-center" colspan="1">N</td>
    <td class="tg-center" colspan="1">N/A</td>
    <td class="tg-center" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.1</td>
    <td class="tg-left-tiny" colspan="5">Verificação e remoção de vírus</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.2</td>
    <td class="tg-left-tiny" colspan="5">Verificação e remoção de spywares</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.3</td>
    <td class="tg-left-tiny" colspan="5">Limpeza de arquivos temporários</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.4</td>
    <td class="tg-left-tiny" colspan="5">Formatação quando necessário</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.5</td>
    <td class="tg-left-tiny" colspan="5">Instalação de pacote Office</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center" colspan="1">4</td>
    <td class="tg-left" colspan="5">INVENTÁRIO EQUIPAMENTOS DE INFORMÁTICA</td>
    <td class="tg-center" colspan="1">S</td>
    <td class="tg-center" colspan="1">N</td>
    <td class="tg-center" colspan="1">N/A</td>
    <td class="tg-center" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">4.1</td>
    <td class="tg-left-tiny" colspan="5">Levantamento dos equipamentos (Computadores Completos Funcionando)</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">4.2</td>
    <td class="tg-left-tiny" colspan="5">Levantamento do sistema operacional</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-left-tiny" colspan="5">*Se possível inserir fotos comprovando a visita e os serviços</td>
    <td class="tg-center-tiny" colspan="6">S - SIM, N - NÃO, N/A - NÃO APLICÁVEL</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="6">LOCAL / DATA</td>
    <td class="tg-center-tiny" colspan="6">ASSINATURA E CARIMBO DO GESTOR ESCOLAR</td>
  </tr>
  <tr>
    <td class="tg-blank-center-prev" colspan="6">______________________________________ _____/_____/_____</td>
    <td class="tg-blank-center-prev" colspan="6">________________________________________________________</td>
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