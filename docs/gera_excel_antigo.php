<?php
/*
* Criando e exportando planilhas do Excel
* /
*/
// Definimos o nome do arquivo que será exportado 
$arquivo = 'planilha.xls';
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

		$sedes = array('JP', 'CG', 'SS');

		for ($i=1; $i < 4; $i++) { 

			$ESC_ATEND = mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT O.fk_id_nome_escola from ordem_servico O where O.status = 3 and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and O.fk_id_sede = '$i'  and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val'"));
		
			$Num_O_S = mysqli_num_rows(mysqli_query($link, "SELECT * from ordem_servico O where O.status = 3 and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and O.fk_id_sede = '$i'  and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val'"));

			$PROGRAM = mysqli_num_rows(mysqli_query($link, "SELECT * from ordem_servico O where O.status = 3 and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and O.fk_id_sede = '$i' and O.tipo_chamado = 1 and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val'"));

			$CHAMADOS =	mysqli_num_rows(mysqli_query($link, "SELECT * from ordem_servico O where O.status = 3 and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final') and O.fk_id_sede = '$i' and O.tipo_chamado = 0 and MONTH(O.dt_conclusao) = '$mes_val' and Year(O.dt_conclusao) = '$ano_val'"));


			//############################################################################################## fim consultas rápidas ##################################################################################

			//##################################### Inicio consultas complexas

			$DD72_HRS_Atend_ok = 0;
			$HRS72_Atend_off = 0;
			$DD15_Resolucao_ok = 0;
			$DD15_Resolucao_off = 0;

			$result_OSs = mysqli_query($link, "SELECT * from ordem_servico O where O.fk_id_sede = '$i' and O.status > 1 and (O.dt_conclusao BETWEEN '$dt_inicial' and '$dt_final')");

			while($row_os = mysqli_fetch_object($result_OSs)){

				//#################################################################### SLA ATENDIMENTO #########################################################

				$data_1 = explode(' ', $row_os->dt_abertura);
				$data = $data_1[0];

				$ano = explode('-', $data)[0];
        		$mes = explode('-', $data)[1];
        		$dia = explode('-', $data)[2];
 
        		$temp = $dia.'/'.$mes.'/'.$ano;

        		
        		//echo "<script>alert('$temp')</script>";

        		$dt_1exec_os_result = mysqli_query($link, "SELECT EXEC.data_execucao from ordem_servico O, execucao_diaria EXEC where O.id_os = '$row_os->id_os' and EXEC.fk_id_ordem_servico = '$row_os->id_os'");

        		if (mysqli_num_rows($dt_1exec_os_result) > 0) {

        			$dt_sla_aten = somar_dias_uteis($temp,'4','');// faltam 2 dias para vencer

        			$dt_1exec_os = mysqli_fetch_object($dt_1exec_os_result)->data_execucao;

        			//echo "<script>alert('Mes1exec $dt_1exec_os temp $temp')</script>";

        			$ano_1exec = explode('-', $dt_1exec_os)[0];
        			$mes_1exec = explode('-', $dt_1exec_os)[1];

        		

        			if (($ano_1exec == $ano_val) && ($mes_1exec == $mes_val)) {
        				//echo "<script>alert('Aqui heh')</script>";
        				if (($dt_1exec_os >= $row_os->dt_abertura) && ($dt_1exec_os <= $dt_sla_aten)) { // e sede == sede_parametro
        					
							$DD72_HRS_Atend_ok += 1;
						}else{
							//echo "<script>alert('DT-1EXEC  $dt_1exec_os DT-AB $dt_abertura DT-SLA $dt_sla_aten')</script>";
							$HRS72_Atend_off+=1;
						
        				}
        			}
        		}
        	
        		
        	
        		//#################################################################### SLA CONCLUSAO #########################################################

        		if ($row_os->status === '3') {
        			$data_1 = explode(' ', $row_os->dt_conclusao);
					$data = $data_1[0];

					$ano = explode('-', $data)[0];
        			$mes = explode('-', $data)[1];
        			$dia = explode('-', $data)[2];
 
        			$temp = $dia.'/'.$mes.'/'.$ano;

        			$dt_sla_conc = somar_dias_uteis($temp,'16','');// faltam 15 dias para vencer

        			//echo "<script>alert('$temp')</script>";
	
        			//echo "<script>alert('Mes1exec $dt_1exec_os temp $temp')</script>";

        			$ano_conc = explode('-', $row_os->dt_conclusao)[0];
        			$mes_conc = explode('-', $row_os->dt_conclusao)[1];

        		

        			if (($ano_conc == $ano_val) && ($mes_conc == $mes_val)) {
        				//echo "<script>alert('Aqui heh')</script>";
        				if ($row_os->dt_conclusao <= $dt_sla_conc) { // e sede == sede_parametro
        					//echo "<script>alert('ID $row_os->id_os')</script>";
							$DD15_Resolucao_ok += 1;
						}else{
							$DD15_Resolucao_off+=1;
						}
        			}
        		}

        		
        	}

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