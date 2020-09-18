<?php

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');


?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="dist/vertical-timeline.js"></script>
<script>
    $('#vt1').verticalTimeline();
    $('#vt2').verticalTimeline();
    $('#vt3').verticalTimeline({
        startLeft: false
    });
    $('#vt4').verticalTimeline({
        startLeft: false,
        arrows: false,
        alternate: false

    });
    $('#vt5').verticalTimeline({
        animate: 'fade'
    });
    $('#vt6').verticalTimeline({
        animate: 'slide'
    });
</script>




<?php


if(isset($_POST['c_id'])) {
	$id_escola = $_POST['c_id'];

	//echo "<script>alert('$id_escola');</script>";

	$sql = "SELECT o.id_os id, o.dt_abertura dt, o.protocolo pt, m.sub_motivo mt FROM ordem_servico o, sub_motivo_chamado m WHERE fk_id_nome_escola = '$id_escola' and m.id_sub_motivo = o.fk_id_motivo_os order by dt DESC";
	$result_OSs = $link->query($sql);
	
	if(mysqli_num_rows($result_OSs) > 0){
                                                
        while($row = mysqli_fetch_object($result_OSs)) {

            $id_os = $row->id;
            $data = date_create($row->dt);
            $data_os = date_format($data, 'd/m/Y');
            $protocolo = $row->pt;
            $motivo = $row->mt;

            echo "<div data-vtdate='$data_os'>
                  	<h4>Protocolo: $protocolo</h4>
                    <h5>Motivo: $motivo</h5><br>
                    <div class='tooltip'><a href='setor_ver_os.php?id_os=".$id_os."'><i class='material-icons dp48'>visibility</i></a><span class='tooltiptextbottom'>Detalhes</span></div>
                    <div class='tooltip'><a href='documents/".$protocolo.".pdf' target='_blank'><i class='material-icons dp48'>description</i></a><span class='tooltiptextbottom'>Documento</span></div>
                    <div class='tooltip'><a href='galeria/index.php?id_os=".$id_os."' target='_blank'><i class='material-icons dp48'>picture_in_picture</i></a><span class='tooltiptextbottom'>Fotos</span></div>
                    <div class='tooltip'><a href='rel_obra/gera_rel_os.php?id_os=".$id_os."' target='_blank'><i class='material-icons dp48'>library_books</i></a><span class='tooltiptextbottom'>Rel Fotos</span></div>
                    <div class='tooltip'><a href='rel_obra/gera_rel_bio.php?id_os=".$id_os."' target='_blank'><i class='material-icons dp48'>view_list</i></a><span class='tooltiptextbottom'>Rel Biometria</span></div>
                  </div>";  
                                                        
        }
	} else {
		echo "<h4>Não existe histórico da escola selecionada...</h4>";
	}
} else {
	header('location: ./');
} 


?>