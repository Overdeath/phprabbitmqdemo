<?php
require __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

require 'config.php';

$connection = new AMQPStreamConnection($hostName, $port, $user, $pass, $vhost);

$publisher = new \RabbitMqDemo\Publisher($connection, $exchangeName);

for ($i=1; $i<=1000; $i++) {
    $data = ["name"=>"Produs ".$i, "price"=>rand(100, 1000)];
    $publisher->publishArray($data);
}

$connection->close();
