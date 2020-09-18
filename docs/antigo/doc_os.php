<?php


$id_os = $_GET['id_os'];

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
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
$programado = '';

if ($tipo === 1) {
	$emergencial = '';
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


$inep_escola = $row_escola->inep;
$cidade = $row_escola->cidade;
$nome_escola = $row_escola->nome_escola;
$gestor_escola = $row_escola->responsavel;
$endereco = $row_escola->endereco;
$gre = $row_escola->gre;
$telefone = $row_escola->contato01.' - '.$row_escola->contato02;

//############################################################################





if(isset($usuario)  && !is_numeric($usuario)){ // tratar para que não possa ser visto OSs de outro setores passando id na url (verificar se escola pertence ao user)

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OS</title>
    <link rel="stylesheet" href="style/custom-os.css"> 
</head>

<body>

<table class="tg" style="undefined;table-layout: fixed; width: 1076px">
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
    <th class="tg-center" colspan="6" rowspan="4"><img src="logo/logo.png"></th>
    <th class="tg-center" colspan="2">PROTOCOLO</th>
    <th class="tg-center" colspan="2"><?php echo $protocolo; ?></th>
  </tr>
  <tr>
    <td class="tg-center" colspan="4">ABERTURA DA SOLICITAÇÃO</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="2">DATA</td>
    <td class="tg-center" colspan="2">HORA</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="2" rowspan="2"><?php echo $dt; ?></td>
    <td class="tg-center" colspan="2" rowspan="2"><?php echo $hr; ?></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="6">Secretaria de Estado da Educação da Paraíba - Comissão de Acompanhamento, Monitoramento e Avaliação das Organizações Sociais</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="10">1. IDENTIFICAÇÃO DO SOLICITANTE</td>
  </tr>
  <tr>
    <td class="tg-left">Escola:</td>
    <td class="tg-left" colspan="4"><?php echo $nome_escola; ?></td>
    <td class="tg-left">Municipio:</td>
    <td class="tg-left" colspan="4"><?php echo $cidade; ?></td>
  </tr>
  <tr>
    <td class="tg-left">Endereço:</td>
    <td class="tg-left" colspan="4"><?php echo $endereco; ?></td>
    <td class="tg-left">GRE:</td>
    <td class="tg-left" colspan="4"><?php echo $gre; ?>ª GRE</td>
  </tr>
  <tr>
    <td class="tg-left">Nome/Resp.:</td>
    <td class="tg-left" colspan="4"><?php echo $gestor_escola; ?></td>
    <td class="tg-left">Cargo:</td>
    <td class="tg-left" colspan="4"></td>
  </tr>
  <tr>
    <td class="tg-left">Telefone:</td>
    <td class="tg-left" colspan="2"><?php echo $telefone; ?></td>
    <td class="tg-left" colspan="2">RG:</td>
    <td class="tg-left"></td>
    <td class="tg-left">CPF:</td>
    <td class="tg-left" colspan="3"></td>
  </tr>
  <tr>
    <td class="tg-left">2.TIPO:</td>
    <td class="tg-left" colspan="2">CHAMADO DE EMERGÊNCIA</td>
    <td class="tg-center"><?php echo $emergencial; ?></td>
    <td class="tg-left" colspan="3">SERVIÇO PROGRAMADO</td>
    <td class="tg-center"><?php echo $programado; ?></td>
    <td class="tg-left" colspan="2"></td>
  </tr>
  <tr>
    <td class="tg-left" colspan="10">3. SERVIÇO A EXECUTAR</td>
  </tr>
  <tr>
    <td class="tg-blank-reason" colspan="10" rowspan="3"><?php echo $motivo; ?></td>
  </tr>
  <tr>
    <td class="tg-left"></td>
  </tr>
  <tr>
    <td class="tg-left"></td>
  </tr>
  <tr>
    <td class="tg-blank-center" rowspan="4">OBS:</td>
    <td class="tg-blank-center" colspan="9" rowspan="4"></td>
  </tr>
   <tr>
    <td class="tg-left" ></td>
  </tr>
  <tr>
    <td class="tg-left"></td>
  </tr>
  <tr>
    <td class="tg-left"></td>
  </tr>
  <tr>
    <td class="tg-left" colspan="10">4. ATENDIMENTO DE CHAMADO</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="5">Confirmo a visita técnica realizada das datas e horários informados abaixo.</td>
    <td class="tg-blank-left" colspan="5" rowspan="3"></td>
  </tr>
  <tr>
    <td class="tg-blank-left" colspan="2" rowspan="2">Data:  ___ /___ /___   Hora  ___ : ___</td>
    <td class="tg-blank-left" colspan="3" rowspan="2">Ass. e carimbo do gestor escolar</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="10"></td>
  </tr>
  <tr>
    <td class="tg-left" colspan="10">5. FUNCIONÁRIO(S) RESPONSÁVEL(IS) PELO SERVIÇO A SERVIÇO A SEREM EXECUTADOS</td>
  </tr>
  <tr>
    <td class="tg-left"></td>
    <td class="tg-center" colspan="4">Nome do funcionário</td>
    <td class="tg-center" colspan="5">Cargo/função</td>
  </tr>
  <tr>
    <td class="tg-center">1</td>
    <td class="tg-left" colspan="4"></td>
    <td class="tg-left" colspan="5"></td>
  </tr>
  <tr>
    <td class="tg-center">2</td>
    <td class="tg-left" colspan="4"></td>
    <td class="tg-left" colspan="5"></td>
  </tr>
  <tr>
    <td class="tg-center">3</td>
    <td class="tg-left" colspan="4"></td>
    <td class="tg-left" colspan="5"></td>
  </tr>
  <tr>
    <td class="tg-center">4</td>
    <td class="tg-left" colspan="4"></td>
    <td class="tg-left" colspan="5"></td>
  </tr>
  <tr>
    <td class="tg-center" colspan="2">Data de início do serviço</td>
    <td class="tg-center" colspan="3">Hora</td>
    <td class="tg-center" colspan="3">Data de término do serviço</td>
    <td class="tg-center" colspan="2">Hora</td>
  </tr>
  <tr>
    <td class="tg-center" colspan="2">_____/_______/______</td>
    <td class="tg-center" colspan="3">___:___</td>
    <td class="tg-center" colspan="3">_____/_______/______</td>
    <td class="tg-center" colspan="2">___:___</td>
  </tr>
  <tr>
    <td class="tg-left" colspan="5">6. ENCERRAMENTO DO SERVIÇO</td>
    <td class="tg-left" colspan="5" rowspan="3"></td>
  </tr>
  <tr>
    <td class="tg-left" colspan="5">Declaro que o serviço acima solicitado, foi executado e encerrado considerando aceito o serviço.</td>
  </tr>
  <tr>
    <td class="tg-blank-left" colspan="2">Data:  ___ /___ /___   Hora  ___ : ___</td>
    <td class="tg-blank-left" colspan="3">Ass. e carimbo do gestor escolar</td>
  </tr>
  <tr>
    <td class="tg-blank-center" rowspan="4">OBS:</td>
    <td class="tg-blank-center" colspan="9" rowspan="4"></td>
  </tr>
  <tr>
    <td class="tg-left" ></td>
  </tr>
  <tr>
    <td class="tg-left"></td>
  </tr>
  <tr>
    <td class="tg-left"></td>
  </tr>
  <tr>
    <td class="tg-left" colspan="10"><span>ESTE REGISTRO DEVE RELACIONAR O MAIOR NÚMERO DE INFORMAÇÕES POSSÍVEIS, RATIFICANDO POR MEIO DA ANEXAÇÃO DE DOCUMENTOS A QUALIDADE E A VERACIDADE DAS INFORMAÇÕES. SE POSSÍVEL ANEXAR FOTOS.</span></td>
  </tr>
</table>
</body>

</html>

<?php
}else{

    header("location: ./index.php");
    exit();
}
?>