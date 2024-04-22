<?php

namespace Conversaciones;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
require_once 'InicioConversation.php';

require __DIR__.'/../datos/DependenciaDAO.php';
require __DIR__.'/../datos/ConexionDB.php';

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
                    $array[] = Button::create($localidad)->value($i);
                }
            } else {
                echo "No hay localidades";
            }
        

        $question = Question::create('¿Sobre qué dependencia desea consultar?')
        ->fallback('No se pudo identificar la dependencia ingresada')
        ->callbackId('ask_reason')
        ->addButtons($array);

        $this->ask($question, function (Answer $answer, $conv) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
                $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'
        
                switch ($selectedValue) {
                    case '1':
                        # code...
                        $conv->say('PDF con el protocolo disponible en página web.');
                        $this->returnOrExit($conv);
                        break;
        
                    case '2':
                        # code...
                        $conv->say('Cámara Civil - Río Gallegos. Domicilio: España esq. pasaje Feruglio. Dias no laborables (Feriados nacionales, provinciales y municipales – Río Gallegos 19 de Diciembre). Teléfono: 02966-420825');
                        $this->returnOrExit($conv);
                        break;
        
                    case '3':
                        # code...
                        $conv->say('Seleccionada Dependencias Judiciales');
                        
                        break;
                    case '4':
                        # code...
                        $conv->say('Seleccionada Dependencias de Apoyo');
                        break;
                    case '5':
                        # code...
                        $conv->say('Seleccionada Denuncias');
                        break;
                    case '6':
                        # code...
                        $conv->say('Seleccionada Trámites');
                        break;
                        
                    case '7':
                        # code...
                        $conv->say('Seleccionada Novedades... (ELECTORAL)');
                        break;
                    
                    default:
                        # code...
                        $conv->say('No selecciono opcion valida');
                        break;
                }
            }
            });

        } catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            $this->say("No hay localidades por consultar");
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
