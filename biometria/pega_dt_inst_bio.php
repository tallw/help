<?php

$link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
$link->set_charset('utf8');

$query_bios = "SELECT * FROM biometrias WHERE status_bio = 2";

$result_bios = $link->query($query_bios) or die(mysqli_error($link));

$DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
$DT_atual = $DT_atual->format('Y-m-d h:m:i');

if (mysqli_num_rows($result_bios) > 0) {
		
	while($row = mysqli_fetch_object($result_bios)) {
			
		$id_biometria = $row->id_biometria;


		$query_dt = "SELECT * FROM `ordem_servico` o, `escola` e, `biometrias` b WHERE o.fk_id_motivo_os = 16 AND o.fk_id_nome_escola = e.id_escola AND e.fk_id_biometria = '$id_biometria' AND o.status = 3";

		$result_dt = $link->query($query_dt) or die(mysqli_error($link));

		if (mysqli_num_rows($result_dt) > 0) {

			$row_dt = mysqli_fetch_object($result_dt);
			$dt_instalacao = $row_dt->dt_conclusao;
			$id_escola = $row_dt->id_escola;
			$id_sede = $row_dt->fk_id_sede;

			$query_history = "INSERT INTO `history_serial`(`data_mudanca`, `fk_id_serial`, `fk_id_escola_serial`, `fk_status_bio`, `fk_sede_bio`) VALUES ('$dt_instalacao','$id_biometria','$id_escola',2, '$id_sede')";

			$result_history = $link->query($query_history) or die(mysqli_error($link));

			if ($result_history == TRUE) {
				echo nl2br("ok\n");
			}else{
				echo nl2br("###################################################\n");
			}
		}else{

			$query_esc = "SELECT * FROM escola e, gre g WHERE fk_id_biometria = '$id_biometria' AND e.gre = g.id_gre";
			$result_esc = $link->query($query_esc) or die(mysqli_error($link));
			$row_dt = mysqli_fetch_object($result_esc);
			$id_escola = $row_dt->id_escola;
			$id_sede = $row_dt->id_sede;

			$query_history = "INSERT INTO `history_serial`(`data_mudanca`, `fk_id_serial`, `fk_id_escola_serial`, `fk_status_bio`, `fk_sede_bio`) VALUES ('$DT_atual','$id_biometria','$id_escola',2,'$id_sede')";

			$result_history = $link->query($query_history) or die(mysqli_error($link));

			if ($result_history == TRUE) {
				echo nl2br("ok\n");
			}else{
				echo nl2br("###################################################\n");
			}

			//echo nl2br("Não encontrada OS de instalação para a biometria de ID: $id_biometria \n");
		}
		
	}
}

echo 'terminou';




























?>