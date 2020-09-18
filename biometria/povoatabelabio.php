<?php

	$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
    $link->set_charset('utf8');


	$query_bios = "SELECT * FROM escola e, gre g WHERE e.serial_b != '' AND e.serial_b REGEXP '[0-9]' AND e.gre = g.id_gre AND e.serial_b IS NOT NULL";

	$result_bios = $link->query($query_bios) or die(mysqli_error($link));

	if (mysqli_num_rows($result_bios) > 0) {

		$cont = 1;
		
		while($row = mysqli_fetch_object($result_bios)) {
			
			$serial_b = $row->serial_b;
			$id_sede = $row->id_sede;
			$id_escola = $row->id_escola;

			$query_insert = "INSERT INTO `biometrias`(`serial_bio`, `status_bio`, `sede_bio`) VALUES ('$serial_b',2,'$id_sede')";

			$result_insert = $link->query($query_insert) or die(mysqli_error($link));

			if ($result_insert == TRUE) {
				echo nl2br("N: ".$cont." Serial: ".$serial_b." ID escola: ".$id_escola."\n");
				$cont++;
			}else{
				echo nl2br("###################################################\n");
			}

		}
	}

	echo 'terminou';
?>