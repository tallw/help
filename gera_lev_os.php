<?php

$id_os = $_GET['id_os'];

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');

if (!$link){

    die('Connect Error (' . mysqli_connecterrno() . ')' .mysqli_connect_error());

}else{

	$query_OS = "SELECT * FROM ordem_servico WHERE id_os = '$id_os'";
	$result_OS = $link->query($query_OS);
	$row_OS = mysqli_fetch_object($result_OS);
	$protocolo = $row_OS->protocolo;
    $fk_id_escola = $row_OS->fk_id_nome_escola;

    $query_escola = "SELECT * FROM escola WHERE id_escola = '$fk_id_escola'";
    $result_escola = $link->query($query_escola);
    $row_escola = mysqli_fetch_object($result_escola);
    $nome_escola = $row_escola->nome_escola;



	$arquivo = 'Levantamento de itens OS: '.$protocolo.'.xls';

	$html = '';
	$html .= '<table border="1">';
	$html .= '<tr>
                <td colspan="5"><center><b>Levantamento de Material para OS: '.$protocolo.'</b></center></td>      
              </tr>
              <tr>
                <td><b>Escola:</b></td>
                <td colspan="4">'.$nome_escola.'</td>     
              </tr>
            <tr>
               	<td><center><b>Item</b></center></td>
                <td><center><b>Nome Item</b></center></td>
              	<td><center><b>Quantidade</b></center></td>
                <td><center><b>Referencia</b></center></td>
                <td><center><b>Destino</b></center></td>
            </tr>';

    $query_cot_os = "SELECT * FROM cotacoes_os WHERE fk_id_os_cotada = '$id_os'";
    $result_cot_os = $link->query($query_cot_os);
    $row_cot_os = mysqli_fetch_object($result_cot_os);
    $obs_cotacao = $row_cot_os->obs_cotacao;
    $id_cotacao = $row_cot_os->id_cotacao;

    $query_itens = "SELECT itdc.nome_item nome, itc.quantidade qtde, itc.referencia ref, itc.local_destino loc FROM itens_cotados itc, itens_de_cotacao itdc WHERE itc.fk_id_cotacao = '$id_cotacao' and itc.fk_id_item = itdc.id_item order by itdc.nome_item";
    $result_itens = $link->query($query_itens);

    $item = 1;

    while ($row_itens = mysqli_fetch_object($result_itens)) { 

    	$html .=  "<tr>";	    
    	$cols = "";	

    	$cols = $cols."<td>".$item."</td>";	    
    	$cols = $cols."<td>".utf8_decode($row_itens->nome)."</td>";	     
    	$cols = $cols."<td>".$row_itens->qtde."</td>";	  	    
    	$cols = $cols."<td>".utf8_decode($row_itens->ref)."</td>";

        if ($row_itens->loc === '1') {
            $local = 'Laboratorios';
        }else if($row_itens->loc === '2'){
            $local = 'Áreas Administrativas';
        }else{
            $local = 'Toda escola';
        }
        $cols = $cols."<td>".utf8_decode($local)."</td>";

    	$html .=  $cols.'</tr>';  

    	$item+=1;                                          

    }

    $query_obs = "SELECT * FROM cotacoes_os WHERE fk_id_os_cotada = '$id_os'";
    $result_obs = $link->query($query_obs);
    $row_obs = mysqli_fetch_object($result_obs);
    $obs = $row_obs->obs_cotacao;

    $html .= '<tr>
                <td colspan="5"><center><b>Observacoes</b></center></td>      
              </tr>
            <tr>
               	<td colspan="5">'.utf8_decode($obs).'</td>   
            </tr>';

    $html .= '</table>';
	// Configurações header para forçar o download
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
	header ("Content-Description: PHP Generated Data" );
	// Envia o conteúdo do arquivo
	echo $html;
	exit;

}

?>