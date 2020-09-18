<?php



$link = new mysqli('localhost', 'root', 'mericunofoide', 'help_desk_ecos');
$link->set_charset('utf8');

$query_escola = "SELECT * FROM escola";

$result_escola = $link->query($query_escola);

if(mysqli_num_rows($result_escola) > 0){

    $cont = 0;

    while($row_escola = mysqli_fetch_object($result_escola)){ 

        $serial = $row_escola->serial_b;
        $id_escola = $row_escola->id_escola;

        if (strlen($serial) < 17 and $serial != "") {

            $novo = $serial;

            for ($i=0; $i < (17-strlen($serial)); $i++) { 
                $novo = "0".$novo;
            }

            $query_update = "UPDATE `escola` SET `serial_b`= '$novo' WHERE id_escola = '$id_escola'";

            $result_update = $link->query($query_update);

            $cont++;


        }
    }
}

echo $cont." atualizados, fim";














































?>