<?php

namespace Conversaciones;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Datos\DependenciaDAO;
use Datos\TurnoDAO;
use Datos\TurnoDTO;

require_once 'InicioConversation.php';

require __DIR__.'/../datos/TurnoDAO.php';

class TurnoConversacion extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        //
        $this->askTurnos();
    }

    private function askTurnos(){
        /**
         * Parte dinámica, con consulta a la BD postgres meidante uso de clases DAO DTO
         */

        try {
            $instance = \ConexionDB::getInstance();
            // Crear una instancia del DAO
            $turnosDAO = new \Datos\TurnoDAO($instance->getConexion());

            $turnos = $turnosDAO->getTurnos();

            if ($turnos) {
                $i = 0;
                foreach ($turnos as $turno) {
                    $i++;
                    //$question->addButtons(Button::create($localidad)->value($i));
                    $array[] = Button::create($turno->getJuzgado_nombre())->value($turno->getId_juzgado());
                }
                $question = Question::create('Dependencias de Turno disponibles')
                ->fallback('No se pudo determinar el turno seleccionado')
                ->callbackId('ask_reason')
                ->addButtons($array);

                $this->ask($question, function (Answer $answer, $conv){
                    // Detect if button was clicked:
                    if ($answer->isInteractiveMessageReply()) {
                        //$selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                        $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'//
                        
                        $turno_seleccionado = $answer->getValue(); //"novedad 1";
                        $instance = \ConexionDB::getInstance();
                        $dependenciaDAO = new DependenciaDAO($instance->getConexion());
                        $unaDependencia = $dependenciaDAO->getDependenciaById($turno_seleccionado);
                        //print_r($dependencias);
                        $dependencia_texto = "";
                        if ($unaDependencia) {
                            //$dependencia_texto = "Dependencias para " . $turno_seleccionado . "<br>";
                            
                            //$dependencia_texto .= "Dependencia encontrada id:" . $dependencia->getId();
                            $dependencia_texto .=  $unaDependencia->getDependencia();
                            $dependencia_texto .= "<br>Localidad: " . $unaDependencia->getLocalidad();
                            $dependencia_texto .= "<br>Autoridad: " . $unaDependencia->getAutoridad();
                            $dependencia_texto .= "<br>Telefonos: " . $unaDependencia->getTelefonos();
                            $dependencia_texto .= "<br><br>";
                            
                            
                        } else {
                            $dependencia_texto .= "No se encontraron turnos para " . $turno_seleccionado;
                        }
                        $conv->say($dependencia_texto);

                        $this->returnOrExit($conv);  //preguntar para ir de nuevo al menu o terminar
                        
                    }
                    });
            } else {
                $this->say("No hay dependencias de turno");
                $this->getBot()->startConversation(new InicioConversation());
            }

        } catch (\PDOException $e) {
            $this->say("Por el momento no puedo acceder a los datos");
            error_log(print_r($e->getMessage(), true), 3, $_ENV['LOG_PATH']);
        }

    }

    private function returnOrExit($conversation){
        
        $question = Question::create("¿Deseas continuar consultando tunos?")
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
