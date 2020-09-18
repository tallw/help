<?php

$arquivo = 'Planilha Tudao.xls';

$html = '<style type="text/css">.text_justify{ text-align: justify;} .vertical{ vertical-align:middle;} </style>';
$html .= '<table border="1">';

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$html .='<tr>
            <td colspan="12"><center><font size="7"><b>TUDÃO ECOS</b></font></center></td>      
        </tr>
        <tr>
            <td colspan="12"></td>      
        </tr>';

$query_escola = "SELECT * FROM escola ORDER BY gre";

$result_escola = $link->query($query_escola);

if(mysqli_num_rows($result_escola) > 0){

    while($row_escola = mysqli_fetch_object($result_escola)){

        $id_escola = $row_escola->id_escola;
        $gre = $row_escola->gre;
        $inep = $row_escola->inep;
        $nome_escola = $row_escola->nome_escola;
        $cidade = $row_escola->cidade; 

        $query_infra = "SELECT * FROM infraestrutura WHERE fk_id_escola = '$id_escola'";
        $result_infra = $link->query($query_infra);
        $row_infra = mysqli_fetch_object($result_infra);

        if (mysqli_num_rows($result_infra) > 0) {
            $mp = $row_infra->mnt_preventiva;
            $vt = $row_infra->vistoria;
            $int = $row_infra->internet;
            $ir = $row_infra->infra_rede;
            $lab = $row_infra->lab_info;
            $bio = $row_infra->biometria;
            $cam = $row_infra->camera;
            $sab = $row_infra->saber;
        }else{
            $mp = "O";
            $vt = "O";
            $int = "O";
            $ir = "O";
            $lab = "O";
            $bio = "O";
            $cam = "O";
            $sab = "O";
        }

        

        //echo "<script>alert('$mp')</script>";


        // bgcolor="#A4A4A4"

    	$html .='<tr>
            		<td colspan="12"></td>      
        		</tr>
        		<tr>
            		<td><center><font size="5"><b>GRE</b></font></center></td>
            		<td><center><font size="5"><b>INEP</b></font></center></td>
            		<td><center><font size="5"><b>NOME ESCOLA</b></font></center></td>
            		<td><center><font size="5"><b>CIDADE</b></font></center></td>

            		<td><center><font size="5"><b>Manutenção Preventiva</b></font></center></td>
            		<td><center><font size="5"><b>Vistoria Técnica</b></font></center></td>
            		<td><center><font size="5"><b>Internet</b></font></center></td>
                    <td><center><font size="5"><b>Infraestrutura Administrativa</b></font></center></td>
                    <td><center><font size="5"><b>Laboratório de Informática</b></font></center></td>
                    <td><center><font size="5"><b>Biometria</b></font></center></td>
                    <td><center><font size="5"><b>Possui Câmera</b></font></center></td>
                    <td><center><font size="5"><b>Saber Atualizado</b></font></center></td>      
        		</tr>';

        $html .=  "<tr>";	    
    		$cols = "";	
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$gre."</font></center></td>";	     
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$inep."</font></center></td>";	    
    		$cols = $cols."<td class='text_justify'><font size='4'>".$nome_escola."</font></td>";	  
    		$cols = $cols."<td class='text_justify'><font size='4'>".$cidade."</font></td>";	  	    
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$mp."</font></center></td>";	      
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$vt."</font></center></td>";	  	    
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$int."</font></center></td>";
            $cols = $cols."<td class='vertical'><center><font size='4'>".$ir."</font></center></td>";       
            $cols = $cols."<td class='vertical'><center><font size='4'>".$lab."</font></center></td>";         
            $cols = $cols."<td class='vertical'><center><font size='4'>".$bio."</font></center></td>";
            $cols = $cols."<td class='vertical'><center><font size='4'>".$cam."</font></center></td>";         
            $cols = $cols."<td class='vertical'><center><font size='4'>".$sab."</font></center></td>";	  		          
    		$html .=  $cols.'</tr>';

    	$query_os = "SELECT o.id_os, o.protocolo, o.dt_abertura, o.dt_conclusao, sm.sub_motivo, o.pre_venda, o.pos_venda FROM ordem_servico o, sub_motivo_chamado sm WHERE o.fk_id_motivo_os = sm.id_sub_motivo AND o.fk_id_nome_escola = '$id_escola' ORDER BY o.dt_abertura";

        $result_os = $link->query($query_os);

        if(mysqli_num_rows($result_os) > 0){

            while($row_os = mysqli_fetch_object($result_os)){

                $id_os = $row_os->id_os;
                $protocolo = $row_os->protocolo;
                $dt_abertura = $row_os->dt_abertura;
                $dt_abertura = explode(' ', $dt_abertura)[0];
                $dt_conclusao = $row_os->dt_conclusao;
                $dt_conclusao = explode(' ', $dt_conclusao)[0];
                $motivo = $row_os->sub_motivo;
                $pre_venda = $row_os->pre_venda;
                $pos_venda = $row_os->pos_venda;

                $html .='<tr>
            				<td colspan="12"></td>      
        				</tr>
        				<tr>
            				<td>-</td>
            				<td ><center><font size="5"><b>CHAMADO</b></font></center></td>
            				<td colspan="2"><center><font size="5"><b>ABERTURA</b></font></center></td>
            				<td colspan="2"><center><font size="5"><b>CONCLUSAO</b></font></center></td>
            				<td colspan="2"><center><font size="5"><b>MOTIVO</b></font></center></td>
            				<td colspan="2"><center><font size="5"><b>PRE_VENDA</b></font></center></td>
            				<td colspan="2"><center><font size="5"><b>POS_VENDA</b></font></center></td>      
        				</tr>';

        		$html .=  "<tr>";	    
    			$cols = "";	
    			$cols = $cols."<td>-</td>";	     
    			$cols = $cols."<td class='vertical'><center><font size='4'>".$protocolo."</font></center></td>";	    
    			$cols = $cols."<td colspan='2' class='vertical'><center><font size='4'>".$dt_abertura."</font></center></td>";	  
    			$cols = $cols."<td colspan='2' class='vertical'><center><font size='4'>".$dt_conclusao."</font></center></td>";	  	    
    			$cols = $cols."<td colspan='2' class='text_justify'><font size='4'>".$motivo."</font></td>";	      
    			$cols = $cols."<td colspan='2' class='text_justify'><font size='4'>".$pre_venda."</font></td>";	  	    
    			$cols = $cols."<td colspan='2' class='text_justify'><font size='4'>".$pos_venda."</font></td>";	  		          
    			$html .=  $cols.'</tr>';

    			$query_execs = "SELECT * FROM execucao_diaria WHERE fk_id_ordem_servico = '$id_os' ORDER BY data_execucao";

                $result_execs = $link->query($query_execs);

                if(mysqli_num_rows($result_execs) > 0){

                    while($row_exec = mysqli_fetch_object($result_execs)){

                        $data_execucao = $row_exec->data_execucao;
                        $data_execucao = explode(' ', $data_execucao)[0];
                        $relato = $row_exec->relato;
                        $pendencias = $row_exec->pendencias;
                    
                        $html .='<tr>
            						<td colspan="12"></td>      
        						</tr>
        						<tr>
            						<td>-</td>
            						<td>-</td>
            						<td colspan="2"><center><font size="5"><b>DATA</b></font></center></td>
            						<td colspan="2"><center><font size="5"><b>RELATO</b></font></center></td>
            						<td colspan="2"><center><font size="5"><b>PENDÊNCIA</b></font></center></td>
            						<td colspan="2">-</td>
            						<td colspan="2">-</td>      
        						</tr>';

        				$html .=  "<tr>";	    
    					$cols = "";	
    					$cols = $cols."<td>-</td>";	     
    					$cols = $cols."<td>-</td>";	    
    					$cols = $cols."<td colspan='2' class='vertical'><center><font size='4'>".$data_execucao."</font></center></td>";	  
    					$cols = $cols."<td colspan='2' class='text_justify'><font size='4'>".$relato."</font></td>";	  	    
    					$cols = $cols."<td colspan='2' class='text_justify'><font size='4'>".$pendencias."</font></td>";	      
    					$cols = $cols."<td colspan='2'>-</td>";	  	    
    					$cols = $cols."<td colspan='2'>-</td>";	  		          
    					$html .=  $cols.'</tr>';

                    }
                }

            }
        }

    }
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
?>