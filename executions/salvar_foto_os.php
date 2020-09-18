 <?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];

if(isset($_SESSION['user_name']) && !is_numeric($usuario)){
    
require('../config/config.php');
include('../libraries/functions.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HELP-ECOS | Sistema de Apoio às Escolas</title>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/materialize/css/materialize.min.css" media="screen,projection" />
    <!-- Bootstrap Styles-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="../assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="../assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="../assets/js/Lightweight-Chart/cssCharts.css"> 
</head>

<body>
    <?php include("setor_menu.php"); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">
                    Arquivos de Imagem
                </h1>
                <ol class="breadcrumb">
					<li>Resultado do Upload.</li>
				</ol>
            </div>
		
            <div id="page-inner"> 
               
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                            Mensagem
                        </div>
                        <div class="card-content">
 						<div class="table-responsive">
                            <?php

                                $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
                                $link->set_charset('utf8');

                                if (!$link){
                                  die('Connect Error (' . mysqli_connecterrno() . ')' .
                                    mysqli_connect_error());
                                }else{ 

                                  $id_os = $_POST['id_os'];

                                  $result_os = mysqli_query($link, "SELECT * from ordem_servico where id_os = '$id_os'");
                                  $protocolo = mysqli_fetch_object($result_os)->protocolo;



                                }



                                if(isset($_POST['enviar']) && !empty($_POST['enviar'])){
                                    $imagem = isset($_FILES['imagem']) && !empty($_FILES['imagem']) ? $_FILES['imagem'] : NULL;
                                    // utilizando o $_FILES, a unica relacao existente
                                    // entre os ficheiros será o numero de indice, visto que  serão agrupados
                                    // por tipo consoante os indices de $_FILES
                                    if($imagem){

                                        $_UP['pasta'] = '../galeria/'.$protocolo."/";
                                        $pathToSave = $_UP['pasta'];
                                        /*Checa se a pasta existe - caso negativo ele cria*/
                                        if (!file_exists($pathToSave)) {
                                          //echo "<script>alert('aqui')</script>";
                                          mkdir("../galeria/".$protocolo);
                                          chmod ("../galeria/".$protocolo,0777);
                                                
                                        }

                                        // Tamanho máximo do arquivo (em Bytes)
                                        $_UP['tamanho'] = 1024 * 1024 * 20; // 2Mb post_max_size=20M alterar no php ini
                                        // Array com as extensões permitidas
                                        $_UP['extensoes'] = array('jpeg', 'jpg', 'png', 'gif', 'bmp','jfif');
                                        // Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
                                        $_UP['renomeia'] = false;
                                        

                                          $tudo_ok = true;

                                          $i=0;
                                          foreach($imagem['name'] as $img){

                                            $arq = explode('.', $img);
                                            $extensao = strtolower(end($arq));
                                            if (array_search($extensao, $_UP['extensoes']) === false) {
                                              ?>
                                              <div class="input-field col s12">
                                                <div class="alert alert-danger">
                                                  <strong>Por favor, apenas envie arquivos com a extensão (jpg, png, gif ou bmp)</strong>
                                                </div>            
                                              </div>
                                              <?php
                                              $tudo_ok = false;
                                              break;
                                            }else if ($_UP['tamanho'] < $imagem['size'][$i]) {
                                              ?>
                                              <div class="input-field col s12">
                                                <div class="alert alert-danger">
                                                  <strong>O arquivo enviado é muito grande, envie arquivos de até 20Mb.</strong>
                                                </div>            
                                              </div>
                                              <?php
                                              $tudo_ok = false;
                                              break;
                                            }

                                            $i++;
                                            
                                          }

                                          $foto_ok = $_POST['foto_ok'];

                                          if ($foto_ok === '1') {
                                            ?>

                                          <div class="input-field col s12">
                                            <div class="alert alert-danger">
                                                <strong>Não é possível upar mais de 20 fotos no sistema de uma só vez...!</strong>
                                            </div> 
                                            <div class="input-field col s3">
                                              <a href="../setor_ver_os.php?id_os=<?php echo $id_os; ?>" class="waves-effect waves-light btn custom-back">Voltar</a>
                                          </div>           
                                          </div>

                                        <?php
                                          }
                                            else if ($tudo_ok) {

                                            $x=0;

                                            foreach($imagem['name'] as $img){

                                              if ($_UP['renomeia'] == true) {
                                                // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
                                                $nome_final = md5(time()).'.jpg';
                                              } else {
                                                // Mantém o nome original do arquivo
                                                $nome_final = $img;
                                              }

                                              //move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $_FILES['arquivo']['name'])

                                              move_uploaded_file($imagem['tmp_name'][$x], $_UP['pasta'].'_'.$nome_final);

                                              $x++;
                                               
                                            }

                                            //########################################################## LOG ############################################

                                            $DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
                                            $DT_atual = $DT_atual->format('Y-m-d h:m:i');

    
                                            $query_descobre_sede = "SELECT * FROM sede S WHERE S.user_sede = '$usuario'";
                                            $result_descobre_sede = $link->query($query_descobre_sede);
                                            $row_sede = mysqli_fetch_object($result_descobre_sede);
                                            $id_usersede = $row_sede->sede_id;


                                            $query_add_log = "INSERT INTO `log_acoes`(`id_acao`, `data_acao`, `descricao_acao`, `fk_id_user`) VALUES (NULL,'$DT_atual','Fotos inseridas na OS: $protocolo','$id_usersede')";
                                            $result_add_log = $link->query($query_add_log) or die( mysqli_error( $link ) );

                                            //###################################################################################################################


                                            ?>

                                          <div class="input-field col s12">
                                            <div class="alert alert-success">
                                                <strong>Upload efetuado com sucesso!</strong>
                                            </div>            
                                          </div>
                                          <div class="input-field col s3">
                                              <a href="../setor_ver_os.php?id_os=<?php echo $id_os; ?>" class="waves-effect waves-light btn custom-back">Voltar</a>
                                          </div>

                                        <?php


                                          }else{
                                            ?>

                                          <div class="input-field col s12">
                                            <div class="alert alert-danger">
                                                <strong>Não foi possível realizar o upload, tente novamente!</strong>
                                            </div>            
                                          </div>

                                        <?php

                                          }
                                        
                                        
                                    } else {

                                      ?>

                                          <div class="input-field col s12">
                                            <div class="alert alert-danger">
                                                <strong>Por favor, Selecione um arquivo de imagem primeiro!</strong>
                                            </div>            
                                          </div>

                                        <?php
                                        
                                    }
                                }else{
                                    echo "<script>alert('deu erro')</script>";
                                }
                              ?> 
						</div>
						<!-- End Card Content-->
						</div>
						<!-- End Table-Responsive-->
					</div>
					<!--End Card -->
				</div>
				<!--End col-md-12-->
			</div>
			<!--ROW-->
			</div>
			<!-- /. PAGE INNER  -->        
	    </div>
	    <!-- /. PAGE WRAPPER  -->
	</div>
	<!-- /. WRAPPER  -->

	<!-- JS Scripts-->
								 
    <!-- jQuery Js -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
	
	<!-- Bootstrap Js -->
    <script src="../assets/js/bootstrap.min.js"></script>
	
	<script src="../assets/materialize/js/materialize.min.js"></script>
	
    <!-- Metis Menu Js -->
    <script src="../assets/js/jquery.metisMenu.js"></script>
    <!-- Morris Chart Js -->
    <script src="../assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="../assets/js/morris/morris.js"></script>
	
	
	<script src="../assets/js/easypiechart.js"></script>
	<script src="../assets/js/easypiechart-data.js"></script>
	
	 <script src="../assets/js/Lightweight-Chart/jquery.chart.js"></script>
    <!-- Custom Js -->
    <script src="../assets/js/custom-scripts.js"></script> 

</body>
</html>
<?php
}else{

    header("location: ../index.php");
    exit();

}
?>