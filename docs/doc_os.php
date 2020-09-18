<?php

$id_os = $_GET['id_os'];


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
$obs_op = $row_OS->obs_operador;

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

if ($tipo === '1') {

	$emergencial = ' ';
	$programado = ' X ';
}else{

  $emergencial = ' X ';
  $programado = ' ';

}

$id_motivo = $row_OS->fk_id_motivo_os;
$query_motivo = "SELECT * FROM sub_motivo_chamado where id_sub_motivo = '$id_motivo'";
$result_motivo = $link->query($query_motivo);
$row_motivo = mysqli_fetch_object($result_motivo);
$submotivo = $row_motivo->sub_motivo;
$id_motivo = $row_motivo->fk_id_motivo_chamado;




$query_motivo_chamado = "SELECT motivo FROM motivo_chamado WHERE id_motivo = '$id_motivo'";
$result_motivo_chamado = $link->query($query_motivo_chamado);
$row_motivo_chamado = mysqli_fetch_object($result_motivo_chamado);
$motivo = $row_motivo_chamado->motivo;

//###########################################################################


//######################### DADOS ESCOLA ####################################

$id_escola = $row_OS->fk_id_nome_escola;

$query_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'";
$result_escola = $link->query($query_escola);
$row_escola = mysqli_fetch_object($result_escola);


$inep_escola = $row_escola->inep;
$cidade = $row_escola->cidade;
$nome_escola = $row_escola->nome_escola;
$gestor_escola = $row_escola->responsavel;
$endereco = $row_escola->endereco;
$gre = $row_escola->gre;
$telefone = $row_escola->contato01.' - '.$row_escola->contato02;

//############################################################################



  /* Carrega a classe DOMPdf */
require_once("dompdf/dompdf_config.inc.php");

/* Cria a instância */
$dompdf = new DOMPDF();


$html_form ='<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>OS</title><link rel="stylesheet" href="style/custom-os.css"></head><body>
    <table class="tg" style="undefined;table-layout: fixed; width: 795px; margin-left: 10px; margin-top: 25px;">
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
      </colgroup>
      <tr>
        <th class="tg-logo" colspan="6" rowspan="4"><img class="custom-logo-os" src="logo/logo_gov.png"></th>
        <th class="tg-center" colspan="2">PROTOCOLO</th>
        <th class="tg-center" colspan="2">'.$protocolo.'</th>
      </tr>
      <tr>
        <td class="tg-center" colspan="4">ABERTURA DA SOLICITAÇÃO</td>
      </tr>
      <tr>
        <td class="tg-center" colspan="2">DATA</td>
        <td class="tg-center" colspan="2">HORA</td>
      </tr>
      <tr>
        <td class="tg-center" colspan="2">'.$dt.'</td>
        <td class="tg-center" colspan="2">'.$hr.'</td>
      </tr>
      <tr class="custom-line">
        <td class="tg-left" colspan="10">1. IDENTIFICAÇÃO DO SOLICITANTE</td>
      </tr>
      <tr>
        <td class="tg-left" colspan="1">Escola:</td>
        <td class="tg-left" colspan="5">'.$nome_escola.'</td>
        <td class="tg-left" colspan="1">Municipio:</td>
        <td class="tg-left" colspan="3">'.$cidade.'</td>
      </tr>
      <tr>
        <td class="tg-left" colspan="1">Endereço:</td>
        <td class="tg-left" colspan="5">'.$endereco.'</td>
        <td class="tg-left" colspan="1">GRE:</td>
        <td class="tg-left" colspan="3">'.$gre.'ª GRE</td>
      </tr>
      <tr>
        <td class="tg-left" colspan="1">Nome/Resp.:</td>
        <td class="tg-left" colspan="5">'.$gestor_escola.'</td>
        <td class="tg-left" colspan="1">Cargo:</td>
        <td class="tg-left" colspan="3"></td>
      </tr>
      <tr>
        <td class="tg-left" colspan="1">Telefone:</td>
        <td class="tg-left" colspan="2">'.$telefone.'</td>
        <td class="tg-left" colspan="1">RG:</td>
        <td class="tg-left" colspan="2"></td>
        <td class="tg-left" colspan="1">CPF:</td>
        <td class="tg-left" colspan="3"></td>
      </tr>
      <tr>
        <td class="tg-left custom-line" colspan="2">2.TIPO:</td>
        <td class="tg-left" colspan="4">CHAMADO DE EMERGÊNCIA ('.$emergencial.')</td>
        <td class="tg-left" colspan="4">SERVIÇO PROGRAMADO ('.$programado.')</td>
      </tr>
      <tr class="custom-line">
        <td class="tg-left" colspan="10">3. SERVIÇO A EXECUTAR</td>
      </tr>
      <tr>
        <td class="tg-center" colspan="10">'.$motivo.'</td>
      </tr>
      <tr>
        <td class="tg-center" colspan="10">'.$submotivo.'</td>
      </tr>
      <tr>
        <td class="tg-blank-center" colspan="1">OBS:</td>
        <td class="tg-blank-center" colspan="9">'.$obs_op.'</td>
      </tr>
      <tr>
        <td class="tg-left custom-line" colspan="5">4. ATENDIMENTO DE CHAMADO</td>
        <td class="tg-center" colspan="5" rowspan="3"><p class="custom-msg-top">Assinatura e carimbo</p></td>
      </tr>
      <tr>
        <td class="tg-left" colspan="5">Confirmo a visita técnica realizada das datas e horários informados abaixo.</td>
      </tr>
      <tr>
        <td class="tg-left" colspan="3">Data:  _____/_____/_____   Hora  ____ : ____</td>
        <td class="tg-left" colspan="2">Ass. e carimbo do gestor escolar</td>
      </tr>
      <tr class="custom-line">
        <td class="tg-left" colspan="10">5. FUNCIONÁRIO(S) RESPONSÁVEL(IS) PELO SERVIÇO A SEREM EXECUTADOS</td>
      </tr>
      <tr>
        <td class="tg-center"></td>
        <td class="tg-center" colspan="4">Nome do funcionário</td>
        <td class="tg-center" colspan="5">Cargo/função</td>
      </tr>
      <tr>
        <td class="tg-center" colspan="1">1</td>
        <td class="tg-left" colspan="4"></td>
        <td class="tg-left" colspan="5"></td>
      </tr>
      <tr>
        <td class="tg-center" colspan="1">2</td>
        <td class="tg-left" colspan="4"></td>
        <td class="tg-left" colspan="5"></td>
      </tr>
      <tr>
        <td class="tg-center" colspan="1">3</td>
        <td class="tg-left" colspan="4"></td>
        <td class="tg-left" colspan="5"></td>
      </tr>
      <tr>
        <td class="tg-center" colspan="1">4</td>
        <td class="tg-left" colspan="4"></td>
        <td class="tg-left" colspan="5"></td>
      </tr>
      <tr>
        <td class="tg-center" colspan="3">Data de início do serviço</td>
        <td class="tg-center" colspan="2">Hora</td>
        <td class="tg-center" colspan="3">Data de término do serviço</td>
        <td class="tg-center" colspan="2">Hora</td>
      </tr>
      <tr>
        <td class="tg-center" colspan="3">_______/_______/_______</td>
        <td class="tg-center" colspan="2">____ : ____</td>
        <td class="tg-center" colspan="3">_______/_______/_______</td>
        <td class="tg-center" colspan="2">____ : ____</td>
      </tr>
      <tr>
        <td class="tg-left custom-line" colspan="5">6. ENCERRAMENTO DO SERVIÇO</td>
        <td class="tg-center" colspan="5" rowspan="3"><p class="custom-msg-top">Assinatura e carimbo</p></td>
      </tr>
      <tr>
        <td class="tg-left" colspan="5">Declaro que o serviço acima solicitado, foi executado e encerrado considerando aceito o serviço.</td>
      </tr>
      <tr>
        <td class="tg-left" colspan="3">Data:  _____/_____/_____   Hora  ____ : ____</td>
        <td class="tg-left" colspan="2">Ass. e carimbo do gestor escolar</td>
      </tr>
      <tr>
        <td class="tg-blank-center" colspan="1">OBS:</td>
        <td class="tg-other-employer" colspan="6"><p class="custom-msg">Nome completo e por extenso, RG e CPF de quem acompanhou a visita caso não for o gestor.</p><span class="other-employer">NOME: ___________________________________________________ CARGO: _____________________</span></td>
        <td class="tg-blank-center" colspan="3"><span class="other-employer">RG: ___________________________<br><br>CPF: ___________________________</span></td>
      </tr>
      <tr>
        <td class="tg-left" colspan="10"><span class="custom">ESTE REGISTRO DEVE RELACIONAR O MAIOR NÚMERO DE INFORMAÇÕES POSSÍVEIS, RATIFICANDO POR MEIO DA ANEXAÇÃO DE DOCUMENTOS A QUALIDADE E A VERACIDADE DAS INFORMAÇÕES. SE POSSÍVEL ANEXAR FOTOS.</span></td>
      </tr>
      <tr>
        <td class="tg-center" colspan="5">SATISFAÇÃO COM O ATENDIMENTO PRESENCIAL<br/><br/><img class="custom-mult_check" src="logo/mult_checks.png"></td>
        <td class="tg-center" colspan="5"><p class="custom-msg-top">Nome completo e carimbo</p></td>
      </tr>
    </table>
  </body></html>';


/* Carrega seu HTML */
$dompdf->load_html($html_form);


/* Renderiza */   
$dompdf->render();

/* Exibe */
$dompdf->stream(
    "Arquivo.pdf", /* Nome do arquivo de saída */
    array(
        "Attachment" => true /* Para download, altere para true */
    )
);



?>