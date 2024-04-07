<?php
   require_once 'vendor/autoload.php';
   //include 'conversaciones/InicioConversation.php';
   use BotMan\BotMan\BotMan;
   use BotMan\BotMan\BotManFactory;
   use BotMan\BotMan\Drivers\DriverManager;
   use BotMan\BotMan\Messages\Incoming\Answer;
   use BotMan\BotMan\Messages\Outgoing\Actions\Button;
   use BotMan\BotMan\Messages\Outgoing\Question;
   use BotMan\BotMan\Messages\Conversations\Conversation;
   //use conversaciones;

   class InicioConversation extends Conversation
{
    protected $firstname;

    protected $email;

    public function askFirstname()
    {
        $this->ask('Hello! What is your firstname?', function(Answer $answer) {
            // Save result
            $this->firstname = $answer->getText();

            $this->say('Nice to meet you '.$this->firstname);
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('One more thing - what is your email?', function(Answer $answer) {
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
   
   $config = [
    // Your driver-specific configuration
    // "telegram" => [
    //    "token" => "TOKEN"
    // ]
];

   DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

   $botman = BotManFactory::create($config);



  // Give the bot something to listen for.
  $botman->hears(strtolower('Hola'), function (BotMan $bot) {
    
    $bot->startConversation(new InicioConversation);
    //esquema de pregunta con opciones
    /* $question = Question::create('Sobre que tema desea consultar?')
    ->fallback('Unable to continue...')
    ->callbackId('create_database')
    ->addButtons([
        Button::create('Opcion ayuda 1')->value('1'),
        Button::create('Opcion ayuda 2')->value('2'),
        Button::create('Opcion ayuda 3')->value('3'),
        Button::create('Opcion ayuda 4')->value('4'),
        Button::create('Opcion ayuda 5')->value('5')
    ]); */

    /* $bot->ask($question, function (Answer $answer) {
    // Detect if button was clicked:
    if ($answer->isInteractiveMessageReply()) {
        $selectedValue = $answer->getValue(); // will be either 'yes' or 'no'
        $selectedText = $answer->getText(); // will be either 'Of course' or 'Hell no!'
        //$bot->say('Seleccionada');
        $this->say('Seleccionada Opcion');
        switch ($selectedValue) {
            case '1':
                # code...
                $this->reply('Seleccionada Opcion 1');
                break;

            case '2':
                # code...
                $this->reply('Seleccionada Opcion 2');
                break;

            case '3':
                # code...
                $this->reply('Seleccionada Opcion 3');
                break;
            case '4':
                # code...
                $this->reply('Seleccionada Opcion 4');
                break;
            case '5':
                # code...
                $this->reply('Seleccionada Opcion 5');
                break;
            
            default:
                # code...
                $this->reply('No selecciono opcion valida');
                break;
        }
    }
    }); */

});

/* $botman->hears('what is the time in {city} located in {continent}' , function (BotMan $bot,$city,$continent) {
    date_default_timezone_set("$continent/$city");
     $reply = "The time in ".$city." ".$continent." is ".date("h:i:sa");
   $bot->reply($reply);
}); */

$botman->fallback(function($bot) {
    $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: ...');
});


// Start listening
$botman->listen();