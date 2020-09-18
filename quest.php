<?php

$id_os = $_GET['id_os'];

if(!isset($_SESSION)){ 

    session_start(); 
}

$usuario = $_SESSION['user_name'];

$link = new mysqli('localhost', 'root', '260211', 'help_desk_ecos');
$link->set_charset('utf8');

$query_OS = "SELECT * FROM ordem_servico WHERE id_os = '$id_os'";
$result_OS = $link->query($query_OS);
$row_OS = mysqli_fetch_object($result_OS);
$id_escola = $row_OS->fk_id_nome_escola;
$status = $row_OS->status;

$query_escola = "SELECT * FROM escola WHERE id_escola = '$id_escola'";
$result_escola = $link->query($query_escola);
$row_escola = mysqli_fetch_object($result_escola);
$inep_escola = $row_escola->inep;


if(isset($usuario) && $usuario == $inep_escola && is_numeric($usuario) && $status == '3'){

?>


<!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>HELP-ECOS | Sistema de Apoio às Escolas</title>

        <link rel="stylesheet" href="avaliacao/estilo.css">
    	
    	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    	<link rel="stylesheet" href="assets/materialize/css/materialize.min.css" media="screen,projection" />
        <!-- Bootstrap Styles-->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FontAwesome Styles-->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- Morris Chart Styles-->
        <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- Custom Styles-->
        <link href="assets/css/custom-styles.css" rel="stylesheet" />
        <!-- Google Fonts-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <link rel="stylesheet" href="assets/js/Lightweight-Chart/cssCharts.css"> 

        
    </head>

    <script language="javascript" type="text/javascript" >

  function validarcampos(){

   d = document.cad;


    if (d.q1[0].checked == false && d.q1[1].checked == false && d.q1[2].checked == false && d.q1[3].checked == false) {
      alert('Por favor, selecione uma opção na questão 1...');
      d.q1[0].focus();
      return false;
    }

    if (d.q2[0].checked == false && d.q2[1].checked == false && d.q2[2].checked == false && d.q2[3].checked == false) {
      alert('Por favor, selecione uma opção na questão 2...');
      d.q2[0].focus();
      return false;
    }

    if (d.q3[0].checked == false && d.q3[1].checked == false && d.q3[2].checked == false && d.q3[3].checked == false) {
      alert('Por favor, selecione uma opção na questão 3...');
      d.q3[0].focus();
      return false;
    }

    if (d.q4[0].checked == false && d.q4[1].checked == false && d.q4[2].checked == false && d.q4[3].checked == false) {
      alert('Por favor, selecione uma opção na questão 4...');
      d.q4[0].focus();
      return false;
    }

    if (d.q5[0].checked == false && d.q5[1].checked == false) {
      alert('Por favor, selecione uma opção na questão 5...');
      d.q5[0].focus();
      return false;
    }

    if (d.q5[1].checked && apenasEspacos(d.message5.value)) {
      alert('Você precisa justificar sua resposta da questão 5...');
      d.message5.focus();
      return false;
    }

    if (d.q6[0].checked == false && d.q6[1].checked == false) {
      alert('Por favor, selecione uma opção na questão 6...');
      d.q6[0].focus();
      return false;
    }

    if (d.q6[1].checked && apenasEspacos(d.message6.value)) {
      alert('Você precisa justificar sua resposta da questão 6...');
      d.message6.focus();
      return false;
    }

    if (apenasEspacos(d.q7.value)) {
      alert('Você precisa responder a questão 7...');
      d.q7.focus();
      return false;
    }

    if (apenasEspacos(d.q8.value)) {
      alert('Você precisa responder a questão 8...');
      d.q8.focus();
      return false;
    }



  } 

  function apenasEspacos(texto){
    var letras = texto.split(' ');
    var semLetras = true;

    for (var i = 0; i < letras.length; i++) {
        if (letras[i] == '' || letras[i] == ' ') {
            continue;
        }else{
            semLetras = false;
        }
        
    }
    return semLetras;    
  }

</script>

    <body>
        <div id="wrapper">
            <nav class="navbar navbar-default top-navbar" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle waves-effect waves-dark" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand waves-effect waves-dark" href="#"><i class="large material-icons">insert_chart</i> <strong>HELP-ECOS</strong></a>
    				
    				<div id="sideNav" href=""><i class="material-icons dp48">toc</i></div>
                </div>

                <ul class="nav navbar-top-links navbar-right"> 			
    			  	<li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b><?php echo $usuario; ?></b> <i class="material-icons right">arrow_drop_down</i></a>
    			  	</li>
                </ul>
            </nav>
    		<!-- Dropdown Structure -->
    		<ul id="dropdown1" class="dropdown-content">
        		<li>
                    <a href="edita_user.php"><i class="fa fa-user fa-fw"></i> Meu Perfil</a>
                </li>
        		<li>
                    <a href="index.php?logout"><i class="fa fa-sign-out fa-fw"></i> Sair</a>
        		</li>
    		</ul>
    	   <!--/. NAV TOP  -->
            <nav class="navbar-default navbar-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="main-menu">
                        <li>
                            <a class="active-menu waves-effect waves-dark" href="school_dashboard.php"><i class="fa fa-dashboard"></i> Painel</a>
                        </li>
                        <li>
                            <a href="#" class="waves-effect waves-dark"><i class="fa fa-ticket"></i> Chamados<span class="fa arrow"></span></a>  
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="abrir_chamado.php">Abrir Chamado</a>
                                </li>
                                <li>
                                    <a href="meus_chamados.php?filtro=0">Meus Chamados</a>
                                </li>
                            </ul>
                        </li>
                    <?php

                        if ($usuario === 'master') { ?>
                            
                        <li>
                            <a href="#" class="waves-effect waves-dark"><i class="fa fa-ticket"></i> Relatórios<span class="fa arrow"></span></a>  
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Mensal<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Metas</a>
                                        </li>
                                        <li>
                                            <a href="setor_rel_atividades.php">Atividades</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="rel_quantitativo.php">Quantitativo</a>
                                </li>
                            </ul>
                        </li>

                        <?php

                        }

                        ?>
                        
                    </ul>
                </div>
            </nav>

       
            <!-- /. NAV SIDE  -->



            <div id="page-wrapper">
              <div class="header"> 
                    <h1 class="page-header">
                        Questionario de avaliação do Chamado: <?php echo $id_os ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li>Aqui você deve avaliar o atendimento da finalização da OS</li>
                    </ol> 
                                        
                </div>
                <div id="page-inner">               
                    <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                             Dados Chamado
                        </div> 
                        <div class="card-content">
                            <div class="table-responsive">

                                <script type="text/javascript" src="wz_tooltip.js"></script>
                                <img style="height: 20px; position:absolute; left: 1500px; top: 40px;" src="image/interrogacao.png" onmouseover="Tip('Marque apenas uma opção em cada questão de sua avaliação do chamado...')" onmouseout="UnTip()">

                                <?php 

                                    $id_escola = $row_OS->fk_id_nome_escola;

                                    $dt_abertura = date_create( $row_OS->dt_abertura);
                                    $data = date_format($dt_abertura, 'd/m/Y');

                                    $status = $row_OS->status;
                                    $status_txt = '';
                                    if ($status == '1') {
                                        $status_txt = 'Aberto';
                                    }else if ($status == '2') {
                                        $status_txt = 'Em Atendimento';
                                    }else{
                                        $status_txt = 'Finalizado';
                                    }

                                    $id_motivo = $row_OS->fk_id_motivo_chamado;
                                    $query_motivo = "SELECT * FROM razao_chamado where id_motivo = '$id_motivo'";
                                    $result_motivo = $link->query($query_motivo);
                                    $row_motivo = mysqli_fetch_object($result_motivo);
                                    $motivo = $row_motivo->motivo_chamado;

                                    $id_dep = $row_motivo->fk_id_departamento;
                                    $query_dep = "SELECT * FROM departamento where id_departamento = '$id_dep'";
                                    $result_dep = $link->query($query_dep);
                                    $row_dep = mysqli_fetch_object($result_dep);
                                    $dep = $row_dep->nome_departamento;

                                    $nome_escola = $row_escola->nome_escola;
                                    $gestor_escola = $row_escola->responsavel;


                                ?>
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <td><b>Nº OS:</b></td>
                                        <td><?php echo $id_os ?></td>
                                        <td><b>Status: </b></td>
                                        <td><?php echo $status_txt ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Data: </b></td>
                                        <td><?php echo $data ?></td>
                                        <td><b>Montivo: </b></td>
                                        <td><?php echo $motivo ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Departamento: </b></td>
                                        <td><?php echo $dep ?></td>
                                        <td><b>Gestor: </b></td>
                                        <td><?php echo $gestor_escola ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Escola: </b></td>
                                        <td colspan="3"><?php echo $nome_escola ?></td>
                                        
                                    </tr>
                                    
                                </table>

                            </div>
                            
                        </div>
                    </div>

                    <!-- End Advanced Tables -->

                    <div class="card">
                        <div class="card-action">
                             Questionario:
                        </div> 
                        <div class="card-content">
                            
                            <div id="topo">
        AVALIAÇÃO DO CHAMADO: <?php echo $id_os ?>
    </div>




<form name="cad" method="post" action="grava_dados.php">
    <div id="conteudo">

        <!-- <div align="right">Inserir texto aqui</div>
        <div align="right">Inserir texto aqui</div> -->
        <center><img src="avaliacao/logo.jpg"></center>

        <div class="questoes">
            <p>1) A velocidade da internet é suficiente inclusive para utilização de objetos digitais de aprendizagem?</p>
            <center><p>
            	<input class="with-gap" name="group1" type="radio" id="q1a" /><label style="width: 140px;" for="q1a">1</label>
            	<input class="with-gap" name="group1" type="radio" id="q1b" /><label style="width: 140px;" for="q1b">2</label>
            	<input class="with-gap" name="group1" type="radio" id="q1c" /><label style="width: 140px;" for="q1c">3</label>
            	<input class="with-gap" name="group1" type="radio" id="q1d" /><label style="width: 140px;" for="q1d">4</label>
            	<input class="with-gap" name="group1" type="radio" id="q1e" /><label style="width: 140px;" for="q1e">5</label>
            </p></center>
    		<p><input class="with-gap" name="group1" type="radio" id="q1b" /><label for="q1b">2</label></p>
    		<p><input class="with-gap" name="group1" type="radio" id="q1c" /><label for="q1c">3</label></p>
    		<p><input class="with-gap" name="group1" type="radio" id="q1d" /><label for="q1d">4</label></p>
    		<p><input class="with-gap" name="group1" type="radio" id="q1e" /><label for="q1e">5</label></p>
        </div>

        <div class="questoes">
            <p>2) Os equipamentos de informática estão em bom funcionamento (computadores, impressoras, modem, roteador)?</p>
            <p><input name="group2" type="radio" id="q2a" /><label for="q2a">1</label></p>
    		<p><input name="group2" type="radio" id="q2b" /><label for="q2b">2</label></p>
    		<p><input name="group2" type="radio" id="q2c" /><label for="q2c">3</label></p>
    		<p><input name="group2" type="radio" id="q2d" /><label for="q2d">4</label></p>
    		<p><input name="group2" type="radio" id="q2e" /><label for="q2e">5</label></p>
        </div>

        <div class="questoes">
            <p>3) Os sistemas de projeção/equipamentos audiovisuais estão em bom funcionamento?</p>
            <p><input name="group3" type="radio" id="q3a" /><label for="q3a">1</label></p>
    		<p><input name="group3" type="radio" id="q3b" /><label for="q3b">2</label></p>
    		<p><input name="group3" type="radio" id="q3c" /><label for="q3c">3</label></p>
    		<p><input name="group3" type="radio" id="q3d" /><label for="q3d">4</label></p>
    		<p><input name="group3" type="radio" id="q3e" /><label for="q3e">5</label></p>
        </div>

        <div class="questoes">
            <p>4) A rede de telefonia funciona bem?</p>
            <p><input name="group4" type="radio" id="q4a" /><label for="q4a">1</label></p>
    		<p><input name="group4" type="radio" id="q4b" /><label for="q4b">2</label></p>
    		<p><input name="group4" type="radio" id="q4c" /><label for="q4c">3</label></p>
    		<p><input name="group4" type="radio" id="q4d" /><label for="q4d">4</label></p>
    		<p><input name="group4" type="radio" id="q4e" /><label for="q4e">5</label></p>
        </div>

        <div class="questoes">
            <p>5) A rede lógica (rede de computadores) funciona bem?</p>
            <p><input name="group5" type="radio" id="q5a" /><label for="q5a">1</label></p>
    		<p><input name="group5" type="radio" id="q5b" /><label for="q5b">2</label></p>
    		<p><input name="group5" type="radio" id="q5c" /><label for="q5c">3</label></p>
    		<p><input name="group5" type="radio" id="q5d" /><label for="q5d">4</label></p>
    		<p><input name="group5" type="radio" id="q5e" /><label for="q5e">5</label></p>
        </div>

        <div class="questoes">
            <p>6) A rede elétrica funciona bem?</p>
            <p><input name="group6" type="radio" id="q6a" /><label for="q6a">1</label></p>
    		<p><input name="group6" type="radio" id="q6b" /><label for="q6b">2</label></p>
    		<p><input name="group6" type="radio" id="q6c" /><label for="q6c">3</label></p>
    		<p><input name="group6" type="radio" id="q6d" /><label for="q6d">4</label></p>
    		<p><input name="group6" type="radio" id="q6e" /><label for="q6e">5</label></p>
        </div>

        <div class="questoes">
            <p>7) A rede elétrica funciona bem?</p>
            <p><input name="group7" type="radio" id="q7a" /><label for="q7a">1</label></p>
    		<p><input name="group7" type="radio" id="q7b" /><label for="q7b">2</label></p>
    		<p><input name="group7" type="radio" id="q7c" /><label for="q7c">3</label></p>
    		<p><input name="group7" type="radio" id="q7d" /><label for="q7d">4</label></p>
    		<p><input name="group7" type="radio" id="q7e" /><label for="q7e">5</label></p>
        </div>

        <br><br>

        <div>
          <center><input type="submit" class="btn" value="Enviar Respostas" onClick="return validarcampos()"></center>
          <input type="hidden" name="cpf" value="<?php echo $cpf; ?>">
        </div>    

    </div>

    </form>
    
    <footer>
        <p>Uma produção de Desenvolvimento ECOS-PB - 2018</p>
    </footer>
    <script type="text/javascript" src="avaliacao/main.js"> </script>

                        </div>
                        
                    </div>
                </div>
            </div>
                
                    <!-- /. ROW  -->
                </div>
                <!-- /. PAGE INNER  -->
            </div>
        </div>





            <!-- /. WRAPPER  -->
        <!-- JS Scripts-->
        <!-- jQuery Js -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        
        <!-- Bootstrap Js -->
        <script src="assets/js/bootstrap.min.js"></script>
        
        <script src="assets/materialize/js/materialize.min.js"></script>
        
        <!-- Metis Menu Js -->
        <script src="assets/js/jquery.metisMenu.js"></script>
        <!-- Morris Chart Js -->
        <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
        <script src="assets/js/morris/morris.js"></script>
        
        
        <script src="assets/js/easypiechart.js"></script>
        <script src="assets/js/easypiechart-data.js"></script>
        
        <script src="assets/js/Lightweight-Chart/jquery.chart.js"></script>
        
        <!-- Custom Js -->
        <script src="assets/js/custom-scripts.js"></script> 
     

    </body>

    </html>

<?php
}else{

    header("location: ./index.php");
    exit();

}
?>