<?php

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$arquivo = 'escolas.xls';

$html = '<style type="text/css">.text_justify{ text-align: justify;} .vertical{ vertical-align:middle;} </style>';
$html .= '<table border="1">';



$html .='<tr>
                <td ><center><font size="5"><b>INEP</b></font></center></td>
                <td ><center><font size="5"><b>SERIAL</b></font></center></td>
                <td ><center><font size="5"><b>SEDE</b></font></center></td>
            </tr>';

$query_bios = "SELECT * FROM escola e, gre g WHERE e.serial_b != '' AND e.serial_b REGEXP '[0-9]' AND e.gre = g.id_gre";

$result_bios = $link->query($query_bios) or die(mysqli_error($link));

if (mysqli_num_rows($result_bios) > 0) {
        
    while($row = mysqli_fetch_object($result_bios)) {
            
        $serial_b = $row->serial_b;
        $nome_escola = $row->nome_escola;
        $id_sede = $row->id_sede;

        if ($id_sede === '0') {
            $sede = "sem sede...";
        }else if ($id_sede === '1') {
            $sede = "CG";
        }else if ($id_sede === '2') {
            $sede = "JP";
        }else if ($id_sede === '3') {
            $sede = "SS";
        }

        $html .=  "<tr>";	    
    		$cols = "";	
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$nome_escola."</font></center></td>";	     
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$serial_b."</font></center></td>";	    
    		$cols = $cols."<td class='text_justify'><font size='4'>".$sede."</font></td>";	    	      		          
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