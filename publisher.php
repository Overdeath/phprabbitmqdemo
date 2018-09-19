<?php
require __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('192.168.56.12', 5672, 'test', 'test');

$publisher = new \RabbitMqDemo\Publisher($connection, "products");

for ($i=1; $i<=1000; $i++) {
    $data = ["name"=>"Produs ".$i, "price"=>rand(100, 1000)];
    $publisher->publishArray($data);
}

$connection->close();
