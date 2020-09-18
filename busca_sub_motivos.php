<?php include("/config/db.php");

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$usuario = $_SESSION['user_name'];

//echo "<script>alert('ola');</script>";

if(isset($_POST['c_id'])) {
	$motivo = $_POST['c_id'];

	if (is_numeric($usuario)) {
		$sql = "SELECT * FROM sub_motivo_chamado WHERE visivel_p_escola = '1' AND fk_id_motivo_chamado = '$motivo' ORDER BY sub_motivo";
	}elseif (!is_numeric($usuario)) {
		$sql = "SELECT * FROM sub_motivo_chamado WHERE fk_id_motivo_chamado = '$motivo' ORDER BY sub_motivo";
	}
	
	$res = mysqli_query($link, $sql);
	if(mysqli_num_rows($res) > 0) {
		echo "<option value=''>Motivo do chamado...</option>";
		while($row = mysqli_fetch_object($res)) {
			echo "<option value='".$row->id_sub_motivo."'>".$row->sub_motivo."</option>";
		}
	} else {
		echo "<option>Não existe motivos de chamados disponíveis</option>"; // foi colocado como "motivo" para facilitar o entendimento do usuário gestor
	}
} else {
	header('location: ./');
} 
?>
