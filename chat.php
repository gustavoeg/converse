<?php
   namespace Conversaciones;
   require_once 'vendor/autoload.php';
   
   use BotMan\BotMan\BotMan;
   use BotMan\BotMan\BotManFactory;
   use BotMan\BotMan\Drivers\DriverManager;

   use Wit\Wit;
   
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
   

   //$botman = BotManFactory::create($config);

  // Give the bot something to listen for.
  $botman->hears(strtolower('Hola'), function (BotMan $bot) {
    $bot->startConversation(new InicioConversation());
});

$botman->hears('clima en {location}', function ($bot,$location){
    $bot->reply('Ingresaste: ' . $location);
});

$botman->hears('wit.ai', function ($bot,$location){
    $bot->reply('Ingresaste: ' . $location);
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
