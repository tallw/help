<?php


?>



<html>
<head>
    <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="estilo.css">
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

    <div id="topo">
        PESQUISA DE SATISFAÇÃO EXTERNA
    </div>

<form name="cad" method="post" action="grava_dados.php">
    <div id="conteudo">

        <!-- <div align="right">Inserir texto aqui</div>
        <div align="right">Inserir texto aqui</div> -->
        <center><img src="logo.jpg"></center>

        <div class="questoes">
            <p>1) Que conceito você estabelece para a equipe de Supervisão (Qualidade) das unidades operacionais da ECOS (JP, CG e SS) no que se refere ao atendimento e solução de problemas?</p>
            <p>A <input name="q1" type="radio" value="A" >Ótimo</p>
            <p>B <input name="q1" type="radio" value="B">Bom</p>
            <p>C <input name="q1" type="radio" value="C">Regular</p>
            <p>D <input name="q1" type="radio" value="D">Ruim</p>
            <p><textarea name="message1" placeholder="Comentário(os):" class="textarea" maxlength="600" rows="6" cols="100"></textarea></p>
        </div>

        <div class="questoes">
            <p>2) Que conceito você estabelece para a equipe de Atendimento (Secretariado) das unidades operacionais da ECOS (JP, CG e SS) no que se refere ao atendimento e solução de problemas?</p>
            <p>A <input name="q2" type="radio" value="A" >Ótimo</p>
            <p>B <input name="q2" type="radio" value="B">Bom</p>
            <p>C <input name="q2" type="radio" value="C">Regular</p>
            <p>D <input name="q2" type="radio" value="D">Ruim</p>
            <p><textarea name="message2" placeholder="Comentário(os):" class="textarea" maxlength="600" rows="6" cols="100"></textarea></p>
        </div>

        <div class="questoes">
            <p>3) Que conceito você estabelece para a equipe do Departamento Pessoal (RH) das unidades operacionais da ECOS (JP, CG e SS) no que se refere ao atendimento e solução de problemas?</p>
            <p>A <input name="q3" type="radio" value="A" >Ótimo</p>
            <p>B <input name="q3" type="radio" value="B">Bom</p>
            <p>C <input name="q3" type="radio" value="C">Regular</p>
            <p>D <input name="q3" type="radio" value="D">Ruim</p>
            <p><textarea name="message3" placeholder="Comentário(os):" class="textarea" maxlength="600" rows="6" cols="100"></textarea></p>
        </div>

        <div class="questoes">
            <p>4) Que conceito você estabelece para a equipe de Gestão de Pessoas (RH) das unidades operacionais da ECOS (JP, CG e SS) no que se refere ao atendimento e solução de problemas?</p>
            <p>A <input name="q4" type="radio" value="A" >Ótimo</p>
            <p>B <input name="q4" type="radio" value="B">Bom</p>
            <p>C <input name="q4" type="radio" value="C">Regular</p>
            <p>D <input name="q4" type="radio" value="D">Ruim</p>
            <p><textarea name="message4" placeholder="Comentário(os):" class="textarea" maxlength="600" rows="6" cols="100"></textarea></p>
        </div>

        <div class="questoes">
            <p>5) Você considera que esta sendo treinado de forma adequada pelos agentes operacionais da ECOS (Colaboradores Internos) para realizar com eficiência o seu trabalho?</p>
            <p><input name="q5" type="radio" value="A" >Sim</p>
            <p><input name="q5" type="radio" value="B">Não</p>
            <p><textarea name="message5" placeholder="Se não, justifique sua resposta:" class="textarea" maxlength="600" rows="6" cols="100"></textarea></p>
        </div>

        <div class="questoes">
            <p>6) Os equipamentos de proteção individual (EPI'S) necessários a realização do seu trabalho com excelência estão sendo providenciados em tempo hábil? Ex.: Luvas, Botas, Toucas, Etc.</p>
            <p><input name="q6" type="radio" value="A" >Sim</p>
            <p><input name="q6" type="radio" value="B">Não</p>
            <p><textarea name="message6" placeholder="Se não, justifique sua resposta:" class="textarea" maxlength="600" rows="6" cols="100"></textarea></p>
        </div>

        <div class="questoes">
            <p>7) Quais são os recursos que a ECOS poderia disponibilizar para otimizar o seu trabalho, melhorando o seu desempenho e aumentando os resultados gerais?</p>
            <p><textarea name="q7" class="textarea" maxlength="600" rows="6" cols="100"></textarea></p>
        </div>

        <div class="questoes">
            <p>8) ACEITAMOS SUAS OPINIÕES, SUGESTÕES E CRITICAS</p>
            <p><textarea name="q8" class="textarea" maxlength="600" rows="6" cols="100"></textarea></p>
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
    <script type="text/javascript" src="main.js"> </script>
</body>
</html>


