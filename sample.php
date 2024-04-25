<?php
ini_set('display_errors','1');

require_once 'vendor/autoload.php';

function preguntar_API_IA($pregunta){
    //para utilizacion del archivo .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $user_consulta = urlencode("borra el contacto Samuel");
    $url = "https://api.wit.ai/message?v=20240304&q=" . $user_consulta;
    $token = $_ENV['TOKEN_WITAI'];
    $options = array('http' => array(
        'method'  => 'GET',
        'header' => 'Authorization: Bearer '.$token
    ));
    $context  = stream_context_create($options);
    try {
        $response = file_get_contents($url, false, $context);
        $resultado = json_decode($response); //respuesta procesada

        //echo "<br>ACCION: ";
        if(count($resultado->entities->{'tsj_action:tsj_action'}) > 0){
            $accion_nombre = $resultado->entities->{'tsj_action:tsj_action'}[0]->value;
            $accion_confianza = $resultado->entities->{'tsj_action:tsj_action'}[0]->confidence;
        }else{
            $accion_nombre = "";
            $accion_confianza = "";
        }
        $accion = array('accion_nombre' => $accion_nombre, 'accion_confianza' => $accion_confianza);


        //echo "<br>OBJETO: ";
        if(count($resultado->entities->{'tsj_object:tsj_object'}) > 0){
            //
            $objeto_nombre = $resultado->entities->{'tsj_action:tsj_action'}[0]->value;
            $objeto_confianza = $resultado->entities->{'tsj_action:tsj_action'}[0]->confidence;
        }else{
            $objeto_nombre = "";
            $objeto_confianza = "";
        }
        $objeto = array('objeto_nombre' => $objeto_nombre, 'objeto_confianza' => $objeto_confianza);

        //echo "<br>CUERPO: ";
        if(count($resultado->entities->{'wit$message_body:message_body'}) > 0){
            $cuerpo_nombre = $resultado->entities->{'wit$message_body:message_body'}[0]->value;
            $cuerpo_confianza = $resultado->entities->{'wit$message_body:message_body'}[0]->confidence;
        }else{
            $cuerpo_nombre = "";
            $cuerpo_confianza = "";
        }
        $cuerpo = array('cuerpo_nombre' => $cuerpo_nombre, 'cuerpo_confianza' => $cuerpo_confianza);

        if (count($resultado->intents) > 0) {
            $intencion_nombre = $resultado->intents[0]->name;
            $intencion_confianza = $resultado->intents[0]->confidence;
        } else {
            $intencion_nombre = "";
            $intencion_confianza = "";
        }
        $intencion = array('intencion_nombre' => $intencion_nombre, 'intencion_confianza' => $intencion_confianza);

        if (count($resultado->traits) > 0){
            $trait_nombre = $resultado->traits[0]->name;
            $trait_confianza = $resultado->traits[0]->confidence;
        }else{
            $trait_nombre = "";
            $trait_confianza = "";
        }
        $trait = array('trait_nombre' => $trait_nombre, 'trait_confianza' => $trait_confianza);

        $para_enviar = array($accion,$objeto,$cuerpo,$intencion,$trait);
    
    /* 
    echo "Tiene ". count($resultado->entities->{'tsj_action:tsj_action'}) . " accion/es <br>";
    print_r($resultado->entities->{'tsj_action:tsj_action'}[0]->value);
    echo "<br>tipo de accion: ";
    print_r($resultado->entities->{'tsj_action:tsj_action'}[0]->name); */

    //obtencion del objeto 'tsj_object'
    //echo "<br>tsj_object: ";
    //print_r($resultado->entities->{'tsj_object:tsj_object'});
    /* echo "<br>OBJETO: ";
    echo "Tiene ". count($resultado->entities->{'tsj_object:tsj_object'}) . " objetos.<br>";
    print_r($resultado->entities->{'tsj_object:tsj_object'}[0]->value);
    echo "<br>tipo de objeto: ";
    print_r($resultado->entities->{'tsj_object:tsj_object'}[0]->name); */

    //obtencion de 'message_body'
    //echo "<br>Message body: ";
    //print_r($resultado->entities->{'wit$message_body:message_body'});
    /* echo "<br>CUERPO: ";
    echo "Tiene ". count($resultado->entities->{'wit$message_body:message_body'}) . " cuerpos.<br>";
    print_r($resultado->entities->{'wit$message_body:message_body'}[0]->value);
    echo "<br>tipo de cuerpo: ";
    print_r($resultado->entities->{'wit$message_body:message_body'}[0]->name); */

    //obtencion de intencion 
    /* echo "<br>INTENCION: ";
    echo "Tiene ". count($resultado->intents) . " intents.<br>";
    print_r($resultado->intents[0]->name);
    echo "<br>confianza: % ";
    print_r($resultado->intents[0]->confidence);

    //obtencion de traits
    echo "<br>traits ingresados: " ;
    print_r($resultado->traits); */

    } catch (\Throwable $th) {
        $para_enviar = "";
    }
    
    return json_encode($para_enviar);

}


    //print_r($response);
    //header('Content-Type: application/json; charset=utf-8');
    //echo ($response);
    //print_r($response);
/*
    $prueba = '{
        "entities": {
          "tsj_action:tsj_action": [
            {
              "body": "crea",
              "confidence": 0.9995,
              "end": 14,
              "entities": {},
              "id": "452450090780658",
              "name": "tsj_action",
              "role": "tsj_action",
              "start": 10,
              "type": "value",
              "value": "crear"
            }
          ],
          "tsj_object:tsj_object": [
            {
              "body": "contacto",
              "confidence": 0.9995,
              "end": 26,
              "entities": {},
              "id": "442780914794624",
              "name": "tsj_object",
              "role": "tsj_object",
              "start": 18,
              "type": "value",
              "value": "contacto"
            }
          ],
          "wit$message_body:message_body": [
            {
              "body": "Paul",
              "confidence": 0.999,
              "end": 31,
              "entities": {},
              "id": "7606973706028684",
              "name": "wit$message_body",
              "role": "message_body",
              "start": 27,
              "suggested": true,
              "type": "value",
              "value": "Paul"
            }
          ]
        },
        "intents": [
          {
            "confidence": 0.97266282722601,
            "id": "799354398293305",
            "name": "create_contact"
          }
        ],
        "text": "Por favor crea el contacto Paul",
        "traits": {}
      }';

    //$resultado = json_decode($prueba);
    //echo $resultado->entities;
    //print_r($resultado);

    //obtencion de la acción 'tsj_action'
    //echo "<br>tsj_action: ";
    //print_r($resultado->entities->{'tsj_action:tsj_action'});  //array con acciones (recorrer para preguntar si hay varios)

    //obtencion de texto ingresado 
    echo "<br>texto ingresado: ";
    print_r($resultado->text);

    echo "<br>ACCION: ";
    echo "Tiene ". count($resultado->entities->{'tsj_action:tsj_action'}) . " accion/es <br>";
    print_r($resultado->entities->{'tsj_action:tsj_action'}[0]->value);
    echo "<br>tipo de accion: ";
    print_r($resultado->entities->{'tsj_action:tsj_action'}[0]->name);

    //obtencion del objeto 'tsj_object'
    //echo "<br>tsj_object: ";
    //print_r($resultado->entities->{'tsj_object:tsj_object'});
    echo "<br>OBJETO: ";
    echo "Tiene ". count($resultado->entities->{'tsj_object:tsj_object'}) . " objetos.<br>";
    print_r($resultado->entities->{'tsj_object:tsj_object'}[0]->value);
    echo "<br>tipo de objeto: ";
    print_r($resultado->entities->{'tsj_object:tsj_object'}[0]->name);

    //obtencion de 'message_body'
    //echo "<br>Message body: ";
    //print_r($resultado->entities->{'wit$message_body:message_body'});
    echo "<br>CUERPO: ";
    echo "Tiene ". count($resultado->entities->{'wit$message_body:message_body'}) . " cuerpos.<br>";
    print_r($resultado->entities->{'wit$message_body:message_body'}[0]->value);
    echo "<br>tipo de cuerpo: ";
    print_r($resultado->entities->{'wit$message_body:message_body'}[0]->name);

    //obtencion de intencion 
    echo "<br>INTENCION: ";
    echo "Tiene ". count($resultado->intents) . " intents.<br>";
    print_r($resultado->intents[0]->name);
    echo "<br>confianza: % ";
    print_r($resultado->intents[0]->confidence);

    //obtencion de traits
    echo "<br>traits ingresados: " ;
    print_r($resultado->traits);

    echo "------------------------------------";
    echo " RESULTADO OBTENIDO";
    echo "------------------------------------";
    print_r($response);

*/
    

    //echo ($response->entities);
    //echo ($response.entities);

/* curl ^
  -H "Authorization: Bearer JBVDSLC5NERFEF5UZX3CAYAGQURZHTNI" ^
  "https://api.wit.ai/message?v=20240418&q=sube%20el%20volumen" */