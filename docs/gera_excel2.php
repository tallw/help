<?php
/*
* Criando e exportando planilhas do Excel
* /
*/
// Definimos o nome do arquivo que será exportado 
$arquivo = 'Relatorio SLA por GRE(';
// Criamos uma tabela HTML com o formato da planilha
$html = '';
$html .= '<table border="1">';

require_once("../config/db.php");
include("../libraries/functions.php");

include("../libraries/feriado.php");

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$dt_inicial = $_POST['inicial'];
$dt_final = $_POST['final'];

$arquivo.=$dt_inicial." à ".$dt_final.").xls";

if($dt_inicial != "" && $dt_final != "") {

	$meses_index = get_meses_index($dt_inicial, $dt_final);

	$msg = "";
	$meses = array('Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez');

	
	//$tde =mysqli_num_rows($result_OSs);

	//echo "<script>alert('Aqui $tde')</script>";

	$html .= '<tr>
                <td rowspan="3"></td>
                <td colspan="4"><b>-</b></td>
                <td colspan="4"><center><b>CHAMADO</b></center></td>      
            </tr>
            <tr>
               	<td rowspan="2"><center><b>ESC ATEND.</b></center></td>
                <td rowspan="2"><center><b>N&ordm; O.S.</b></center></td>
              	<td rowspan="2"><center><b>PROGRAM.</b></center></td>
                <td rowspan="2"><center><b>CHAMADOS</b></center></td>
                <td colspan="2"><center><b>72 HRS(Atend.)</b></center></td>
                <td colspan="2"><center><b>15DD(Resolu&ccedil;&atilde;o)</b></center></td>  
            </tr>
            <tr>
               	<td><center><b>&lt; 3 DD</b></center></td>
                <td><center><b>&gt; 3 DD</b></center></td>
                <td><center><b>&lt; 15 DD</b></center></td>
                <td><center><b>&gt; 15 DD</b></center></td>
            </tr>';



    foreach ($meses_index as $key => $value) {
		

		$mes_val = explode("#", $value)[0] + 1;
		$ano_val = explode("#", $value)[1];


		// #################################################################################### Inicio consultas rapidas ########################################################################################

		// por parametro em todas no lugar do numero da sede em todos

		$TOT_ESC_ATEND = 0;
		$TOT_Num_O_S = 0;
		$TOT_PROGRAM = 0;
		$TOT_CHAMADOS =	0;

		$TOT_DD72_HRS_Atend_ok = 0;
		$TOT_HRS72_Atend_off = 0;
		$TOT_DD15_Resolucao_ok = 0;
		$TOT_DD15_Resolucao_off = 0;

		$sedes = array('1ª GRE','2ª GRE','3ª GRE','4ª GRE','5ª GRE','6ª GRE','7ª GRE','8ª GRE','9ª GRE','10ª GRE','11ª GRE','12ª GRE','13ª GRE','14ª GRE');

		for ($i=1; $i < 15; $i++) { 

			$ESC_ATEND = mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT O.fk_id_nome_escola from ordem_servico O, escola e where O.status = 3 and e.id_escola = O.fk_id_nome_escola and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and e.gre = '$i'  and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val'"));

			$Num_O_S = mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT O.id_os from ordem_servico O, escola e where O.status = 3 and e.id_escola = O.fk_id_nome_escola and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and e.gre = '$i'  and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val'"));

			$PROGRAM = mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT O.id_os from ordem_servico O, escola e where O.status = 3 and e.id_escola = O.fk_id_nome_escola and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and e.gre = '$i' and O.tipo_chamado = 1 and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val'"));

			$CHAMADOS =	mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT O.id_os from ordem_servico O, escola e where O.status = 3 and e.id_escola = O.fk_id_nome_escola and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and e.gre = '$i' and O.tipo_chamado = 0 and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val'"));


			//############################################################################################## fim consultas rápidas ##################################################################################

			//##################################### Inicio consultas complexas

			$DD72_HRS_Atend_ok = mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT O.id_os from ordem_servico O, escola e where O.status = 3 and e.id_escola = O.fk_id_nome_escola and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and e.gre = '$i'  and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val' and O.sla_atendimento < 4 and O.tipo_chamado = 0"));

			$HRS72_Atend_off = mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT O.id_os from ordem_servico O, escola e  where O.status = 3 and e.id_escola = O.fk_id_nome_escola and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and e.gre = '$i'  and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val' and O.sla_atendimento > 3 and O.tipo_chamado = 0"));

			$DD15_Resolucao_ok = mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT O.id_os from ordem_servico O, escola e where O.status = 3 and e.id_escola = O.fk_id_nome_escola and e.gre = '$i'  and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val' and O.sla_conclusao < 16 and O.tipo_chamado = 0"));

			$DD15_Resolucao_off = mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT O.id_os from ordem_servico O, escola e where O.status = 3 and e.id_escola = O.fk_id_nome_escola and e.gre = '$i'  and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val' and O.sla_conclusao > 15 and O.tipo_chamado = 0"));

			// ############################## Perguntar a david se essa data da execucao é a primeira

			//##################################### FIM consultas complexas

			$html .=  "<tr>";	    
    		$cols = "";	

    		$cols = $cols."<td>".$meses[$mes_val-1]."/".$ano_val."/".$sedes[$i-1]."</td>";	    
    		$cols = $cols."<td>".$ESC_ATEND."</td>";	     
    		$cols = $cols."<td>".$Num_O_S."</td>";	  	    
    		$cols = $cols."<td>".$PROGRAM."</td>";	  
    		$cols = $cols."<td>".$CHAMADOS."</td>";	  	    
    		$cols = $cols."<td>".$DD72_HRS_Atend_ok."</td>";	      
    		$cols = $cols."<td>".$HRS72_Atend_off."</td>";	  	    
    		$cols = $cols."<td>".$DD15_Resolucao_ok."</td>";	  		    
    		$cols = $cols."<td>".$DD15_Resolucao_off."</td>";	      
    		$html .=  $cols.'</tr>';

    		$TOT_ESC_ATEND += $ESC_ATEND;
			$TOT_Num_O_S += $Num_O_S;
			$TOT_PROGRAM += $PROGRAM;
			$TOT_CHAMADOS += $CHAMADOS;

			$TOT_DD72_HRS_Atend_ok += $DD72_HRS_Atend_ok; 
			$TOT_HRS72_Atend_off += $HRS72_Atend_off;
			$TOT_DD15_Resolucao_ok += $DD15_Resolucao_ok;
			$TOT_DD15_Resolucao_off += $DD15_Resolucao_off;
			
		}

		$html .=  "<tr>";	    
    		$cols = "";	

    		$cols = $cols."<td bgcolor='yellow'>TOTAL</td>";	    
    		$cols = $cols."<td bgcolor='yellow'>".$TOT_ESC_ATEND."</td>";	     
    		$cols = $cols."<td bgcolor='yellow'>".$TOT_Num_O_S."</td>";	  	    
    		$cols = $cols."<td bgcolor='yellow'>".$TOT_PROGRAM."</td>";	  
    		$cols = $cols."<td bgcolor='yellow'>".$TOT_CHAMADOS."</td>";	  	    
    		$cols = $cols."<td bgcolor='yellow'>".$TOT_DD72_HRS_Atend_ok."</td>";	      
    		$cols = $cols."<td bgcolor='yellow'>".$TOT_HRS72_Atend_off."</td>";	  	    
    		$cols = $cols."<td bgcolor='yellow'>".$TOT_DD15_Resolucao_ok."</td>";	  		    
    		$cols = $cols."<td bgcolor='yellow'>".$TOT_DD15_Resolucao_off."</td>";	      
    		$html .=  $cols.'</tr>';

		

		

	}

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

} else {
	header('location: ../rel_quantitativo.php');
} 
?>