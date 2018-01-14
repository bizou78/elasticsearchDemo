<?php
/**
 * Created by PhpStorm.
 * User: bizou
 * Date: 27/12/17
 * Time: 12:27
 */

require 'vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->build();    // Build the client object
/*
$params = array();
$params['body']  = array(
    'name' => 'Ash Ketchum',
    'age' => 10,
    'badges' => 8
);

$params['index'] = 'pokemon';
$params['type']  = 'pokemon_trainer';

$result = $client->index($params);

var_dump($result);*/
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>COUCOU</h1>
    <?php if ($client){
        echo 'connected';
    }; ?>
</body>
</html>
