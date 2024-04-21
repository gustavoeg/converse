<?php
ini_set('display_errors','1');
/* $user_consulta = urlencode("Crear el contacto gustavo");
    $url = "https://api.wit.ai/message?v=20240304&q=" . $user_consulta;
    $token = "42OKHWM7P7YSJV4QYAWKBILFDE5HV5LA";
$options = array('http' => array(
    'method'  => 'GET',
    'header' => 'Authorization: Bearer '.$token
));
$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);

print_r($response);
*/
var_dump(
	pg_connect("
		host = localhost
		port = 5432
		dbname = postgres
		user = postgres
		password = root
	"));