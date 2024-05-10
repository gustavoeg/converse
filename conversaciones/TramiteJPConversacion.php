<?php

namespace Conversaciones;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Datos\TramitesJPDAO;
require_once 'InicioConversation.php';

require __DIR__.'/../datos/TramitesJPDAO.php';
//require_once __DIR__.'/../datos/ConexionDB.php';

class TramiteJPConversacion extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        //
        $this->askTramite();
    }

    private function askTramite(){
        /**
         * Parte dinámica, con consulta a la BD postgres meidante uso de clases DAO DTO
         */

        try {
            $instance = \ConexionDB::getInstance();
            // Crear una instancia del DAO
            $tramiteDAO = new \Datos\TramitesJPDAO($instance->getConexion());

            $sectores = $tramiteDAO->getSectores();

            if ($sectores) {
                $i = 0;
                foreach ($sectores as $sector) {
                    $i++;
                    //$question->addButtons(Button::create($localidad)->value($i));
                    $array[] = Button::create($sector)->value($sector);
                }
                $question = Question::create('¿Sobre qué trámite desea consultar?')
                ->fallback('No se pudo identificar el trámite ingresado')
                ->callbackId('ask_reason')
                ->addButtons($array);

                $this->ask($question, function (Answer $answer, $conv){
                    // Detect if button was clicked:
                    if ($answer->isInteractiveMessageReply()) {
                        //$selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                        $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'//
                        
                        $tramiteSector_seleccionado = $answer->getValue(); //"RIO GALLEGOS";
                        $instance = \ConexionDB::getInstance();
                        $tramiteDAO = new TramitesJPDAO($instance->getConexion());
                        $tramites = $tramiteDAO->getTramiteBySector($tramiteSector_seleccionado);
                        //print_r($dependencias);
                        $sector_texto = "";
                        if ($tramites) {
                            $sector_texto = "Sectores/tramites para " . $tramiteSector_seleccionado . "<br>";
                            foreach ($tramites as $tramite) {
                                $sector_texto .= "<br>" . $tramite->getSector();
                                $sector_texto .= "<br>Trámite: " . $tramite->getTramite();
                                $sector_texto .= "<br>Costo: " . $tramite->getCosto();
                                $sector_texto .= "<br>Requisitos: " . $tramite->getRequisitos();
                                $sector_texto .= "<br><br>";
                            }
                            
                        } else {
                            $sector_texto .= "No se encontraron dependencias para " . $tramiteSector_seleccionado;
                        }
                        $conv->say($sector_texto);

                        $this->returnOrExit($conv);  //preguntar para ir de nuevo al menu o terminar
                        
                    }
                    });
            } else {
                $this->say("No hay sectores");
            }

        } catch (\PDOException $e) {
            //echo "Error de conexión: " . $e->getMessage();
            $this->say("Por el momento no puedo acceder a los datos");
            $this->say("Error de conexión: " . $e->getMessage());
        }

    }

    private function returnOrExit($conversation){
        
        $question = Question::create("¿Deseas continuar consultando trámites?")
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
