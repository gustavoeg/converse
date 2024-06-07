<?php
  namespace Conversaciones;

  use BotMan\BotMan\BotMan;
  use BotMan\BotMan\Messages\Conversations\Conversation;
  use BotMan\BotMan\Messages\Incoming\Answer;
  use BotMan\BotMan\Messages\Outgoing\Actions\Button;
  use BotMan\BotMan\Messages\Outgoing\Question;
  require_once __DIR__.'/../datos/ConexionDB.php';
  require_once 'DependenciaConversacion.php';
  require_once 'DenunciasConversacion.php';
  require_once 'TramiteJPConversacion.php';
  require_once 'NovedadesConversacion.php';
  require_once __DIR__.'/../consulta_ia.php'; //para consultas abiertas

//  use Datos\DependenciaDAO;


class InicioConversation extends Conversation
{

    public function askMenuprincipal()
    {

        $question = Question::create('Sobre que tema desea consultar?')
        ->fallback('Unable to continue...')
        ->callbackId('create_database')
        ->addButtons([
            Button::create('Leyes usuales')->value('1'), //Enlace web
            Button::create('Guía Judicial')->value('2'),
            Button::create('Dependencias Judiciales')->value('3'), //Todas las defensorias, fiscalías, juzgados de 1ra instancia (x localidad, horario domicilio, tel, funcionarios, reseña)
            Button::create('Dependencias En Turno')->value('8'), //Todas las defensorias, fiscalías, juzgados de 1ra instancia (x localidad, horario domicilio, tel, funcionarios, reseña)
            Button::create('Dependencias de Apoyo')->value('4'),
            Button::create('Denuncias')->value('5'),  //Armar arbol -y descripcion- (no está en pagina)
            Button::create('Trámites')->value('6'),  //Juzgado de paz
            Button::create('Novedades...')->value('7')
        ]);

    $this->ask($question, function (Answer $answer, $conv) {
    // Detect if button was clicked:
    if ($answer->isInteractiveMessageReply()) {
        $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
        $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'
        
        switch ($selectedValue) {
            case '1':
                # code...
                $conv->say('Las leyes usuales se encuentran en el siguiente enlace <a href="https://www.jussantacruz.gob.ar/index.php/normativa-juridica/leyes-usuales" target="_blank">https://www.jussantacruz.gob.ar/index.php/normativa-juridica/leyes-usuales</a>');
                $this->returnOrExit($conv);
                break;

            case '2':
                # code...
                $conv->say('Seleccionada Guía Judicial');
                $this->returnOrExit($conv);
                break;

            case '3':
                # code...
                $conv->say('Por favor, seleccione la Dependencia Judicial');
                $conv->getBot()->startConversation(new DependenciaConversacion());
                break;
            case '4':
                # code...
                $conv->say('Seleccionó Dependencias de Apoyo');
                $this->returnOrExit($conv);
                break;
            case '5':
                # code...
                $conv->say('Seleccionada Denuncias');
                $conv->getBot()->startConversation(new DenunciasConversacion());
                break;
            case '6':
                # code...
                $conv->say('Por favor, seleccione el Trámite que desea realizar');
                $conv->getBot()->startConversation(new TramiteJPConversacion());
                break;
            case '7':
                # code...
                $conv->say('Por favor, seleccione la Novedad para ampliar');
                $conv->getBot()->startConversation(new NovedadesConversacion());
                break;
            case '8':
                # code...
                $conv->say('Seleccionó Dependencias En Turno');
                $this->returnOrExit($conv);
                break;
            
            default:
                # code...
                $conv->say('No selecciono opcion valida');
                break;
        }
    }else{
        //respuesta por texto ingresado (sin hacer clic en las opciones)
        $selectedValue = $answer->getValue();
        $selectedText = $answer->getText();

        //se puede hacer PLN para el texto ingresado
        $respuesta_api = preguntar_API_IA($selectedText);

        //procesamiento de la respuesta del api y se obtiene la respuesta en texto para ser mostrado en la conversacion
        $dependencia_texto = textoRespuestaDependenciaAPI($respuesta_api);

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
/*     private function textoRespuestaDependenciaAPI($respuesta_api){
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
 */
    public function run()
    {
        // This will be called immediately
        $this->askMenuprincipal();
    }
}