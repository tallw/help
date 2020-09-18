<?php

$id_setor = $_POST['id_setor'];
	
if(!isset($_SESSION)){ 

    session_start(); 
}

if(isset($_SESSION['user_name'])){

  require_once("config/db.php");

  $conecta = mysql_connect(DB_HOST, DB_USER, DB_PASS) or print (mysql_error()); 
  mysql_select_db(DB_NAME, $conecta) or print(mysql_error());

  //echo "<script>alert('ID do setor: $id_setor');</script>";
	

  $sql = "SELECT * FROM `razao_chamado`  WHERE fk_id_departamento = '$id_setor'";
  $qr = mysql_query($sql) or die(mysql_error());

  if(mysql_num_rows($qr) == 0){
    echo  '<option value="0">'.htmlentities('Não ha produtos para este tipo').'</option>'; 
  }else{
	  echo "<option value='0'>(Selecione o Motivo)</option>";
    while($ln = mysql_fetch_assoc($qr)){
      echo '<option value="'.$ln['id_motivo'].'">'.utf8_encode($ln['motivo_chamado']).'</option>';
    }
  }

}else{

    header("location: ./index.php");
    exit();

}
?>
