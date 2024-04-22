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
   

  // Give the bot something to listen for.
  $botman->hears(strtolower('Hola'), function (BotMan $bot) {
    $bot->startConversation(new InicioConversation());
});

$botman->hears('clima en {location}', function ($bot,$location){
    $bot->reply('Ingresaste: ' . $location);
});

$botman->hears('wit.ai', function ($bot){
    $user_consulta = urlencode("Crear el contacto gustavo");
    $url = "https://api.wit.ai/message?v=20240304&q=" . $user_consulta;
    $token = "42OKHWM7P7YSJV4QYAWKBILFDE5HV5LA";
    $options = array('http' => array(
        'method'  => 'GET',
        'header' => 'Authorization: Bearer '.$token
    ));
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

//print_r($response);
    $bot->reply('Ingresaste: ' . $response);
});

$botman->hears('survey', function ($bot){
    $bot->ask('Cual es tu nombre ', function ($answer, $conversation){
        $value = $answer->getText();
        $conversation->say('Nice to meet you, ' . $value);
    });
});

$botman->fallback(function($bot) {
    $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: ...');
});

// Start listening
$botman->listen();
