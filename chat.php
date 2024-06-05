<?php
   namespace Conversaciones;
   require_once 'vendor/autoload.php';
   
   use BotMan\BotMan\BotMan;
   use BotMan\BotMan\BotManFactory;
   use BotMan\BotMan\Drivers\DriverManager;
   use BotMan\BotMan\Messages\Incoming\Answer;
   use BotMan\BotMan\Messages\Outgoing\Actions\Button;
   use BotMan\BotMan\Messages\Outgoing\Question;
      
   require_once 'conversaciones/InicioConversation.php';
   require_once 'consulta_ia.php'; //para consultas abiertas

   $config = [
    // Your driver-specific configuration
    // "telegram" => [
    //    "token" => "TOKEN"
    // ]
];


   DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

   use BotMan\BotMan\Cache\SymfonyCache;
   use Symfony\Component\Cache\Adapter\FilesystemAdapter;
   
   $adapter = new FilesystemAdapter();
   $botman = BotManFactory::create($config, new SymfonyCache($adapter));
   $botman->typesAndWaits(1);

   function returnOrExit($conversation){
        
        $question = Question::create("¿Deseas realizar otras consultas?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Si')->value('si'),
                Button::create('No')->value('no'),
            ]);
            
        $conversation->ask($question, function (Answer $answer) use($conversation){

            if (strtolower($answer->getValue()) === 'si') {
                $conversation->startConversation(new InicioConversation());
                
            } elseif (strtolower($answer->getValue()) === 'no'){
                $this->say('Fue un placer conversar. Si requiere mi ayuda de nuevo, solo vuelva a escribir "hola"...');
            }else{
                //respuesta desconocida
                $this->say('Respuesta desconocida...');
                $this->say('Fue un placer conversar. Si requiere mi ayuda de nuevo, solo vuelva a escribir "hola"...');
            }
        
        });
    }
   

/*   // Give the bot something to listen for.
  $botman->hears(strtolower('Hola'), function (BotMan $bot) {
    $bot->startConversation(new InicioConversation());
});
 */

$botman->hears('(.*)', function (BotMan $bot){
    if(strtolower($bot->getMessage()->getText() ) == "hola"){
        //iniciar conversación
        $bot->startConversation(new InicioConversation());
    }else{
        //$bot->reply($bot->getMessage()->getText() );
        //respuesta por texto ingresado tipeando (sin hacer clic en las opciones o pasó el tiempo)
        $selectedText = $bot->getMessage()->getText();

        //se puede hacer PLN para el texto ingresado
        $respuesta_api = preguntar_API_IA($selectedText);

        //procesamiento de la respuesta del api y se obtiene la respuesta en texto para ser mostrado en la conversacion
        $dependencia_texto = textoRespuestaDependenciaAPI($respuesta_api);

        $bot->reply($dependencia_texto);
        
        returnOrExit($bot);  //¿otra consulta?
        $bot->reply("Gracias por ");
    }
});


$botman->fallback(function($bot) {
    //para no estar diciendole al usuario que escribió mal y que ingrese 'hola' correctamente, le muestro el menú principal
    $bot->startConversation(new InicioConversation());
});

// Start listening
$botman->listen();