<?php

$arquivo = 'Planilha Biometrias.xls';

$html = '<style type="text/css">.text_justify{ text-align: justify;} .vertical{ vertical-align:middle;} </style>';
$html .= '<table border="1">';

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$query = "SELECT * FROM escola e, gre g WHERE e.serial_b != '' AND e.serial_b REGEXP '[0-9]' AND e.gre = g.id_gre";
$result = $link->query($query);

if(mysqli_num_rows($result) > 0){

    $html .='<tr>
                <td><center><font size="5"><b>SERIAL</b></font></center></td>
                <td><center><font size="5"><b>SEDE</b></font></center></td>
                <td><center><font size="5"><b>NOME ESCOLA</b></font></center></td>
            </tr>';

    while($row = mysqli_fetch_object($result)){

        
        $nome_escola = $row->nome_escola;
        $serial = $row->serial_b."_";
        $id_sede = $row->id_sede;

        if ($id_sede === '1') {
            $sede = "CG";
        }else if ($id_sede === '2') {
            $sede = "JP";
        }else if ($id_sede === '3') {
            $sede = "SS";
        }
        


        $html .=  "<tr>";	    
    		$cols = "";	
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$serial."</font></center></td>";	     
    		$cols = $cols."<td class='vertical'><center><font size='4'>".$sede."</font></center></td>";	    
    		$cols = $cols."<td class='text_justify'><font size='4'>".$nome_escola."</font></td>";	  	  	      		          
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