<?php
  namespace Conversaciones;

  use BotMan\BotMan\BotMan;
  use BotMan\BotMan\Messages\Conversations\Conversation;
  use BotMan\BotMan\Messages\Incoming\Answer;
  use BotMan\BotMan\Messages\Outgoing\Actions\Button;
  use BotMan\BotMan\Messages\Outgoing\Question;
  require_once __DIR__.'/../datos/ConexionDB.php';
  require_once 'DependenciaConversacion.php';
  require_once 'TramiteJPConversacion.php';
  require 'consulta_ia.php'; //para consultas abiertas

  use Datos\DependenciaDAO;


class DenunciasConversacion extends Conversation
{
    public function askMenuDenuncias()
    {

        $question = Question::create('Qué denuncia desea realizar?')
        ->fallback('No se puede continuar...')
        ->callbackId('create_database')
        ->addButtons([
            Button::create('Fuiste estafado')->value('1'), 
            Button::create('Sos víctima')->value('2'),
            Button::create('Tuviste un accidente')->value('3'),
            Button::create('Sufriste abuso')->value('4'),
            Button::create('problema con vecinos')->value('5'),
            Button::create('Contratos')->value('6'), 
            Button::create('Régimen de visitas')->value('7'),
            Button::create('Cuota alimentaria')->value('8'), 
        ]);

    $this->ask($question, function (Answer $answer, $conv) {
    // Detect if button was clicked:
    if ($answer->isInteractiveMessageReply()) {
        $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
        
        switch ($selectedValue) {
            case '1':
                # code...
                $conv->say('Procedimiento para "Fuiste estafado"');
                $this->returnOrExit($conv);
                break;

            case '2':
                # code...
                $conv->say('Procedimiento para "Sos víctima"');
                $this->returnOrExit($conv);
                break;

            case '3':
                # code...
                $conv->say('Procedimiento para "Tuviste un accidente"');
                $this->returnOrExit($conv);
                break;
            case '4':
                # code...
                $conv->say('Procedimiento para "Sufriste abuso"');
                $this->returnOrExit($conv);
                break;
            case '5':
                # code...
                $conv->say('Procedimiento para "Problema con vecinos"');
                $this->returnOrExit($conv);
                break;
            case '6':
                # code...
                $conv->say('Procedimiento para "Contratos"');
                $this->returnOrExit($conv);
                break;
                
            case '7':
                # code...
                $conv->say('Procedimiento para "Régimen de visitas"');
                $this->returnOrExit($conv);
                break;

            case '8':
                # code...
                $conv->say('Procedimiento para "Cuota alimentaria"');
                $this->returnOrExit($conv);
                break;
            
            default:
                # code...
                $conv->say('No selecciono opcion valida');
                $this->returnOrExit($conv);
                break;
        }
    }else{
        //respuesta por texto ingresado (sin hacer clic en las opciones)
        $selectedValue = $answer->getValue();
        $selectedText = $answer->getText();

        //se puede hacer PLN para el texto ingresado
        $respuesta_api = preguntar_API_IA($selectedText);

        //procesamiento de la respuesta del api y se obtiene la respuesta en texto para ser mostrado en la conversacion
        $dependencia_texto = $this->textoRespuestaAPI($respuesta_api);

        $conv->say($dependencia_texto);
        
        $this->returnOrExit($conv);  //¿otra consulta?

    }
    });
    }

    private function returnOrExit($conversation){
        
        $question = Question::create("¿Deseas realizar otras consultas?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Si')->value('si'),
                Button::create('No')->value('no'),
            ]);
            
        $this->ask($question, function (Answer $answer) use($conversation) {

            if (strtolower($answer->getValue()) === 'si') {
                $this->getBot()->startConversation(new $conversation());
                
            } elseif (strtolower($answer->getValue()) === 'no'){
                $this->say('Fue un placer conversar. Si requiere mi ayuda de nuevo, solo vuelva a escribir "hola"...');
            }else{
                //respuesta desconocida
                $this->say('Respuesta desconocida...');
            }
        
        });
    }

    /**
     * Toma la respuesta obtenida de consultar la API
     * evalua los items necesarios y realiza la consulta a la BD
     * arma y prepara el texto con la respuesta que será visible en la convsersacion.
     */
    private function textoRespuestaAPI($respuesta_api){
        $resp_json = json_decode($respuesta_api);
        $dependencia_texto = "";
        if(isset($resp_json->dependencia->dependencia_nombre) && isset($resp_json->localidad->localidad_nombre)){
            //hay una dependencia al menos
            $instance = \ConexionDB::getInstance();
            $dependenciaDAO = new DependenciaDAO($instance->getConexion());
            $dependencias = $dependenciaDAO->getRespuestaAPI($resp_json);
            //print_r($dependencias);
            if ($dependencias) {
                $dependencia_texto = "Información detectada para su consulta: Dependencia(".
                $resp_json->dependencia->dependencia_nombre.") Localidad(". $resp_json->localidad->localidad_nombre. "). A continuación los resultados.<br>";
                foreach ($dependencias as $dependencia) {
                    $dependencia_texto .= "<br>" . $dependencia->getDependencia();
                    $dependencia_texto .= "<br>Dependencia localidad: " . $dependencia->getLocalidad();
                    $dependencia_texto .= "<br>Autoridad: " . $dependencia->getAutoridad();
                    $dependencia_texto .= "<br>Telefonos: " . $dependencia->getTelefonos();
                    $dependencia_texto .= "<br>";
                }
                
            } else {
                $dependencia_texto .= "No se encontraron dependencias para su consulta";
                error_log("\n" . date('Y-m-d h:i:s') . " | " . print_r($respuesta_api, true), 3, $_ENV['LOG_PATH']);
            }
        }else{
            $dependencia_texto .= "No se pudo interpretar la consulta. API:";
            $dependencia_texto .= $respuesta_api;
            error_log("\n" . date('Y-m-d h:i:s') . " | " . print_r($respuesta_api, true), 3, $_ENV['LOG_PATH']);
        }
        return $dependencia_texto;
    }

    public function run()
    {
        // This will be called immediately
        $this->askMenuDenuncias();
    }
}