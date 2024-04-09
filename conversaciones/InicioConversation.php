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
            Button::create('Opcion ayuda 1')->value('1'),
            Button::create('Opcion ayuda 2')->value('2'),
            Button::create('Opcion ayuda 3')->value('3'),
            Button::create('Opcion ayuda 4')->value('4'),
            Button::create('Opcion ayuda 5')->value('5')
        ]);

    $this->ask($question, function (Answer $answer, $conv) {
    // Detect if button was clicked:
    if ($answer->isInteractiveMessageReply()) {
        $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
        $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'
        $conv->say('Seleccionada: getText()' . $selectedText . ' getValue(): ' . $selectedValue);
        //$this->say('Seleccionada Opcion');
        switch ($selectedValue) {
            case '1':
                # code...
                $conv->say('Seleccionada Opcion 1');
                break;

            case '2':
                # code...
                $conv->say('Seleccionada Opcion 2');
                break;

            case '3':
                # code...
                $conv->say('Seleccionada Opcion 3');
                break;
            case '4':
                # code...
                $conv->say('Seleccionada Opcion 4');
                break;
            case '5':
                # code...
                $conv->say('Seleccionada Opcion 5');
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