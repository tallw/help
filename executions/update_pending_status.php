 <?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($_SESSION['user_name']) && !is_numeric($_SESSION['user_name'])){
    
    $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

    mysqli_query($link,"UPDATE execucao_diaria SET status_pendencia = '".$_POST["status"]."' WHERE id_execucao = '".$_POST["key"]."';") or die(mysqli_error( $link ));

    $DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
    $DT_atual = $DT_atual->format('Y-m-d h:m:i');

    
    $query_descobre_sede = "SELECT * FROM sede S WHERE S.user_sede = '$usuario'";
    $result_descobre_sede = $link->query($query_descobre_sede);
    $row_sede = mysqli_fetch_object($result_descobre_sede);
    $id_usersede = $row_sede->sede_id;


    $query_add_log = "INSERT INTO `log_acoes`(`id_acao`, `data_acao`, `descricao_acao`, `fk_id_user`) VALUES (NULL,'$DT_atual','Atualização do status da pendência de execução ID: ".$_POST["key"]."','$id_usersede')";
    $result_add_log = $link->query($query_add_log) or die( mysqli_error( $link ) );

    mysqli_close($link);

}else{

    header("location: ../index.php");
    exit();

}
?>