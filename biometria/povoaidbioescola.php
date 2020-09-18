<?php

	$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
    $link->set_charset('utf8');


	$query_bios = "SELECT * FROM biometrias";

	$result_bios = $link->query($query_bios) or die(mysqli_error($link));

	if (mysqli_num_rows($result_bios) > 0) {
		
		while($row = mysqli_fetch_object($result_bios)) {
			
			$serial_bio = $row->serial_bio;
			$id_biometria = $row->id_biometria;
			
			$query_update = "UPDATE `escola` SET `fk_id_biometria`= '$id_biometria' WHERE serial_b = $serial_bio";

			$result_update = $link->query($query_update) or die(mysqli_error($link));

			if ($result_update == TRUE) {
				echo nl2br($serial_bio."\n");
			}else{
				echo nl2br("###################################################\n");
			}
		}
	}

	echo 'terminou';
?>