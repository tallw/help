<?php

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');


if(isset($_POST['c_id'])) {

    
    $id_escola = $_POST['c_id'];
    
	

	$query_dados_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'";
    $result_get_escola = $link->query($query_dados_escola);
       
	if(mysqli_num_rows($result_get_escola) > 0){

		$row_escola = mysqli_fetch_object($result_get_escola);

        $query_infra = "SELECT * FROM `infraestrutura` WHERE fk_id_escola = '$id_escola'";
        $result_infra = $link->query($query_infra);
        $row_infra = mysqli_fetch_object($result_infra);

                        
        echo '<table class="table table-striped table-bordered table-hover">
                <tr>
                    <td><b>Escola: </b></td>
                    <td colspan="7">'.$row_escola->nome_escola.'</td>                                
                </tr>
                <tr>
                    <td colspan="1"><b>GRE: </b></td>
                    <td colspan="2">'.$row_escola->gre."ª GRE".'</td>
                    <td colspan="1"><b>Cidade: </b></td>
                    <td colspan="4">'.$row_escola->cidade.'</td>
                </tr>
                <tr>
                    <td colspan="1"><b>Endereço: </b></td>
                    <td colspan="7">'.$row_escola->endereco.'</td>     
                </tr>
                <tr>
                    <td colspan="1"><b>Responsável: </b></td>
                    <td colspan="3">'.$row_escola->responsavel.'</td>
                    <td colspan="1"><b>Contatos: </b></td>
                    <td colspan="3">'.$row_escola->contato01.'</td>
                </tr>
                <tr>
                    <td colspan="1"><b>E-mails: </b></td>
                    
                    <td colspan="7">'.$row_escola->email.'</td>
                </tr>
                <tr>
                    <td colspan="8"><center><b>CheckList Infraestrutura: </b></center></td>                      
                </tr>';

                $id_biometria = $row_escola->fk_id_biometria;

                if ($id_biometria === '0') {
                    $serial_bio = "Não possui...";
                }else{
                    $serial_bio = mysqli_fetch_object($link->query("SELECT * FROM biometrias WHERE id_biometria = '$id_biometria'"))->serial_bio;
                }

                $labels = array('MP', 'VT', 'I', 'IR', 'B', 'LI','CA','SA');
                $titulos = array('Manutenção Preventiva: ', 'Vistoria Técnica: ', 'Internet: ', 'Infraestrutura Administrativa: ', 'Biometria: '.$serial_bio, 'Laboratório de Informática: ', 'Possui Câmera: ', 'Saber Atualizado: ');
                $radios = array($row_infra->mnt_preventiva, $row_infra->vistoria, $row_infra->internet, $row_infra->infra_rede, $row_infra->biometria, $row_infra->lab_info, $row_infra->camera, $row_infra->saber);
                $total = 0;

                for ($i=0; $i < count($radios); $i= $i+=4) { 
                    echo '<tr>';
                    echo '<td><b>'.$titulos[$i].'</b></td>';
                    if ($radios[$i] === 'X') {
                        echo '<td ><p><input class="filled-in" type="checkbox" id="'.$labels[$i].'" checked="checked" disabled="disabled" /><label for="'.$labels[$i].'"> </label></p></td>';
                        $total++;
                    }else{
                        echo '<td ><p><input class="filled-in" type="checkbox" id="'.$labels[$i].'" disabled="disabled" /><label for="'.$labels[$i].'"> </label></p></td>';
                    }
                    echo '<td><b>'.$titulos[$i+1].'</b></td>';
                    if ($radios[$i+1] === 'X') {
                        echo '<td ><p><input class="filled-in" type="checkbox" id="'.$labels[$i+1].'" checked="checked" disabled="disabled" /><label for="'.$labels[$i+1].'"> </label></p></td>';
                        $total++;
                    }else{
                        echo '<td ><p><input class="filled-in" type="checkbox" id="'.$labels[$i+1].'" disabled="disabled" /><label for="'.$labels[$i+1].'"> </label></p></td>';
                    }
                    echo '<td><b>'.$titulos[$i+2].'</b></td>';
                    if ($radios[$i+2] === 'X') {
                        echo '<td ><p><input class="filled-in" type="checkbox" id="'.$labels[$i+2].'" checked="checked" disabled="disabled" /><label for="'.$labels[$i+2].'"> </label></p></td>';
                        $total++;
                    }else{
                        echo '<td ><p><input class="filled-in" type="checkbox" id="'.$labels[$i+2].'" disabled="disabled" /><label for="'.$labels[$i+2].'"> </label></p></td>';
                    }
                    echo '<td><b>'.$titulos[$i+3].'</b></td>';
                    if ($radios[$i+3] === 'X') {
                        echo '<td ><p><input class="filled-in" type="checkbox" id="'.$labels[$i+3].'" checked="checked" disabled="disabled" /><label for="'.$labels[$i+3].'"> </label></p></td>';
                        $total++;
                    }else{
                        echo '<td ><p><input class="filled-in" type="checkbox" id="'.$labels[$i+3].'" disabled="disabled" /><label for="'.$labels[$i+3].'"> </label></p></td>';
                    }

                    echo '<tr>';
                }

                $tit_qtdes = array('Qtde Labs Info', 'Qtde PCs', 'Qtde PCs ATV', 'Qtde PCs OFF', 'Qtde PCs Labs Info', 'Qtde PCs ATV LAbs Info', 'Qtde PCs OFF LAbs Info');
                $qtde_labs_info = $row_infra->qtde_labs_info;
                $qtde_pcs = $row_infra->qtde_pcs;
                $qtde_pcs_atv = $row_infra->qtde_pcs_atv;
                $qtde_pcs_off = $qtde_pcs - $qtde_pcs_atv;
                $qtde_pcs_labs_info = $row_infra->qtde_pcs_labs_info;
                $qtde_pcs_labs_info_atv = $row_infra->qtde_pcs_labs_info_atv;
                $qtde_pcs_labs_info_off = $qtde_pcs_labs_info - $qtde_pcs_labs_info_atv;
                $qtdes = array($qtde_labs_info, $qtde_pcs, $qtde_pcs_atv, $qtde_pcs_off, $qtde_pcs_labs_info, $qtde_pcs_labs_info_atv, $qtde_pcs_labs_info_off);

                echo '<tr style="color: #FF8000"><td><b>'.$tit_qtdes[0].':</b></td><td><center>'.$qtdes[0].'</center></td><td><b>'.$tit_qtdes[1].':</b></td><td><center>'.$qtdes[1].'</center></td><td><b>'.$tit_qtdes[2].':</b></td><td><center>'.$qtdes[2].'</center></td><td><b>'.$tit_qtdes[3].':</b></td><td><center>'.$qtdes[3].'</center></td></tr>';
                echo '<tr style="color: #FF8000"><td><b>'.$tit_qtdes[4].':</b></td><td><center>'.$qtdes[4].'</center></td><td><b>'.$tit_qtdes[5].':</b></td><td><center>'.$qtdes[5].'</center></td><td><b>'.$tit_qtdes[6].':</b></td><td><center>'.$qtdes[6].'</center></td><td><b></b></td><td><center></center></td></tr>';

                $percentual = number_format(($total/8)*100, 1, ',', ' ');

                echo '  <tr>
                            <td colspan="2" style="color:red"><center><b>STATUS:<b> '.$percentual.' %</center></td>
                            <td colspan="3"><center><a href="setor_edita_escola.php?id_escola='.$id_escola.'"  class="waves-effect waves-light btn custom-back">Modificar CheckList Escola</a></center></td>
                            <td colspan="3"><center><a href="rel_obra/gera_capa_escola.php?id_escola='.$id_escola.'" target="_blank" class="waves-effect red btn custom-back">Capa Rel Escola</a></center></td>                                
                        </tr>   ';
                

                
             echo '</table>';  
                                                        
        
	} else {
		echo "<h4>Não existe histórico da escola selecionada...</h4>";
	}
} else {
	header('location: ./');
} 


?>


    