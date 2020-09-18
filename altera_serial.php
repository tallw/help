<?php

if(!isset($_SESSION)){ 

    session_start(); 

}else{
        
    session_destroy();
    session_start(); 
}

$usuario = $_SESSION['user_name'];


if(isset($usuario) && !is_numeric($usuario)){

    $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
    $link->set_charset('utf8');

    $id_os = $_GET['id_os'];

    $query = "SELECT * FROM escola e, gre g, ordem_servico o WHERE o.id_os = '$id_os' AND e.gre = g.id_gre AND o.fk_id_nome_escola = e.id_escola";
    $result = $link->query($query) or die(mysqli_error($link));
    $row = mysqli_fetch_object($result);

    $bio_antiga = $row->fk_id_biometria;
    $nome_escola = $row->nome_escola;
    $protocolo = $row->protocolo;
    $sede_escola = $row->id_sede;

    if ($bio_antiga != 0) {
        $query_bio = "SELECT * FROM biometrias WHERE id_biometria = '$bio_antiga'";
        $result_bio = $link->query($query_bio);
        $row_bio = mysqli_fetch_object($result_bio);
        $biometria = $row_bio->serial_bio;
    }else{
        $biometria = "Não possui...";
    }

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
    <title>HELP-ECOS | Sistema de Apoio às Escolas</title>

    <!-- ############################################### SEARCH SELECT ################################################## -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
    <!--<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" rel="stylesheet" />-->

    <!-- ############################################### SEARCH SELECT ################################################## -->
	
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
    <!-- Search select -->
    <link href="dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="assets/js/Lightweight-Chart/cssCharts.css"> 

</head>
<!-- Populate option -->
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="js.js"></script>
    <script type="text/javascript" src="js2.js"></script>

<body>
    <?php include("setor_menu.php"); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" > 
		  	<div class="header"> 
                <h1 class="page-header">Atualização Serial de Biometria</h1>
				<ol class="breadcrumb">
					<li>Modifique o serial da biometria atual por um novo...</li>
				</ol> 							
			</div>	
            <div id="page-inner"> 
				<div class="row">
                    <div class="col-md-3"></div>
					<div class="col-md-6">
						
                            <div class="card"">
                    			<div class="card-action">Informe o status para o Serial atual e escolha o Serial novo...</div>
                    				<div class="card-content">
    								    <form class="col s12" name="form-pesquisa" action="executions/grava_nova_bio.php" method="post">

    								    	<div class="row">
                                            	<div class="input-field col s9">
                                                    <label><font size="4">ESCOLA: <?php echo $nome_escola; ?></font></label>
                                                </div>   
                                            </div><br>

                                            <div class="row"> 
                                            	<div class="input-field col s6">
                                                    <label><font size="4">SERIAL Atual: <?php echo $biometria; ?></font></label>
                                                </div>

                                                <?php if($bio_antiga != 0){ ?>
                                                <div class="input-field col s3">
                                                    <select class="form-control" name="status_b" id="status_b" required="" style="width: 200px">
                                                        
                                                            <option value='0'>Novo Status...</option>
                                                            <option value='1'>Em Estoque</option>
                                                            <option value='3'>Sede JP (Defeito)</option>
                                                            <option value='4'>Garantia (Defeito)</option>
                                                                     
                                                    </select>
                                                    <input type="hidden" name="id_bio" id="id_bio" value="<?php echo $bio_antiga; ?>">
                                                </div>
                                                <?php }else{ ?> 
                                                    <input type="hidden" name="id_bio" id="id_bio" value="nt">
                                                    <input type="hidden" name="status_b" id="status_b" value="nt">  
                                                <?php } ?>    
                                            </div>

                                            <div class="row">
                                            	<input type="hidden" name="id_os" id="id_os" value="<?php echo $id_os; ?>">
                                                <input type="hidden" name="sede_b" id="sede_b" value="<?php echo $sede_escola; ?>">
                                            </div>
                                    			
    								    	<div class="row"> 

    								    		<div class="input-field col s6">
                                                    <label><font size="4">Nova Biometria: </font></label>
                                                </div>

	                                            <div class="input-field col s6">

	                                                <!-- ############################################### SEARCH SELECT ################################################## -->
	                                                <select name="nova_b" class="selectpicker" data-show-subtext="true" data-live-search="true" id="nova_b">

	                                                    <?php
	                                                        echo "<option value='0'>Biometrias em Estoque...</option>";
                                                            echo "<option value='nt'>Ficar sem Biometria</option>";




                                                            $sql = "SELECT * FROM biometrias WHERE status_bio = 1 AND sede_bio = '$sede_escola'";      
	                                                        $dados = $link->query($sql);

	                                                        if(mysqli_num_rows($dados) > 0) {

                                                                //echo "<script>alert('$sede_escola')</script>";
	                                                            while($row = mysqli_fetch_object($dados)) {
	                                                                    echo "<option value='".$row->id_biometria."'>".$row->serial_bio."</option>";
	                                                            }
	                                                        }
	                                                    ?>         
	                                                </select>
	                                            </div>
                                        	</div>

                                            <div>
                                                <button type="submit" class="waves-effect waves-light btn" id="submit">Atualizar</button>

                                                <a href="setor_inserir_execucao.php?id_os=<?php echo $id_os; ?>&protocolo=<?php echo $protocolo; ?>" class="waves-effect waves-light btn custom-back">Voltar</a>
                                            </div>

                                            
    								    </form>
    									<div class="clearBoth"></div>
    	  							</div>
    	    					</div>
                            </div>
                        
		 			</div>
                    <div class="col-md-3"></div>		
                </div> 
                <!-- /.col-lg-12 --> 
			</div>
             <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
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

    <!-- Search select -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js" defer></script>
    <script src="dist/js/bootstrap-select.js" defer></script>


</body>

</html>

<?php
}else{

    header("location: ./index.php");
    exit();

}
?>
