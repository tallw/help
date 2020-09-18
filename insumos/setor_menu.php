<div id="wrapper">
            <nav class="navbar navbar-default top-navbar" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle waves-effect waves-dark" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand waves-effect waves-dark" href="#"><i class="large material-icons">insert_chart</i> <strong><img height="45" width="193" src="../image/logo_teste.png"></strong></a>
    				
    				<div id="sideNav" href=""><i class="material-icons dp48">toc</i></div>
                </div>

                <ul class="nav navbar-top-links navbar-right"> 			
    			  	<li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <span id="uppercase"><?php echo $usuario; ?></span> <i class="material-icons right">arrow_drop_down</i></a>
    			  	</li>
                </ul>
            </nav>
    		<!-- Dropdown Structure  ecos_logo -->
    		<ul id="dropdown1" class="dropdown-content">
        		<li>
                    <a href="../edita_user.php"><i class="fa fa-key fa-fw"></i> Alterar Senha</a>
                </li>
        		<li>
                    <a href="../index.php?logout"><i class="fa fa-sign-out fa-fw"></i> Sair</a>
        		</li>
    		</ul>
    	   <!--/. NAV TOP  -->
            <nav class="navbar-default navbar-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="main-menu">
                        <li>
                            <a class="active-menu waves-effect waves-dark" href="../setor_dashboard.php"><i class="fa fa-dashboard"></i> Painel</a>
                        </li>
                        <li>
                            <a href="#" class="waves-effect waves-dark"><i class="fa fa-ticket"></i> Chamados<span class="fa arrow"></span></a>  
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="../setor_abrir_chamado.php">Abrir Chamado</a>
                                </li>
                                <li>
                                    <a href="setor_meus_chamados.php?filtro=0">Meus Chamados<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=1">Abertos</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=2">Em Levantamento</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=8">Em Cotação</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=9">Em Execução</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=3">Finalizados</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=6">Com Pós-Venda</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=17">Sem Pós-Venda</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=4">SLA Atendimento</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=5">SLA Conclusão</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=7">Com Pendências</a>
                                        </li>
                                        <li>
                                            <a href="../setor_meus_chamados.php?filtro=0">Todos</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect waves-dark" href="../timeline_tudao.php?filtro=0"><i class="material-icons left">restore</i> Histórico</a>
                        </li>
                        
                        <?php

                        $sede = '';

                        if (!is_numeric($usuario)){

                            $link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
                            $link->set_charset('utf8');

                            $query_user = "SELECT * FROM sede WHERE user_sede = '$usuario'";
                            $result_user = $link->query($query_user);
                            $row_user = mysqli_fetch_object($result_user);
                            $sede = $row_user->sede;
                            $user_sede = $row_user->user_sede;
                        }

                        if ($sede === '0') { ?>
                            
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
                                            <a href="../setor_rel_mensal.php?tipo=0">Atividades por Escola</a>
                                        </li>
                                        <li>
                                            <a href="../setor_rel_mensal.php?tipo=1">Atividades por GRE</a>
                                        </li>
                                        <li>
                                            <a href="../setor_rel_mensal.php?tipo=2">Pendentes</a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="#">SLA<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="../rel_quantitativo.php?tipo=0">Totais por SEDE</a>
                                        </li>
                                        <li>
                                            <a href="../rel_quantitativo.php?tipo=1">Totais por GRE</a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="../gera_tudao_2.php">TUDÃO<span class="fa arrow"></span></a>
                                </li>
                                
                            </ul>
                        </li>

                        <li>
                            <a class="waves-effect waves-dark" href="../backup_db.php"><i class="material-icons left">play_for_work</i> Backup Banco</a>
                        </li>

                        <?php }


                        $users = array('vieira.robson', 'master', 'junior.elco', 'alisson.nil', 'santana.fabiano');
                        

                        if (in_array($user_sede, $users)) { ?>

                        <li>
                            <a href="#" class="waves-effect waves-dark"><i class="fa fa-ticket"></i> Insumos<span class="fa arrow"></span></a>  
                            <ul class="nav nav-second-level">

                                <li>
                                    <a class="waves-effect waves-dark" href="cad_insumos.php"><i class="material-icons left">assignment</i> Inserir</a>
                                </li>

                                <li>
                                    <a class="waves-effect waves-dark" href="lista_insumos.php"><i class="material-icons left">assignment</i> Listar</a>
                                </li>

                                <li>
                                    <a class="waves-effect waves-dark" href="../docs/print_insumos.php"><i class="material-icons left">assignment</i> Imprimir</a>
                                </li>
                            
                            </ul>
                        </li>

                        <?php

                        }

                        ?>

                        <li>
                            <a href="#" class="waves-effect waves-dark"><i class="fa fa-ticket"></i> Biometria<span class="fa arrow"></span></a>  
                            <ul class="nav nav-second-level">

                                <li>
                                    <a class="waves-effect waves-dark" href="../biometria/inserir_bio.php"><i class="material-icons left">assignment</i> Inserir</a>
                                </li>

                                <li>
                                    <a class="waves-effect waves-dark" href="../biometria/bio_dashboard.php"><i class="material-icons left">assignment</i> Dashboard Bio</a>
                                </li>

                                <li>
                                    <a class="waves-effect waves-dark" href="../biometria/gera_todas_bios.php"><i class="material-icons left">assignment</i> Imprimir</a>
                                </li>
                            
                            </ul>
                        </li>


                        
                    </ul>
                </div>
            </nav>