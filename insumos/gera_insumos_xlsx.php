<?php

$id_cot_ins = $_GET['id_cot_ins'];

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

if (!$link){

    die('Connect Error (' . mysqli_connecterrno() . ')' .mysqli_connect_error());

}else{

    $query_cot_ins = "SELECT * FROM cotacoes_insumos WHERE id_cot_insumos = '$id_cot_ins'";
    $result_cot_ins = $link->query($query_cot_ins);
    $row_cot_ins = mysqli_fetch_object($result_cot_ins);
    $obs_cotacao = $row_cot_ins->obs_cotacao;
    $dt_cot_insumos = $row_cot_ins->dt_cot_insumos;

	
	$arquivo = 'Cotacao Insumos: '.$id_cot_ins.'.xls';

	$html = '';
	$html .= '<table border="1">';
	$html .= '<tr>
                <td colspan="5"><center><b>Cotacao de Insumos Data: '.$dt_cot_insumos.'</b></center></td>      
              </tr>
            <tr>
               	<td><center><b>Item</b></center></td>
                <td><center><b>Nome Item</b></center></td>
              	<td><center><b>Quantidade</b></center></td>
                <td><center><b>Referencia</b></center></td>
                <td><center><b>Destino</b></center></td>
            </tr>';

    

    $query_itens = "SELECT itdc.nome_insumo nome, itc.qtde_insumo qtde, itc.referencia_insumo ref, itc.destino_insumo loc FROM insumos_cotados itc, insumos itdc WHERE itc.fk_id_cot_insumo = '$id_cot_ins' and itc.fk_id_insumo = itdc.id_insumo order by itdc.nome_insumo";
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
            $local = 'SEDE JP';
        }else if($row_itens->loc === '2'){
            $local = 'SEDE CG';
        }else{
            $local = 'SEDE SS';
        }
        $cols = $cols."<td>".utf8_decode($local)."</td>";

    	$html .=  $cols.'</tr>';  

    	$item+=1;                                          

    }

    
    $html .= '<tr>
                <td colspan="5"><center><b>Observacoes:</b></center></td>      
              </tr>
            <tr>
               	<td colspan="5">'.utf8_decode($obs_cotacao).'</td>   
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