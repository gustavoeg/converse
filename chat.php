<?php
   namespace Conversaciones;
   require_once 'vendor/autoload.php';
   
   use BotMan\BotMan\BotMan;
   use BotMan\BotMan\BotManFactory;
   use BotMan\BotMan\Drivers\DriverManager;
      
   require_once 'conversaciones/InicioConversation.php';

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
   

  // Give the bot something to listen for.
  $botman->hears(strtolower('Hola'), function (BotMan $bot) {
    $bot->startConversation(new InicioConversation());
});


/* $botman->hears('(.*)', function (BotMan $bot){
    if(strtolower($bot->getMessage()->getText() ) == "hola"){
        //iniciar conversación
        $bot->startConversation(new InicioConversation());
    }else{
        $bot->reply($bot->getMessage()->getText() );
    }
}); */


$botman->fallback(function($bot) {
    //para no estar diciendole al usuario que escribió mal y que ingrese 'hola' correctamente, le muestro el menú principal
    $bot->startConversation(new InicioConversation());
});

// Start listening
$botman->listen();