<?php include("/config/db.php");

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

if(isset($_POST['c_id'])) {
	$dp = $_POST['c_id'];
	$sql = "SELECT * FROM motivo_chamado WHERE visivel_p_escola = '1' AND fk_id_departamento = '$dp' ORDER BY motivo";
	$res = mysqli_query($link, $sql);
	if(mysqli_num_rows($res) > 0) {
		echo "<option value=''>Selecione a categoria...</option>";
		while($row = mysqli_fetch_object($res)) {
			echo "<option value='".$row->id_motivo."'>".$row->motivo."</option>";
		}
	} else {
		echo "<option>Não há categorias disponíveis</option>";
	}
} else {
	header('location: ./');
} 
?>
