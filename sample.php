<?php
ini_set('display_errors','1');

$user_consulta = urlencode("Por favor crea el contacto Paul");
    $url = "https://api.wit.ai/message?v=20240304&q=" . $user_consulta;
    $token = "42OKHWM7P7YSJV4QYAWKBILFDE5HV5LA";
    $options = array('http' => array(
        'method'  => 'GET',
        'header' => 'Authorization: Bearer '.$token
    ));
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    //print_r($response);
    header('Content-Type: application/json; charset=utf-8');
    //echo ($response);
    print_r($response);

    $resultado = json_decode($response);
    //echo $resultado->entities;
    //print_r($resultado->entities);

    print_r($resultado->entities);

    //echo ($response->entities);
    //echo ($response.entities);

/* curl ^
  -H "Authorization: Bearer JBVDSLC5NERFEF5UZX3CAYAGQURZHTNI" ^
  "https://api.wit.ai/message?v=20240418&q=sube%20el%20volumen" */