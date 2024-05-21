<?php
ini_set('display_errors','1');

require_once 'vendor/autoload.php';

function preguntar_API_IA($pregunta){
    //para utilizacion del archivo .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    //$user_consulta = urlencode("borra el contacto Samuel");
    $user_consulta = urlencode($pregunta);
    $url = "https://api.wit.ai/message?v=20240304&q=" . $user_consulta;
    $token = $_ENV['TOKEN_WITAI'];
    $options = array('http' => array(
        'method'  => 'GET',
        'header' => 'Authorization: Bearer '.$token
    ));
    $context  = stream_context_create($options);
    try {
        $response = file_get_contents($url, false, $context);
        $resultado = json_decode($response,true); //respuesta procesada
        //print_r($resultado);
 
        //echo "<br>ACCION: ";
        if(count($resultado['entities']['tsj_direccion:tsj_direccion']) > 0){
            $accion_nombre = $resultado['entities']['tsj_direccion:tsj_direccion'][0]['value'];
            $accion_confianza = $resultado['entities']['tsj_direccion:tsj_direccion'][0]['confidence'];
            $accion = array('direccion' => $accion_nombre, 'accion_confianza' => $accion_confianza);
        }else{
            $accion_nombre = "";
            $accion_confianza = "";
            $accion = "";
        }
        

        //echo "<br>OBJETO: ";
        if(count($resultado['entities']['tsj_dependencia:tsj_dependencia']) > 0){
            //
            $dependencia_nombre = $resultado['entities']['tsj_dependencia:tsj_dependencia'][0]['value'];
            $dependencia_confianza = $resultado['entities']['tsj_dependencia:tsj_dependencia'][0]['confidence'];
            $dependencia_confianza = ((float )$dependencia_confianza) * 100;
            $dependencia = array('dependencia_nombre' => $dependencia_nombre, 'dependencia_confianza' => $dependencia_confianza);
        }else{
            $dependencia_nombre = "";
            $dependencia_confianza = "";
            $dependencia = "";
        }

        //echo "<br>Localidad: ";
        if(count($resultado['entities']['tsj_localidad:tsj_localidad']) > 0){
            //
            $objeto_nombre = $resultado['entities']['tsj_localidad:tsj_localidad'][0]['value'];
            $objeto_confianza = $resultado['entities']['tsj_localidad:tsj_localidad'][0]['confidence'];
            $objeto_confianza = ((float )$objeto_confianza) * 100;
            $objeto = array('localidad_nombre' => $objeto_nombre, 'localidad_confianza' => $objeto_confianza);
        }else{
            $objeto_nombre = "";
            $objeto_confianza = "";
            $objeto = "";
        }
        
 
        if (count($resultado['intents']) > 0) {
            $intencion_nombre = $resultado['intents'][0]['name'];
            $intencion_confianza = $resultado['intents'][0]['confidence'];
            $intencion = array('intencion_nombre' => $intencion_nombre, 'intencion_confianza' => $intencion_confianza);
        } else {
            $intencion_nombre = "";
            $intencion_confianza = "";
            $intencion = "";
        }
        

        if (!empty($resultado['traits']) ){
            $trait_nombre = $resultado['traits'][0]['name'];
            $trait_confianza = $resultado['traits'][0]['confidence'];
            $trait = array('trait_nombre' => $trait_nombre, 'trait_confianza' => $trait_confianza);
        }else{
            $trait_nombre = "";
            $trait_confianza = "";
            $trait = "";
        }
        

        $para_enviar = array(
            'tipo' => $accion,
            'localidad' => $objeto,
            'dependencia' => $dependencia,
            'intencion' => $intencion,
            'error' => false);

    } catch (\Exception $ex) {
        $para_enviar = array('error' => true);
        error_log(print_r($ex->getMessage(), true), 3, $_ENV['LOG_PATH']);
    }
    
    return json_encode($para_enviar);
}
