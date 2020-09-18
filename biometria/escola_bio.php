<?php

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');

$arquivo = 'relatorio_escola_bio.xls';

$html = '<style type="text/css">.text_justify{ text-align: justify;} .vertical{ vertical-align:middle;} </style>';
$html .= '<table border="1">';



$html .='<tr>
				<td ><center><font size="5"><b>INEP</b></font></center></td>
                <td ><center><font size="5"><b>ESCOLA</b></font></center></td>
                <td ><center><font size="5"><b>BIOMETRIA</b></font></center></td>
            </tr>';

$query_escola = "SELECT * FROM escola";

$result_escola = $link->query($query_escola) or die(mysqli_error($link));

if (mysqli_num_rows($result_escola) > 0) {
        
    while($row = mysqli_fetch_object($result_escola)) {

        $inep = $row->inep;
        $nome_escola = utf8_decode($row->nome_escola);
        $id_biometria = $row->fk_id_biometria;

        $query_bio = "SELECT * FROM biometrias WHERE id_biometria = '$id_biometria'";
        $result_bio = $link->query($query_bio) or die(mysqli_error($link));

        $biometria = "Não possui";

        if (mysqli_num_rows($result_bio) > 0) {

            $biometria = mysqli_fetch_object($result_bio)->serial_bio;
        }

        $html .=  "<tr>";	    
    		$cols = "";	
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$inep."</font></center></td>";
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$nome_escola."</font></center></td>";
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$biometria."</font></center></td>";    	      		          
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