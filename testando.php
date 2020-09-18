<?php

$senha = '123456';

$options = ['cost' => 10,];

$senha_segura = password_hash($senha, PASSWORD_DEFAULT, $options);

echo $senha_segura;

/**if (password_verify($senha,$senha_segura)) {
	//echo "okkkkkk";
	//var_dump(password_get_info($senha_segura));
	//echo password_decript($senha_segura);
}else{
	echo "erro";
}


$array = array('blue','red','green','red');

$key1 = array_search('green', $array); // $key = 2;
$key2 = array_search('aa', $array);   // $key = 1;

echo $key2; **/

?> 
