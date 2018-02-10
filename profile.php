<?php
/**
 * Created by PhpStorm.
 * User: bizou
 * Date: 19/01/18
 * Time: 10:55
 */
use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';

$hosts = ['localhost:9200',
    '127.0.0.1:9200'];
$client = ClientBuilder::create()
    ->setHosts($hosts)
    ->build();    // Build the client object

if (isset($_POST) && !empty($_POST)){
    $themesConf = explode(' ', $_POST['themes']);
    $params = [
        'index'     => 'bizou',
        'type'      => 'conférencier',
        'body'      => [
            'nom'       => $_POST['nom'],
            'prenom'    => $_POST['prenom'],
            'métier'    => $_POST['metier'],
            'thèmes'    => $themesConf
        ]
    ];

    $insertConf = $client->index($params);

    header('location: http://localhost:8000/');
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <link href="elastic.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <nav class="navbar-elastic">
        <div class="nav-wrapper">
            <a href="http://localhost:8000" class="brand-logo center"><img class="logo-elastic" src="http://javatutorialspot.com/wp-content/uploads/2017/02/Elasticsearch-Logo-Color-H-1024x273.png"></a>
            <ul id="nav-mobile" class="left hide-on-med-and-down">
                <li><a href="create-profile">Créer un profil</a></li>
            </ul>
        </div>
    </nav>

    <div class="row" id="profileForm">
        <form class="col s12" action="" method="post">
            <div class="row">
                <div class="input-field col s6">
                    <input name="prenom" id="first_name" type="text" class="validate">
                    <label for="first_name">First Name</label>
                </div>
                <div class="input-field col s6">
                    <input name="nom" id="last_name" type="text" class="validate">
                    <label for="last_name">Last Name</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input name="metier" id="metier" type="text" class="validate">
                    <label for="metier">Métier</label>
                </div>
                <div class="input-field col s6">
                    <input name="themes" id="themes" type="text" class="validate">
                    <label for="themes">Thèmes</label>
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit">Submit
                <i class="material-icons right">send</i>
            </button>
        </form>
    </div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
</body>