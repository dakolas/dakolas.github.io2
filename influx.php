<?php 

require_once 'vendor/autoload.php';

$client = new InfluxDB\Client('localhost', '8086');

// fetch the database
$database = $client->selectDB('arduino_test_bueno');

// executing a query will yield a resultset object
$result = $database->query('select temperature,humidity from arduinoData');

// get the points from the resultset yields an array
$points = $result->getPoints();

foreach ($points as $value) {
    echo "\n";
    foreach ($value as $item) {
        echo $item." ";
    }
}

echo "\n";

?>