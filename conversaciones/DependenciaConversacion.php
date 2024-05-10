<?php

namespace Conversaciones;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Datos\DependenciaDAO;
require_once 'InicioConversation.php';

require __DIR__.'/../datos/DependenciaDAO.php';
//require_once __DIR__.'/../datos/ConexionDB.php';

class DependenciaConversacion extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        //
        $this->askDependencia();
    }

    private function askDependencia(){
        /**
         * Parte dinámica, con consulta a la BD postgres meidante uso de clases DAO DTO
         */

        try {
            $instance = \ConexionDB::getInstance();
            // Crear una instancia del DAO
            $dependenciaDAO = new \Datos\DependenciaDAO($instance->getConexion());

            $localidades = $dependenciaDAO->getLocalidades();

            if ($localidades) {
                $i = 0;
                foreach ($localidades as $localidad) {
                    $i++;
                    //$question->addButtons(Button::create($localidad)->value($i));
                    $array[] = Button::create($localidad)->value($localidad);
                }
                $question = Question::create('¿Sobre qué dependencia desea consultar?')
                ->fallback('No se pudo identificar la dependencia ingresada')
                ->callbackId('ask_reason')
                ->addButtons($array);

                $this->ask($question, function (Answer $answer, $conv){
                    // Detect if button was clicked:
                    if ($answer->isInteractiveMessageReply()) {
                        //$selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                        $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'//
                        
                        $dependenciaLocalidad_seleccionada = $answer->getValue(); //"RIO GALLEGOS";
                        $instance = \ConexionDB::getInstance();
                        $dependenciaDAO = new DependenciaDAO($instance->getConexion());
                        $dependencias = $dependenciaDAO->getDependenciaByLocalidad($dependenciaLocalidad_seleccionada);
                        //print_r($dependencias);
                        $dependencia_texto = "";
                        if ($dependencias) {
                            $dependencia_texto = "Dependencias para " . $dependenciaLocalidad_seleccionada . "<br>";
                            foreach ($dependencias as $dependencia) {
                                //$dependencia_texto .= "Dependencia encontrada id:" . $dependencia->getId();
                                $dependencia_texto .= "<br>" . $dependencia->getDependencia();
                                //$dependencia_texto .= "<br>Dependencia localidad: " . $dependencia->getLocalidad();
                                $dependencia_texto .= "<br>Autoridad: " . $dependencia->getAutoridad();
                                $dependencia_texto .= "<br>Telefonos: " . $dependencia->getTelefonos();
                                $dependencia_texto .= "<br><br>";
                            }
                            
                        } else {
                            $dependencia_texto .= "No se encontraron dependencias para " . $dependenciaLocalidad_seleccionada;
                        }
                        $conv->say($dependencia_texto);

                        $this->returnOrExit($conv);  //preguntar para ir de nuevo al menu o terminar
                        
                    }
                    });
            } else {
                $this->say("No hay localidades");
            }

        } catch (\PDOException $e) {
            $this->say("Por el momento no puedo acceder a los datos");
            error_log(print_r($e->getMessage(), true), 3, $_ENV['LOG_PATH']);
        }

    }

    private function returnOrExit($conversation){
        
        $question = Question::create("¿Deseas continuar consultando dependencias?")
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
