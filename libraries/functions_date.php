<?php
//CALCULANDO DIAS NORMAIS
/*Abaixo vamos calcular a diferença entre duas datas. Fazemos uma reversão da maior sobre a menor 
para não termos um resultado negativo. */
function CalculaDias($xDataInicial, $xDataFinal){
   $time1 = dataToTimestamp($xDataInicial);  
   $time2 = dataToTimestamp($xDataFinal);  

   $tMaior = $time1>$time2 ? $time1 : $time2;  
   $tMenor = $time1<$time2 ? $time1 : $time2;  

   $diff = $tMaior-$tMenor;  
   $numDias = $diff/86400; //86400 é o número de segundos que 1 dia possui  
   return $numDias; 
}

//LISTA DE FERIADOS NO ANO
/*Abaixo criamos um array para registrar todos os feriados existentes durante o ano.*/
function Feriados($ano,$posicao){
   $dia = 86400;
   $datas = array();
   $datas['pascoa'] = easter_date($ano);
   $datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
   $datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
   $datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
   $feriados = array (
      '01/01',
      
      date('d/m',$datas['carnaval']),
      date('d/m',$datas['sexta_santa']),
      date('d/m',$datas['pascoa']),
      '21/04',
      '01/05',
      date('d/m',$datas['corpus_cristi']),
      '07/09', // Revolução Farroupilha \m/
      '12/10',
      '02/11',
      '15/11',
      '25/12',
   );
   
return $feriados[$posicao]."/".$ano;
}      

//FORMATA COMO TIMESTAMP
/*Esta função é bem simples, e foi criada somente para nos ajudar a formatar a data já em formato  TimeStamp facilitando nossa soma de dias para uma data qualquer.*/
function dataToTimestamp($data){
   $ano = substr($data, 6,4);
   $mes = substr($data, 3,2);
   $dia = substr($data, 0,2);
   return mktime(0, 0, 0, $mes, $dia, $ano);  
} 

//SOMA 01 DIA   
function Soma1dia($data){   
   $ano = substr($data, 6,4);
   $mes = substr($data, 3,2);
   $dia = substr($data, 0,2);
   return   date("d/m/Y", mktime(0, 0, 0, $mes, intval($dia)+1, $ano));
}

function Sub1dia($data){   
   $ano = substr($data, 6,4);
   $mes = substr($data, 3,2);
   $dia = substr($data, 0,2);
   return   date("d/m/Y", mktime(0, 0, 0, $mes, intval($dia)-1, $ano));
}


//CALCULA DIAS UTEIS
/*É nesta função que faremos o calculo. Abaixo podemos ver que faremos o cálculo normal de dias ($calculoDias), após este cálculo, faremos a comparação de dia a dia, verificando se este dia é um sábado, domingo ou feriado e em qualquer destas condições iremos incrementar 1*/

function DiasUteis($yDataInicial,$yDataFinal){

   $diaFDS = 0; //dias não úteis(Sábado=6 Domingo=0)
   $calculoDias = CalculaDias($yDataInicial, $yDataFinal); //número de dias entre a data inicial e a final
   $diasUteis = 0;
   
   while($yDataInicial!=$yDataFinal){
      $diaSemana = date("w", dataToTimestamp($yDataInicial));
      if($diaSemana==0 || $diaSemana==6){
         //se SABADO OU DOMINGO, SOMA 01
         $diaFDS++;
      }else{
      //senão vemos se este dia é FERIADO
         for($i=0; $i<12; $i++){
            if($yDataInicial==Feriados(date("Y"),$i)){
               //echo nl2br("Feriado: $yDataInicial"."\n");
               $diaFDS++;   
            }
         }
      }
      $yDataInicial = Soma1dia($yDataInicial); //dia + 1
   }
   $dias_uteis = $calculoDias - $diaFDS;

   if (is_int($dias_uteis)) {
      return $dias_uteis;
   }else {
      return intval($dias_uteis) + 1;
   }

}

function e_feriado($data_atual){
   for($i=0; $i<12; $i++){
      if($data_atual==Feriados(date("Y"),$i)){
         return 1;  // É feriado
      }
   }
   return 0; // não É feriado
}

function get_proximo_dia_util($data_atual){

   $diaSemana = date("w", dataToTimestamp($data_atual));

   if($diaSemana!=0 && $diaSemana!=6 && e_feriado($data_atual) == 0){

      return $data_atual;

   }else{

      return get_proximo_dia_util(Soma1dia($data_atual));
   }
    
}

function e_dia_util($data_atual){

   $diaSemana = date("w", dataToTimestamp($data_atual));

   if($diaSemana!=0 && $diaSemana!=6 && e_feriado($data_atual) == 0){

      return 1; // É dia util

   }else{

      return 0; // não É dia util
   }
    
}

  /** $hoje = date("d/m/Y");

   $DataInicial = "05/03/2018";
   $DataFinal = Soma1dia("02/04/2018");
   
   //CHAMADA DA FUNCAO
   $diasUteis = DiasUteis($DataInicial, $DataFinal);
   $diasNormal = CalculaDias($DataInicial, $DataFinal);


   if (is_double($diasUteis)) {
      $diasUteis = intval($diasUteis) + 1;
   }

   echo $diasUteis; **/

function converte_date($data){

   $quebra_1 = explode(" ", $data);
   $quebra_2 = explode("-", $quebra_1[0]);

   $dia = $quebra_2[2];
   $mes = $quebra_2[1];
   $ano = $quebra_2[0];

   return $dia."/".$mes."/".$ano;
}

function converte_padrao_date($date){

    $quebra = explode("/", $date);
    $dia = $quebra[0];
    $mes = $quebra[1];
    $ano = $quebra[2];

    return date($ano.'-'.$mes.'-'.$dia.' 00:00:00');
}


   /** $link = new mysqli('localhost', 'root', '', 'help_desk_ecos');
   $link->set_charset('utf8');

   if (!$link){

      die('Connect Error (' . mysqli_connecterrno() . ')' .
        mysqli_connect_error());

   }else{

      $query = "SELECT * from ordem_servico where status = '2'";
      $result = mysqli_query($link,$query);

      if (mysqli_num_rows($result) > 0) {

         while($row = mysqli_fetch_object($result)){

            $id_os = $row->id_os;
            $dt_abertura = $row->dt_abertura;

            $query_exec = "SELECT * from execucao_diaria where fk_id_ordem_servico = '$id_os'";
            $result_exec = mysqli_query($link,$query_exec);
            $exec1_dt = mysqli_fetch_object($result_exec)->data_execucao;

            $data_abertura = converte_date($dt_abertura);
            $data_exec1 = converte_date($exec1_dt);

            $dias_atendimento = DiasUteis($data_abertura, $data_exec1);

            if (is_double($dias_atendimento)) {
               $dias_atendimento = intval($dias_atendimento) + 1;
            }


            echo nl2br("$id_os Dt_ab: $data_abertura Dt_exec: $data_exec1 Dias: $dias_atendimento"."\n");

            $query_update_os = "UPDATE `ordem_servico` SET `sla_atendimento`='$dias_atendimento' WHERE `id_os` = '$id_os'";
            mysqli_query($link,$query_update_os);

         }
      }else{
         echo "busca vazia";
      }  

      echo "terminou antendimento";

      echo nl2br("---------------------------------------------------------------------------------------------------\n\n\n");


      $query = "SELECT * from ordem_servico where status = '3'";
      $result = mysqli_query($link,$query);

      if (mysqli_num_rows($result) > 0) {

         while($row = mysqli_fetch_object($result)){

            $id_os = $row->id_os;
            $dt_abertura = $row->dt_abertura;
            $dt_conclusao = $row->dt_conclusao;
            $protocolo = $row->protocolo;

            $data_abertura = converte_date($dt_abertura);
            $data_conclusao = converte_date($dt_conclusao);

            $dias_conclusao = DiasUteis($data_abertura, $data_conclusao);

            if (is_double($dias_conclusao)) {
               $dias_conclusao = intval($dias_conclusao) + 1;
            }

            if ($dias_conclusao > 30 || $dias_conclusao < 0) {
               echo nl2br("$protocolo Dt_ab: $data_abertura Dt_conc: $data_conclusao Dias: $dias_conclusao"."\n");
            }

            

            $query_update_os = "UPDATE `ordem_servico` SET `sla_conclusao`='$dias_conclusao' WHERE `id_os` = '$id_os'";
            mysqli_query($link,$query_update_os);

         }
      }else{
         echo "busca vazia";
      }   

      echo "terminou antendimento";
      

   } **/









//echo DiasUteis('31/10/2018','05/11/2018');




?>
   
     