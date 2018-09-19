<?php
require __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('192.168.56.12', 5672, 'test', 'test');

$consumer = new \RabbitMqDemo\Consumer($connection, 'products', 'frontend');
$consumer->consume();