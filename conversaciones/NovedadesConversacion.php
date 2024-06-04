<?php

namespace Conversaciones;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Datos\NovedadDAO;
use Datos\NovedadDTO;

require_once 'InicioConversation.php';

require __DIR__.'/../datos/NovedadDAO.php';

class NovedadesConversacion extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        //
        $this->askNovedades();
    }

    private function askNovedades(){
        /**
         * Parte dinámica, con consulta a la BD postgres meidante uso de clases DAO DTO
         */

        try {
            $instance = \ConexionDB::getInstance();
            // Crear una instancia del DAO
            $novedadesDAO = new \Datos\NovedadDAO($instance->getConexion());

            $novedades = $novedadesDAO->getNovedades();

            if ($novedades) {
                $i = 0;
                foreach ($novedades as $novedad) {
                    $i++;
                    //$question->addButtons(Button::create($localidad)->value($i));
                    $array[] = Button::create($novedad->getNovedad())->value($novedad->getId());
                }
                $question = Question::create('Novedades disponibles')
                ->fallback('No se pudo determinar la novedad seleccionada')
                ->callbackId('ask_reason')
                ->addButtons($array);

                $this->ask($question, function (Answer $answer, $conv){
                    // Detect if button was clicked:
                    if ($answer->isInteractiveMessageReply()) {
                        //$selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                        $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'//
                        
                        $novedad_seleccionado = $answer->getValue(); //"novedad 1";
                        $instance = \ConexionDB::getInstance();
                        $novedadDAO = new NovedadDAO($instance->getConexion());
                        $unaNovedad = $novedadDAO->getNovedadById($novedad_seleccionado);
                        //print_r($dependencias);
                        $novedad_texto = "";
                        if ($unaNovedad) {
                            $novedad_texto = "Novedad para " . $novedad_seleccionado . "<br>";
                            $novedad_texto .= "<br>Fecha Inicio:" . $unaNovedad->getFecha_inicio();
                            $novedad_texto .= "<br>Fecha Fin: " . $unaNovedad->getFecha_fin();
                            $novedad_texto .= "<br>Novedad: " . $unaNovedad->getNovedad();
                            $novedad_texto .= "<br>Descripcion: " . $unaNovedad->getDescripcion();
                            $novedad_texto .= "<br>Tipo: " . $unaNovedad->getTipo();
                            $novedad_texto .= "<br>Enlace: " . $unaNovedad->getEnlace();
                            $novedad_texto .= "<br><br>";
                        } else {
                            $novedad_texto .= "No se encontraron novedades para " . $novedad_seleccionado;
                        }
                        $conv->say($novedad_texto);

                        $this->returnOrExit($conv);  //preguntar para ir de nuevo al menu o terminar
                        
                    }
                    });
            } else {
                $this->say("No hay novedades");
            }

        } catch (\PDOException $e) {
            $this->say("Por el momento no puedo acceder a los datos");
            error_log(print_r($e->getMessage(), true), 3, $_ENV['LOG_PATH']);
        }

    }

    private function returnOrExit($conversation){
        
        $question = Question::create("¿Deseas continuar consultando novedades?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Si')->value('si'),
                Button::create('No')->value('no'),
            ]);
            
        $this->ask($question, function (Answer $answer) use($conversation) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'si') {
                    $this->getBot()->startConversation(new $conversation());
                    
                } elseif ($answer->getValue() === 'no'){
                    //$this->say('Hasta luego...');
                    $this->getBot()->startConversation(new InicioConversation());
                }else{
                    //respuesta desconocida
                    $this->say('Respuesta desconocida...');
                }
            }else{
                //no es respuesta interactiva, ver si es de teclado
                if (strtolower($answer->getValue()) === 'si') {
                    $this->getBot()->startConversation(new $conversation());
                    
                } elseif (strtolower( $answer->getValue()) === 'no'){
                    $this->say('Despedida...');
                }else{
                    //respuesta desconocida
                    $this->say('Respuesta desconocida...');
                }
                
            }
        });
    }
}
