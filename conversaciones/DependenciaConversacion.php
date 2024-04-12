<?php

namespace Conversaciones;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
require_once 'InicioConversation.php';

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
        $question = Question::create('¿Sobre qué dependencia desea consultar?')
        ->fallback('No se pudo identificar la dependencia ingresada')
        ->callbackId('ask_reason')
        ->addButtons([
            Button::create('Protocolo Dependencias Judiciales')->value('1'),
            Button::create('Cámara Civil - Río Gallegos')->value('2'),
            Button::create('Cámara Civil - Caleta Olivia')->value('3'),
            Button::create('Defensoría General ante el Excmo. TSJ')->value('4'),
            Button::create('Defensoría Oficial de Puerto San Julián')->value('5'),
            Button::create('Juzgado Civil Nº 1 Río Gallegos')->value('6'),
            Button::create('Juzgado Civil Nº 2 Río Gallegos')->value('7'),
            Button::create('Juzgado de Paz de Río Gallegos')->value('8'),
            Button::create('Juzgado Civil y Familia - Las Heras')->value('9'),
            Button::create('Juzgado de Recursos - Río Gallegos')->value('10'),
            Button::create('Reg. Público de Comercio - El Calafate')->value('11'),
        ]);

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
