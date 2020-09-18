<?php

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$arquivo = 'biometrias_tudao.xls';

$html = '<style type="text/css">.text_justify{ text-align: justify;} .vertical{ vertical-align:middle;} </style>';
$html .= '<table border="1">';



$html .='<tr>
				<td ><center><font size="5"><b>SERIAL</b></font></center></td>
                <td ><center><font size="5"><b>STATUS</b></font></center></td>
                <td ><center><font size="5"><b>INEP</b></font></center></td>
                <td ><center><font size="5"><b>ESCOLA</b></font></center></td>
                <td ><center><font size="5"><b>SEDE</b></font></center></td>
            </tr>';

$query_bios = "SELECT * FROM biometrias";

$result_bios = $link->query($query_bios) or die(mysqli_error($link));

if (mysqli_num_rows($result_bios) > 0) {
        
    while($row = mysqli_fetch_object($result_bios)) {

    	$id_biometria = $row->id_biometria;
        $serial_bio = $row->serial_bio;
        $status_bio = $row->status_bio;
        $sede_bio = $row->sede_bio;

        $inep = "-";
        $escola = "-";

        $text_status = "";
        if ($status_bio == 1) {
        	$text_status = "ESTOQUE";
        }else if($status_bio == 2) {
        	$text_status = "ESCOLA";
        	$query_escola = "SELECT * FROM escola WHERE fk_id_biometria = '$id_biometria'";
        	$result_escola = $link->query($query_escola) or die(mysqli_error($link));
        	$row_escola = mysqli_fetch_object($result_escola);
        	$inep = $row_escola->inep;
        	$escola = utf8_decode($row_escola->nome_escola);
        }else if($status_bio == 3) {
        	$text_status = "DEFEITO (JP)";
        }else if($status_bio == 4) {
        	$text_status = "CONTROL ID";
        }

        $text_sede = "";
        if ($sede_bio == 1) {
        	$text_sede = "CG";
        }else if($sede_bio == 2) {
        	$text_sede = "JP";
        }else if($sede_bio == 3) {
        	$text_sede = "SS";
        }

        $html .=  "<tr>";	    
    		$cols = "";	
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$serial_bio."</font></center></td>";
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$text_status."</font></center></td>";
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$inep."</font></center></td>";
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$escola."</font></center></td>";	         
    		$cols = $cols."<td class='text_justify'><font size='4'>".$text_sede."</font></td>";	    	      		          
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