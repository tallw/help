<?php

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$id_bio = $_GET['id_bio'];

$serial = mysqli_fetch_object($link->query("SELECT * FROM biometrias WHERE id_biometria = '$id_bio'"))->serial_bio;

$arquivo = 'Historico Biometria.xls';

$html = '<style type="text/css">.text_justify{ text-align: justify;} .vertical{ vertical-align:middle;} </style>';
$html .= '<table border="1">';

$html .='<tr>
            <td colspan="12"><center><font size="7"><b>Historico Biometria: '.$serial.'</b></font></center></td>      
        </tr>
        <tr>
            <td colspan="12"></td>      
        </tr>';

$query_history = "SELECT * FROM history_serial WHERE fk_id_serial = '$id_bio' ORDER BY data_mudanca";

$result_history = $link->query($query_history);

if(mysqli_num_rows($result_history) > 0){

    $html .='<tr>
                <td colspan="2"><center><font size="5"><b>Data</b></font></center></td>
                <td colspan="2"><center><font size="5"><b>Sede</b></font></center></td>
                <td colspan="8"><center><font size="5"><b>Relato</b></font></center></td>
            </tr>';

    while($row_history = mysqli_fetch_object($result_history)){

        
        $data = $row_history->data_mudanca;
        $id_sede = $row_history->fk_sede_bio;
        if ($id_sede === '0') {
            $sede = "sem sede...";
        }else if ($id_sede === '1') {
            $sede = "CG";
        }else if ($id_sede === '2') {
            $sede = "JP";
        }else if ($id_sede === '3') {
            $sede = "SS";
        }
        $status_history = $row_history->fk_status_bio;

        if ($status_history === '1') {
            $relato_history = "Biometria enviada para o Estoque.";
        }else if ($status_history === '2') {
            $id_escola = $row_history->fk_id_escola_serial;
            $escola_history = mysqli_fetch_object($link->query("SELECT * FROM escola WHERE id_escola = '$id_escola'"))->nome_escola;
            $relato_history = "Biometria enviada para a Escola: .".$escola_history;
        }else if ($status_history === '3') {
            $relato_history = "Biometria enviada para a Sede JP com Defeito.";
        }else if ($status_history === '4') {
            $relato_history = "Biometria enviada para Garantia (Defeito).";
        }
        
        


        $html .=  "<tr>";	    
    		$cols = "";	
    		$cols = $cols."<td colspan='2' class='vertical'><center><font size='4'>".$data."</font></center></td>";	     
    		$cols = $cols."<td colspan='2' class='vertical'><center><font size='4'>".$sede."</font></center></td>";	    
    		$cols = $cols."<td colspan='8' class='text_justify'><font size='4'>".$relato_history."</font></td>";	    	      		          
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