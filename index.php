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


$params = [
    'index' => 'bizou',
    'type'  => 'tutoriels'
];

$allDatas = $client->search($params);


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <title>Document</title>
</head>
<body>
    <table class="bordered highlight">
        <thead>
        <tr>
            <th scope="col">Titre</th>
            <th scope="col">Categorie</th>
            <th scope="col">Durée</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $tabHits = $allDatas['hits']['hits'];
        for ($i=0; $i<count($tabHits); $i++){
            echo '
                <tr>
			        <td>' . $tabHits[$i]['_source']['title'] . '</td>
                    <td>' . $tabHits[$i]['_source']['category'] . '</td>
                    <td>' . $tabHits[$i]['_source']['duration'] . '</td>
		        </tr>';
        }
        ?>
        </tbody>
    </table>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
</body>
</html>
