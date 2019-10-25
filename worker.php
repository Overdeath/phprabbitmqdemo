<?php
require __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

require 'config.php';

$connection = new AMQPStreamConnection($hostName, $port, $user, $pass, $vhost);

$consumer = new \RabbitMqDemo\Consumer($connection, $exchangeName, $queueName);

$consumer->consume();