<?php
ini_set('display_errors','1');
require_once __DIR__.'/vendor/autoload.php';
use Wit\Wit;
$app = new Wit(array(
    'default_access_token' => 'JBVDSLC5NERFEF5UZX3CAYAGQURZHTNI')
);

$response = $app->get('/intents');
var_dump($response->getDecodedBody());

$data = [
    "name" => "flight_request",
    "doc"  => "detect flight request",
    "expressions" => [
        ["body" => "fly from incheon to sfo"],
        ["body" => "I want to fly from london to sfo"],
        ["body" => "need a flight from paris to tokyo"],
    ]
];

$response = $app->post('/intents', $data);
var_dump($response->getDecodedBody());

/* curl ^
  -H "Authorization: Bearer JBVDSLC5NERFEF5UZX3CAYAGQURZHTNI" ^
  "https://api.wit.ai/message?v=20240418&q=sube%20el%20volumen" */