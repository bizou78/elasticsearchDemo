<?php
/**
 * Created by PhpStorm.
 * User: bizou
 * Date: 27/12/17
 * Time: 12:27
 */

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';

$hosts = ['localhost:9200',
    '127.0.0.1:9200'];
$client = ClientBuilder::create()
    ->setHosts($hosts)
    ->build();    // Build the client object

if (isset($_POST['search-bar']) && !empty($_POST['search-bar'])){
    $params = [
        'index' => 'bizou',
        'type'  => 'conférencier',
        'body'  => [
            'query' => [
                'bool'  => [
                    'must'  => [
                        'query_string'  => [
                            'query' => $_POST['search-bar']
                        ]
                    ]
                ]
            ]
        ]
    ];

    $allDatas = $client->search($params);
}

if (isset($_GET['metier']) && !empty($_GET['metier'])){
    $params = [
        'index' => 'bizou',
        'type'  => 'conférencier',
        'body'  => [
            'query' => [
                'match' => [
                    'métier' => $_GET['metier']
                ]
            ]
        ]
    ];

    $datasByJob = $client->search($params);
}

if (isset($_POST['id_suppress']) && !empty($_POST['id_suppress'])){
    $params = [
        'index' => 'bizou',
        'type'  => 'conférencier',
        'id'    => $_POST['id_suppress']
    ];
    $supressData = $client->delete($params);
    $client->indices()->refresh();
}
$params = [
    'index' => 'bizou',
    'type'  => 'conférencier'
];

$indexDatas = $client->search($params);
$client->indices()->refresh();
?>
<!doctype html>
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
            <a href="/" class="brand-logo center"><img class="logo-elastic" src="http://javatutorialspot.com/wp-content/uploads/2017/02/Elasticsearch-Logo-Color-H-1024x273.png"></a>
            <ul id="nav-mobile" class="left hide-on-med-and-down">
                <li><a href="profile.php">Créer un profil</a></li>
            </ul>
        </div>
    </nav>
    <div class="row" id="searchBar">
        <form class="col s12" action="" method="post">
            <div class="row">
                <div class="input-field col s6">
                    <i class="material-icons prefix">search</i>
                    <input id="search-bar" type="text" class="validate" name="search-bar">
                    <label for="search-bar">Recherche : </label>
                </div>
            </div>
        </form>
    </div>
    <table class="bordered highlight">
        <thead>
        <tr>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Métier</th>
            <th scope="col">Thèmes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($allDatas) && !empty($allDatas)) {
            $tabHits = $allDatas['hits']['hits'];
            for ($i = 0; $i < count($tabHits); $i++) {
                $themes = '';
                for ($y = 0; $y < count($tabHits[$i]['_source']['thèmes']); $y++) {
                    $themes .= $tabHits[$i]['_source']['thèmes'][$y];
                    $themes .= '<br>';
                }

                echo '
                    <tr>
                        <td>' . $tabHits[$i]['_source']['nom'] . '</td>
                        <td>' . $tabHits[$i]['_source']['prenom'] . '</td>
                        <td><a href="?metier=   ' . $tabHits[$i]['_source']['métier'] . '">' . $tabHits[$i]['_source']['métier'] . '</a></td>
                        <td>' . $themes . '</td>
                        <td>
                            <form>
                                <input name="id_suppress" type="hidden" value="' . $tabHits[$i]['_id'] .'">
                                <button class="btn waves-effect waves-light" type="submit">Suppress
                                <i class="material-icons right">send</i>
                                </button>
                            </form>
                        </td>
                    </tr>';
            }
        }elseif (isset($datasByJob) && !empty($datasByJob)){
            $jobDatas = $datasByJob['hits']['hits'];
            for ($i=0; $i<count($jobDatas); $i++){
                $themes ='';
                for ($y=0; $y<count($jobDatas[$i]['_source']['thèmes']); $y++){
                    $themes .= $jobDatas[$i]['_source']['thèmes'][$y];
                    $themes .= '<br>';
                }

                echo '
                <tr>
			        <td>' . $jobDatas[$i]['_source']['nom'] . '</td>
                    <td>' . $jobDatas[$i]['_source']['prenom'] . '</td>
                    <td><a href="?metier=   ' . $jobDatas[$i]['_source']['métier'] . '">' . $jobDatas[$i]['_source']['métier'] . '</a></td>
                    <td>' . $themes . '</td>
                    <td>
                        <form>
                            <input name="id_suppress" type="hidden" value="' . $jobDatas[$i]['_id'] .'">
                            <button class="btn waves-effect waves-light" type="submit">Suppress
                            <i class="material-icons right">send</i>
                            </button>
                        </form>
                    </td>
		        </tr>';
            }
        }else{
            $indexedDatas = $indexDatas['hits']['hits'];
            for ($i=0; $i<count($indexedDatas); $i++){
                $themes ='';
                for ($y=0; $y<count($indexedDatas[$i]['_source']['thèmes']); $y++){
                    $themes .= $indexedDatas[$i]['_source']['thèmes'][$y];
                    $themes .= '<br>';
                }

                echo '
                <tr>
			        <td>' . $indexedDatas[$i]['_source']['nom'] . '</td>
                    <td>' . $indexedDatas[$i]['_source']['prenom'] . '</td>
                    <td><a href="?metier=   ' . $indexedDatas[$i]['_source']['métier'] . '">' . $indexedDatas[$i]['_source']['métier'] . '</a></td>
                    <td>' . $themes . '</td>
                    <td>
                        <form name="supress" action="" method="post">
                            <input name="id_suppress" type="hidden" value="' . $indexedDatas[$i]['_id'] .'">
                            <button class="btn waves-effect waves-light" type="submit">Suppress
                            <i class="material-icons right">send</i>
                            </button>
                        </form>
                    </td>
		        </tr>';
            }
        }
        ?>
        </tbody>
    </table>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
</body>
</html>
