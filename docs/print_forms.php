<?php

if(!isset($_SESSION)){ 

    session_start(); 

    //include_once("model_forms.php");

    $id_os = $_POST['id_os'];

    $usuario = $_SESSION['user_name'];

    $head = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>Ordem de Serviço</title><link rel="stylesheet" href="style/custom-os.css"></head><body>';

    $body = '';

    $divider = '<p class="whitespace"> </p>';

    $footer = '</body></html>';


    if(isset($_POST['multiples_imp']) && (isset($id_os)) && (isset($usuario))  && (!is_numeric($usuario))){

      $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
      $link->set_charset('utf8');

      $prints = $_POST['multiples_imp'];


      /* Carrega a classe DOMPdf */
      require_once("dompdf/dompdf_config.inc.php");

      /* Cria a instância */
      $dompdf = new DOMPDF();

      /* Dados OS */

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

      $sede = $row_OS->fk_id_sede;
      $endereco_sede = "";
      $contato_sede = "";

      if ($sede === '1') {
        $endereco_sede = "Rua: João Honório de Melo - Nº 54 - Catolé - Campina Grande - PB";
        $contato_sede = "Tel: (83) 3035-9787 - E-mail: ti@ecospb.com.br";
      }else if ($sede === '2') {
        $endereco_sede = "Rua: Rodrigo Chaves - Nº 390 - Trincheiras - João Pessoa - PB";
        $contato_sede = "Tel: (83) 3035-9787 - E-mail: ti@ecospb.com.br";
      }else if ($sede === '3') {
        $endereco_sede = "Rua: Deocleciano Pires - Nº 8 - Centro - Sousa - PB";
        $contato_sede = "Tel: (83) 3035-9787 - E-mail: ti@ecospb.com.br";
      }

      $dt_hr = explode(" ", $data_abertura);
      $dt = $dt_hr[0];
      $hr = '00:00:00';

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

      /* Fim dados OS */


      /* Dados escola */

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

      /* Fim dados escola */

      /* Formulário da OS */

$os_html_form ='<table class="tg" style="undefined;table-layout: fixed; width: 795px; margin-left: 10px; margin-top: 25px;">
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
        <td class="tg-left" colspan="4">Cargo:</td>
      </tr>
      <tr>
        <td class="tg-left" colspan="1">Telefone:</td>
        <td class="tg-left" colspan="2">'.$telefone.'</td>
        <td class="tg-left" colspan="3">RG:</td>
        <td class="tg-left" colspan="4">CPF:</td>
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
    </table>';

/* Formulário da Execução */

$execucao_html_form = '<table class="tg" style="undefined; width: 795px; margin-left: 10px; margin-top: 10px;">
<colgroup>
<col style="width: 79.5px">
<col style="width: 79.5px">
<col style="width: 79.5px">
<col style="width: 79.5px">
<col style="width: 79.5px">
<col style="width: 79.5px">
<col style="width: 79.5px">
<col style="width: 79.5px">
<col style="width: 79.5px">
<col style="width: 79.5px">
</colgroup>
  <tr>
    <th class="tg-logo" colspan="10"><img class="custom-logo-ecos" src="logo/logo_ecos.png"></th>
  </tr>
  <tr>
    <th class="tg-center" colspan="10">RELATÓRIO DE EXECUÇÃO DE SERVIÇOS</th>
  </tr>
  <tr>
    <td class="tg-center" colspan="1" rowspan="2">DATA:</td>
    <td class="tg-center" colspan="2" rowspan="2">_____/_____/_____</td>
    <td class="tg-center" colspan="2">HORA DE INÍCIO:</td>
    <td class="tg-center" colspan="2">HORA DE TÉRMINO</td>
    <td class="tg-center" colspan="3">PROTOCOLO:</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="2">:</td>
    <td class="tg-center" colspan="2">:</td>
    <td class="tg-center" colspan="3">'.$protocolo.'</td>
  </tr>
  <tr class="custom-line">
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
    <td class="tg-left" colspan="1">Email:</td>
    <td class="tg-left" colspan="6">'.$email.'</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="1">INEP:</td>
    <td class="tg-center" colspan="4">'.$inep_escola.'</td>
    <td class="tg-left" colspan="1">Circuito:</td>
    <td class="tg-center" colspan="4">'.$circuito.'</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic" colspan="10">LOCAL DE EXECUÇÃO DO SERVIÇO:</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic" colspan="10">SERVIÇO REALIZADO: EX: (Problema Nº X -> descrição do problema..., Ação problema Nº X -> descrição da ação..., Status problema Nº X -> descrição do Status...)</td>
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
    <td class="tg-topic" colspan="10">OBSERVAÇÕES: exemplo (Nº problema -> descrição, em caso de observação extra apenas descrever)</td>
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
    <td class="tg-topic" colspan="10">PENDÊNCIAS: exemplo (Nº problema -> descrição, em caso de pendência extra apenas descrever)</td>
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
    <td class="tg-topic" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-blank-ass-center" colspan="4">_________________________________________<br>ASSINATURA DO GESTOR / RESPONSÁVEL</td>
    <td class="tg-other-employer" colspan="6"><p class="custom-msg-top">Nome por extenso, RG e CPF de quem acompanhou a visita caso não foi o gestor.</p><br><span>CARGO: ___________________________<br><br>RG: ___________________________ CPF: ___________________________</span></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"><span>ECOS - Espaço Cidadania e Oportunidades Sociais</span></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"><span>'.$endereco_sede.'</span></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"><span>'.$contato_sede.'</span></td>
  </tr>
</table>';

/* Formulário do Checklist */
$checklist_html_form = '<table class="tg" style="undefined;table-layout: fixed; width: 795px; margin-left: 10px; margin-top: 10px;">
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
    <td class="tg-center-tiny" colspan="2">_____/_____/_____</td>
    <td class="tg-center-tiny" colspan="2">Horário de Início:</td>
    <td class="tg-center-tiny" colspan="2">:</td>
    <td class="tg-center-tiny" colspan="2">Horário de Término:</td>
    <td class="tg-center-tiny" colspan="2">:</td>
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
  <tr class="custom-line">
    <td class="tg-topic-center-tiny" colspan="12">Laboratório de Ciências</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4">Infraestrutura</td>
    <td class="tg-center-tiny" colspan="4">Kit de Ciências</td>
    <td class="tg-center-tiny" colspan="4">Montagem</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Adequada</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Tem</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Realizado</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Inadequada</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Não Tem</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Não Realizado</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Indisponível</td>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Agendado</td>
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
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Adequada</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Tem</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Tem</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Inadequada</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Não Tem</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Não Tem</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Indisponível</td>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4"></td>
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
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Adequada</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Realizado</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Inadequada</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Não Realizado</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"></td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Indisponível</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png"> Agendado</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-topic-center-tiny" colspan="12">Manutenção dos computadores da escola</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png">  Limpeza de Hardware</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png">  Manutenção de Software<</td>
    <td class="tg-center-tiny" colspan="4"><img width="11" height="11" src="logo/check.png">  Testes</td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Os dados da escola no SABER foram atualizados ?</td>
    <td class="tg-center-tiny" colspan="3">NÃO  <img width="11" height="11" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM  <img width="11" height="11" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Gestor Ausente:</td>
    <td class="tg-center-tiny" colspan="3">NÃO  <img width="11" height="11" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM  <img width="11" height="11" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Nome do Gestor:</td>
    <td class="tg-center-tiny" colspan="3">NÃO  <img width="11" height="11" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM  <img width="11" height="11" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Contatos Atualizados (Tel e e-mail):</td>
    <td class="tg-center-tiny" colspan="3">NÃO  <img width="11" height="11" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM  <img width="11" height="11" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Internet:</td>
    <td class="tg-center-tiny" colspan="3">NÃO  <img width="11" height="11" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM  <img width="11" height="11" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Foi assinado a folha de protocolo ?</td>
    <td class="tg-center-tiny" colspan="3">NÃO  <img width="11" height="11" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM  <img width="11" height="11" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Caixa de sugestões devidamente funcional:</td>
    <td class="tg-center-tiny" colspan="3">NÃO  <img width="11" height="11"src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM  <img width="11" height="11" src="logo/check.png"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="6">Biometria Funcionando:</td>
    <td class="tg-center-tiny" colspan="3">NÃO  <img width="11" height="11" src="logo/check.png"></td>
    <td class="tg-center-tiny" colspan="3">SIM  <img width="11" height="11" src="logo/check.png"></td>
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
  </tr>
  <tr>
    <td class="tg-blank-ass-center-checklist" colspan="5">________________________________________________<br>ASSINATURA DO GESTOR / RESPONSÁVEL</td>
    <td class="tg-blank-ass-center-checklist" colspan="7">________________________________________________<br>TÉCNICO(S) / ASSINATURA DO EXECUTOR(ES)</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="12"><span>ECOS - Espaço Cidadania e Oportunidades Sociais</span></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="12"><span>'.$endereco_sede.'</span></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="12"><span>'.$contato_sede.'</span></td>
  </tr>
</table>';

/* Formulário da manutenção Preventiva */

$mnt_prev_html_form = '<table class="tg" style="undefined;table-layout: fixed; width: 795px; margin-left: 10px; margin-top: 10px;">
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
    <td class="tg-logo" colspan="7" rowspan="3"><img class="custom-logo-preventiva" src="logo/logo_gov_preventiva.png"></td>
    <td class="tg-center" colspan="3">ABERTURA DA SOLICITAÇÃO</td>
    <td class="tg-center-protocol" colspan="2" rowspan="3"><p class="custom-protocol">'.$protocolo.'</p></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="3">TI - ORDEM DE SERVIÇO Nº</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="1">DATA: </td>
    <td class="tg-center" colspan="2">_____/_____/_____</td>
  </tr>
  <tr class="custom-line">
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
    <td class="tg-center" colspan="2">EXECUTADO</td>
    <td class="tg-center" colspan="4">JUSTIFICATIVA</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center" colspan="1">1</td>
    <td class="tg-left" colspan="5">LIMPEZA</td>
    <td class="tg-center" colspan="1">S</td>
    <td class="tg-center" colspan="1">N</td>
    <td class="tg-center" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1" >1.1</td>
    <td class="tg-left-tiny" colspan="5" >Remoção de excesso de poeira</td>
    <td class="tg-center-tiny" colspan="1" ></td>
    <td class="tg-center-tiny" colspan="1" ></td>
    <td class="tg-center-tiny" colspan="4" ></td>
  </tr>
  
  <tr>
    <td class="tg-center-tiny" colspan="1">1.2</td>
    <td class="tg-left-tiny" colspan="5">Limpeza de coolers e verificação de sua eficiência de rotação</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">1.3</td>
    <td class="tg-left-tiny" colspan="5">Troca de pasta térmica do processador</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">1.4</td>
    <td class="tg-left-tiny" colspan="5">Desfragmentação de disco</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">1.5</td>
    <td class="tg-left-tiny" colspan="5">Limpeza do rack</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center" colspan="1">2</td>
    <td class="tg-left" colspan="5">REALIZAÇÃO DE TESTE</td>
    <td class="tg-center" colspan="1">S</td>
    <td class="tg-center" colspan="1">N</td>
    <td class="tg-center" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">2.1</td>
    <td class="tg-left-tiny" colspan="5">Atualização de drivers de dispositivos</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">2.2</td>
    <td class="tg-left-tiny" colspan="5">Teste de desempenho Hardware</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center" colspan="1">3</td>
    <td class="tg-left" colspan="5">MANUTENÇÃO DE SOFTWARE</td>
    <td class="tg-center" colspan="1">S</td>
    <td class="tg-center" colspan="1">N</td>
    <td class="tg-center" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.1</td>
    <td class="tg-left-tiny" colspan="5">Verificação e remoção de vírus</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.2</td>
    <td class="tg-left-tiny" colspan="5">Verificação e remoção de spywares</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.3</td>
    <td class="tg-left-tiny" colspan="5">Limpeza de arquivos temporários</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.4</td>
    <td class="tg-left-tiny" colspan="5">Formatação quando necessário</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">3.5</td>
    <td class="tg-left-tiny" colspan="5">Instalação de pacote Office</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center" colspan="1">4</td>
    <td class="tg-left" colspan="5">VISTÓRIA (EQUIPAMENTOS E SOFTWARES)</td>
    <td class="tg-center" colspan="1">S</td>
    <td class="tg-center" colspan="1">N</td>
    <td class="tg-center" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">4.1</td>
    <td class="tg-left-tiny" colspan="5">Levantamento dos equipamentos (Computadores Completos Funcionando)</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">4.2</td>
    <td class="tg-left-tiny" colspan="5">Levantamento do sistema operacional</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">4.3</td>
    <td class="tg-left-tiny" colspan="5">*Se possível inserir fotos comprovando a visita e os serviços</td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="1"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center" colspan="1">5</td>
    <td class="tg-left" colspan="5">INVENTÁRIO LABORATÓRIOS E EQUIPAMENTOS DE TI.</td>
    <td class="tg-center" colspan="2">QTDE</td>
    <td class="tg-center" colspan="4">OBS:</td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">5.1</td>
    <td class="tg-left-tiny" colspan="5">Quantidade de laboratórios de TI na escola.</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">5.2</td>
    <td class="tg-left-tiny" colspan="5">Quantidade de computadores na escola.</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">5.3</td>
    <td class="tg-left-tiny" colspan="5">Quantidade de computadores funcionais na escola.</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">5.4</td>
    <td class="tg-left-tiny" colspan="5">Quantidade de computadores nos laboratórios de TI.</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="1">5.5</td>
    <td class="tg-left-tiny" colspan="5">Quantidade de computadores funcionais nos laboratórios de TI.</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-center-tiny" colspan="4"></td>
  </tr>
  <tr class="custom-line">
    <td class="tg-left" colspan="12">OBSERVAÇÕES:</td>
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
  <tr class="custom-line">
    <td class="tg-center-tiny" colspan="6">LOCAL / DATA</td>
    <td class="tg-center-tiny" colspan="6">ASSINATURA E CARIMBO DO GESTOR ESCOLAR</td>
  </tr>
  <tr>
    <td class="tg-blank-center-prev" colspan="6">______________________________________ _____/_____/_____</td>
    <td class="tg-blank-center-prev" colspan="6">________________________________________________________</td>
  </tr>
</table>';

/* Formulário do Levantamento */

$levantamento_html_form = '<table class="tg" style="undefined;table-layout: fixed; width: 795px; margin-left: 10px; margin-top: 10px;">
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
    <th class="tg-center" colspan="10">DETALHAMENTO DE LEVANTAMENTO PARA OBRA</th>
  </tr>
  <tr>
    <td class="tg-left custom-line" colspan="6">1. IDENTIFICAÇÃO DO SOLICITANTE</td>
    <td class="tg-center" colspan="2">PROTOCOLO:</td>
    <td class="tg-center" colspan="2">'.$protocolo.'</td>
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
    <td class="tg-topic" colspan="10">LOCAL:</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"></td>
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
    <td class="tg-topic" colspan="10">LOCAL:</td>
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
    <td class="tg-topic" colspan="10">LOCAL:</td>
  </tr>
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
    <td class="tg-blank-ass-center" colspan="4">ASSINATURA DO GESTOR</td>
    <td class="tg-blank-ass-center" colspan="6">TÉCNICO (S) DO EXECUTOR (ES)</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"><span>ECOS - Espaço Cidadania e Oportunidades Sociais</span></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"><span>'.$endereco_sede.'</span></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="10"><span>'.$contato_sede.'</span></td>
  </tr>
</table>';

/* Formulário da Lista de Materiais */

$query_itens_e = "SELECT * FROM itens_de_cotacao WHERE tipo_item = 'eletrica' order by nome_item";
$query_itens_ti = "SELECT * FROM itens_de_cotacao WHERE tipo_item = 'computacao' order by nome_item";


$result_itens_e = $link->query($query_itens_e);
$result_itens_ti = $link->query($query_itens_ti);

$itens_e = array();
$itens_ti = array();

while ($row_e = mysqli_fetch_object($result_itens_e)) {
  array_push($itens_e, $row_e->nome_item);
}

while ($row_ti = mysqli_fetch_object($result_itens_ti)) {
  array_push($itens_ti, $row_ti->nome_item);
}

$lista_materiais_html_form = '<table class="tg" style="undefined;table-layout: fixed; width: 795px; margin-left: 10px; margin-top: 10px;">
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
    <td class="tg-left-tiny" colspan="1">Técnico:</td>
    <td class="tg-left-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4">DESCRIÇÃO</td>
    <td class="tg-center-tiny" colspan="2">DADOS</td>
    <td class="tg-center-tiny" colspan="4">DESCRIÇÃO</td>
    <td class="tg-center-tiny" colspan="2">DADOS</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center-tiny" colspan="4">REDE ELÉTRICA</td>
    <td class="tg-center-tiny" colspan="2">REFERÊNCIA/QTDE</td>  
    <td class="tg-center-tiny" colspan="4">INFORMATICA</td>
    <td class="tg-center-tiny" colspan="2">REFERÊNCIA/QTDE</td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[0].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[0].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[1].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[1].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[2].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[2].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[3].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[3].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[4].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[4].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[5].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[5].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[6].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[6].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[7].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[7].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[8].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[8].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[9].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[9].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[10].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[10].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[11].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[11].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[12].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[12].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[13].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[13].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[14].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[14].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[15].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[15].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[16].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[16].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[17].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[17].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[18].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[18].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[19].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[19].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[20].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[20].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[21].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[21].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[22].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[22].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[23].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[23].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[24].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[24].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[25].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[25].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[26].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[26].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[27].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[27].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[28].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[28].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[29].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[29].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[30].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[30].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[31].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[31].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[32].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[32].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[33].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[33].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[34].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[34].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[35].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[35].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left-tiny" colspan="4">'.$itens_e[36].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
    <td class="tg-left-tiny" colspan="4">'.$itens_ti[36].'</td>
    <td class="tg-center-tiny" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-topic-tiny" colspan="12">Observações:</td>
  </tr>
</table>'; 
/**<tr>
    <td class="tg-center-tiny" colspan="12"><span>ECOS - Espaço Cidadania e Oportunidades Sociais</span></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="12"><span>'.$endereco_sede.'</span></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="12"><span>'.$contato_sede.'</span></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="6"><span>ADM = Todo o setor administrativo da escola</span></td>
    <td class="tg-center-tiny" colspan="6"><span>LABs = Todos os laboratórios da escola</span></td>
  </tr>**/

      /* Gera o arquivo para impressão */

/* Formulário da Lista de Materiais */

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
    <td class="tg-left-tiny" colspan="1">Técnico:</td>
    <td class="tg-left-tiny" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-center-tiny" colspan="4">DESCRIÇÃO</td>
    <td class="tg-center-tiny" colspan="2">DADOS</td>
    <td class="tg-center-tiny" colspan="4">DESCRIÇÃO</td>
    <td class="tg-center-tiny" colspan="2">DADOS</td>
  </tr>
  <tr class="custom-line">
    <td class="tg-center-tiny" colspan="4">REDE ELÉTRICA</td>
    <td class="tg-center-tiny" colspan="2">REFERÊNCIA/QTDE</td>  
    <td class="tg-center-tiny" colspan="4">INFORMATICA</td>
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
</table>'; 

      $documentos_name = array('os', 'execucao', 'checklist', 'levantamento', 'lista_materiais', 'lista_insumos', 'preventiva');
      $documentos_html = array($os_html_form, $execucao_html_form, $checklist_html_form, $levantamento_html_form, $lista_materiais_html_form, $lista_insumos_html_form, $mnt_prev_html_form);

      foreach ($prints as $i => $doc) {       

          $key = array_search($doc, $documentos_name);

          if ($i+1 === count($prints)) {
            $body .= $documentos_html[$key];
          }else{
            $body .= $documentos_html[$key].$divider;
          }

        

      } 

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