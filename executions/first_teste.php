<?php



$filename = 'Atividades_Mensais.csv';
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="'.$filename.'";');


function array_to_csv_download($array, $filename, $delimiter=";") {



    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
} 

$year = $_POST['ano'];
$month = $_POST['mes'];

$point_one = 1;
$point_two = 2;
$point_three = 3;
$point_four = 4;
$point_five = 5;
$point_six = 6;
$point_seven = 7;
$point_eight = 8;
$point_nine = 9;
$point_ten = 10;
$point_eleven = 11;
$point_twelve = 12;

$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$dt = $year.'-'.$month;

if (!$link){

  die('Connect Error (' . mysqli_connecterrno() . ')' .
    mysqli_connect_error());

}else{

    array_to_csv_download(array(['',$dt]),$filename);

    // $query_point_one: Vistorias Técnicas Programadas em Escolas - Lista as escolas do ponto 01 e 03

    array_to_csv_download(array(['','PONTO 01']),$filename);

    $query = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_one' OR s.fk_id_classificacao_chamado = '$point_three') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    //$query = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE s.fk_id_classificacao_chamado = '$point_one' OR s.fk_id_classificacao_chamado = '$point_three' AND o.dt_conclusao LIKE '$dt%' ORDER BY gre";

    $result = $link->query($query);

    if(mysqli_num_rows($result) > 0){

        $escolas = array();
        $cidades = array();
        $gres = array();
        $protocolos = array();
        $sub_motivos = array();

        while($row = mysqli_fetch_object($result)){

            $school = utf8_decode($row->nome_escola);
            $protocol = $row->protocolo;

            $key = array_search($school, $escolas);

            if($key != ''){
                $protocolos[$key].="-".$protocol;
            }else{
                array_push($escolas, $school);
                array_push($cidades,utf8_decode($row->cidade));
                array_push($gres,utf8_decode($row->gre));
                array_push($protocolos,$protocol);
                array_push($sub_motivos,utf8_decode($row->sub_motivo));
            }
            $dt_finish = ($row->dt_conclusao);

        }
        for ($i=0; $i < count($escolas); $i++) { 

            array_to_csv_download(array([$i+1,$escolas[$i],$cidades[$i],$gres[$i],$protocolos[$i],$sub_motivos[$i]]),$filename);
        }
        

    }else{

        array_to_csv_download(array(['','Nenhum protocolo encontrado.']),$filename);
    }

    array_to_csv_download(array(['PONTO 02']),$filename);

    $query02 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_nine') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result02 = $link->query($query02);

    if(mysqli_num_rows($result02) > 0){
        while($row = mysqli_fetch_object($result02)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);
        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }


    array_to_csv_download(array(['PONTO 03']),$filename);


    $query03 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_three') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result03 = $link->query($query03);

    if(mysqli_num_rows($result03) > 0){
        while($row = mysqli_fetch_object($result03)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);
        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }

    array_to_csv_download(array(['PONTO 04']),$filename);

    $query04 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_one') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result04 = $link->query($query04);

    if(mysqli_num_rows($result04) > 0){
        while($row = mysqli_fetch_object($result04)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);
        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }

    array_to_csv_download(array(['PONTO 05']),$filename);


    $query05 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_five' OR s.fk_id_classificacao_chamado = '$point_six' OR s.fk_id_classificacao_chamado = '$point_seven') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result05 = $link->query($query05);

    if(mysqli_num_rows($result05) > 0){
        while($row = mysqli_fetch_object($result05)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);
        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }

    array_to_csv_download(array(['PONTO 06']),$filename);


    $query06 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_six') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result06 = $link->query($query06);

    if(mysqli_num_rows($result06) > 0){
        while($row = mysqli_fetch_object($result06)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);

        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }


    array_to_csv_download(array(['PONTO 07']),$filename);


    $query07 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_seven') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result07 = $link->query($query07);

    if(mysqli_num_rows($result07) > 0){
        while($row = mysqli_fetch_object($result07)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);

        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }


    array_to_csv_download(array(['PONTO 08']),$filename);


    $query08 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_eight') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result08 = $link->query($query08);

    if(mysqli_num_rows($result08) > 0){
        while($row = mysqli_fetch_object($result08)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);

        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }


    array_to_csv_download(array(['PONTO 09']),$filename);

    $query09 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_nine') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result09 = $link->query($query09);

    if(mysqli_num_rows($result09) > 0){
        while($row = mysqli_fetch_object($result09)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);

        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }


    array_to_csv_download(array(['PONTO 10']),$filename);


    $query10 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_ten') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result10 = $link->query($query10);

    if(mysqli_num_rows($result10) > 0){
        while($row = mysqli_fetch_object($result10)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);

        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }

    array_to_csv_download(array(['PONTO 11']),$filename);


    $query11 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_eleven') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result11 = $link->query($query11);

    if(mysqli_num_rows($result11) > 0){
        while($row = mysqli_fetch_object($result11)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);
        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }


    array_to_csv_download(array(['PONTO 12']),$filename);


    $query12 = "SELECT o.protocolo, e.nome_escola, e.cidade, e.gre, s.sub_motivo, o.dt_conclusao FROM ordem_servico o JOIN escola e ON e.id_escola = o.fk_id_nome_escola JOIN sub_motivo_chamado s ON o.fk_id_motivo_os = s.id_sub_motivo WHERE (s.fk_id_classificacao_chamado = '$point_five' OR s.fk_id_classificacao_chamado = '$point_six' OR s.fk_id_classificacao_chamado = '$point_seven' OR s.fk_id_classificacao_chamado = '$point_eight' OR s.fk_id_classificacao_chamado = '$point_nine' OR s.fk_id_classificacao_chamado = '$point_ten') AND (YEAR(dt_conclusao) = '$year' AND MONTH(dt_conclusao) = '$month') ORDER BY gre";

    $result12 = $link->query($query12);

    if(mysqli_num_rows($result12) > 0){
        while($row = mysqli_fetch_object($result12)){
            $school = utf8_decode($row->nome_escola);
            $city = utf8_decode($row->cidade);
            $gre = utf8_decode($row->gre);
            $protocol = $row->protocolo;
            $sub_motivo = utf8_decode($row->sub_motivo);
            $dt_finish = ($row->dt_conclusao);
            
            array_to_csv_download(array([$school,$city,$gre,$protocol,$sub_motivo]),$filename);
           
        }
    }else{

        array_to_csv_download(array(['Nenhum protocolo encontrado.']),$filename);
    }
}
?>
