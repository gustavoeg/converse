<?php
  namespace Conversaciones;

  use BotMan\BotMan\BotMan;
  use BotMan\BotMan\Messages\Conversations\Conversation;
  use BotMan\BotMan\Messages\Incoming\Answer;
  use BotMan\BotMan\Messages\Outgoing\Actions\Button;
  use BotMan\BotMan\Messages\Outgoing\Question;

class InicioConversation extends Conversation
{
    protected $firstname;

    protected $email;

    public function askFirstname()
    {

        $question = Question::create('Sobre que tema desea consultar?')
        ->fallback('Unable to continue...')
        ->callbackId('create_database')
        ->addButtons([
            Button::create('Leyes usuales')->value('1'), //Enlace web
            Button::create('Guía Judicial')->value('2'),
            Button::create('Dependencias Judiciales')->value('3'), //Todas las defensorias, fiscalías, juzgados de 1ra instancia (x localidad, horario domicilio, tel, funcionarios, reseña)
            Button::create('Dependencias de Apoyo')->value('4'),
            Button::create('Denuncias')->value('5'),  //Armar arbol -y descripcion- (no está en pagina)
            Button::create('Trámites')->value('6'),  //Juzgado de paz
            Button::create('Novedades... (ELECTORAL)')->value('7')
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
                break;

            case '2':
                # code...
                $conv->say('Seleccionada Guía Judicial');
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

    public function askEmail()
    {
        return $this->ask('One more thing - what is your email?', function(Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Great - that is all we need, '.$this->firstname);
        });
    }

    public function run()
    {
        // This will be called immediately
        $this->askFirstname();
    }
}