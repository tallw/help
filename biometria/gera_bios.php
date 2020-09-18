<?php

$arquivo = 'Planilha Biometrias.xls';

$html = '<style type="text/css">.text_justify{ text-align: justify;} .vertical{ vertical-align:middle;} </style>';
$html .= '<table border="1">';

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$html .='<tr>
            <td colspan="12"><center><font size="7"><b>Biometrias Atuais</b></font></center></td>      
        </tr>
        <tr>
            <td colspan="12"></td>      
        </tr>';

$query_escola = "SELECT * FROM escola ORDER BY gre";

$result_escola = $link->query($query_escola);

if(mysqli_num_rows($result_escola) > 0){

    $html .='<tr>
                <td colspan="2"><center><font size="5"><b>GRE</b></font></center></td>
                <td colspan="2"><center><font size="5"><b>INEP</b></font></center></td>
                <td colspan="5"><center><font size="5"><b>NOME ESCOLA</b></font></center></td>
                <td colspan="2"><center><font size="5"><b>SERIAL BIOMETRIA</b></font></center></td>
                <td colspan="1"><center><font size="5"><b>1&ordm; OS</b></font></center></td>     
            </tr>';

    while($row_escola = mysqli_fetch_object($result_escola)){

        
        $gre = $row_escola->gre;
        $id_escola = $row_escola->id_escola;
        $inep = $row_escola->inep;
        $nome_escola = utf8_decode($row_escola->nome_escola);
        $id_serial = $row_escola->fk_id_biometria; 

        $query_bio = "SELECT * FROM biometrias WHERE id_biometria = '$id_serial'";
        $result_bio = $link->query($query_bio);
        if (mysqli_num_rows($result_bio) > 0) {
             $serial = mysqli_fetch_object($result_bio)->serial_bio."_";
        }else{
            $serial = utf8_decode("Não possui");
        }
       

        $query_os = "SELECT * from ordem_servico where fk_id_nome_escola = '$id_escola' and fk_id_motivo_os = 16";
        $result_os = $link->query($query_os);

        if (mysqli_num_rows($result_os) > 0) {
            $row_os = mysqli_fetch_object($result_os);
            $os = $row_os->protocolo;
        }else{
            $os = '';
        }
        


        $html .=  "<tr>";	    
    		$cols = "";	
    		$cols = $cols."<td colspan='2' class='vertical'><center><font size='4'>".$gre."&ordm; GRE</font></center></td>";	     
    		$cols = $cols."<td colspan='2' class='vertical'><center><font size='4'>".$inep."</font></center></td>";	    
    		$cols = $cols."<td colspan='5' class='text_justify'><font size='4'>".$nome_escola."</font></td>";	  
    		$cols = $cols."<td colspan='2' class='text_justify'><font size='4'>".$serial."</font></td>";
            $cols = $cols."<td colspan='1' class='text_justify'><font size='4'>".$os."</font></td>";	  	      		          
    		$html .=  $cols.'</tr>';

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