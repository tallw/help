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


$html_form = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>CHECK-LIST</title><link rel="stylesheet" href="style/custom-os.css"></head><body>
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
    <th class="tg-logo" colspan="12"><img class="custom-logo-ecos" src="logo/logo_ecos.png"></th>
  </tr>
  <tr>
    <th class="tg-center-tiny" colspan="12">RELATÓRIO DE MANUTENÇÃO DE ESCOLAS (Checklist)</th>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="6">PROTOCOLO:</td>
    <td class="tg-center-tiny" colspan="6">'.$protocolo.'</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="2">Data da Visita:</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-center-tiny" colspan="2">Horário de Início:</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-center-tiny" colspan="2">Horário de Término:</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center-tiny" colspan="12">1. IDENTIFICAÇÃO DA ESCOLA</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="1">Escola:</td>
    <td class="tg-left-tiny" colspan="6">'.$nome_escola.'</td>
    <td class="tg-left-tiny" colspan="2">Municipio:</td>
    <td class="tg-left-tiny" colspan="3">'.$cidade.'</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="1">Endereço:</td>
    <td class="tg-left-tiny" colspan="6">'.$endereco.'</td>
    <td class="tg-left-tiny" colspan="1">GRE:</td>
    <td class="tg-left-tiny" colspan="4">'.$gre.'ª GRE</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="2">Nome / Responsável:</td>
    <td class="tg-left-tiny" colspan="5">'.$gestor_escola.'</td>
    <td class="tg-left-tiny" colspan="1">Cargo:</td>
    <td class="tg-left-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="2">INEP:</td>
    <td class="tg-center-tiny" colspan="4">'.$inep_escola.'</td>
    <td class="tg-left-tiny" colspan="2">Circuito:</td>
    <td class="tg-center-tiny" colspan="4">'.$circuito.'</td>
  </tr>
  <tr>
    <td class="tg-line" colspan="12"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic-center-tiny" colspan="12">Laboratório de Ciências</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4">Infraestrutura</td>
    <td class="tg-center-tiny" colspan="4">Kit de Ciências</td>
    <td class="tg-center-tiny" colspan="4">Montagem</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Adequada</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Tem</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Realizado</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Inadequada</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Não Tem</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Não Realizado</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Indisponível</td>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Agendado</td>
  </tr>
  <tr>
    <td class="tg-line" colspan="12"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic-center-tiny" colspan="12">Laboratório de Matemática/Robótica</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4">Infraestrutura</td>
    <td class="tg-center-tiny" colspan="4">Kit de Robótica</td>
    <td class="tg-center-tiny" colspan="4">Kit de Matemática</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Adequada</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Tem</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Tem</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Inadequada</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Não Tem</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Não Tem</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Indisponível</td>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-line" colspan="12"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic-center-tiny" colspan="12">Biblioteca</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4">Infraestrutura</td>
    <td class="tg-center-tiny" colspan="4">Montagem</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Adequada</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Realizado</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Inadequada</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Não Realizado</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Indisponível</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-01" src="logo/check.png">Agendado</td>
  </tr>
  <tr>
    <td class="tg-line" colspan="12"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic-center-tiny" colspan="12">Manutenção dos computadores da escola</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-03" src="logo/check.png">Limpeza de Hardware</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-03" src="logo/check.png">Manutenção de Software<</td>
    <td class="tg-center-tiny" colspan="4"><img class="custom-check-03" src="logo/check.png">Testes</td>
  </tr>
  <tr>
    <td class="tg-line-tiny" colspan="12"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Os dados da escola no SABER foram atualizados ?</td>
    <td class="tg-center-tiny" colspan="3">NÃO<img class="custom-check-02" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM<img class="custom-check-02" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Gestor Ausente:</td>
    <td class="tg-center-tiny" colspan="3">NÃO<img class="custom-check-02" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM<img class="custom-check-02" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Nome do Gestor:</td>
    <td class="tg-center-tiny" colspan="3">NÃO<img class="custom-check-02" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM<img class="custom-check-02" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Contatos Atualizados (Tel e e-mail):</td>
    <td class="tg-center-tiny" colspan="3">NÃO<img class="custom-check-02" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM<img class="custom-check-02" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Internet:</td>
    <td class="tg-center-tiny" colspan="3">NÃO<img class="custom-check-02" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM<img class="custom-check-02" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Foi assinado a folha de protocolo ?</td>
    <td class="tg-center-tiny" colspan="3">NÃO<img class="custom-check-02" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM<img class="custom-check-02" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Caixa de sugestões devidamente funcional:</td>
    <td class="tg-center-tiny" colspan="3">NÃO<img class="custom-check-02" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM<img class="custom-check-02" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Biometria Funcionando:</td>
    <td class="tg-center-tiny" colspan="3">NÃO<img class="custom-check-02" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM<img class="custom-check-02" src="logo/check.png"></td>
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