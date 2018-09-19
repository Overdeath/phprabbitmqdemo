<?php
namespace RabbitMqDemo;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Publisher
{
    /**
     * @var AMQPStreamConnection
     */
    private $conn;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * Publisher constructor.
     * @param AMQPStreamConnection $conn
     * @param string $exchange
     */
    public function __construct(AMQPStreamConnection  $conn, string $exchange)
    {
        $this->conn = $conn;
        $this->exchange = $exchange;
        $this->channel = $conn->channel();
        $this->channel->exchange_declare($exchange, 'fanout', false, true, false, false);
    }

    public function publishArray(array $data) {
        $jsondata = json_encode($data);
        $msg = new AMQPMessage($jsondata);

        $this->channel->basic_publish($msg, $this->exchange);
        echo date("H:i:s")." - Published message: ".$jsondata.PHP_EOL;
        return true;
    }

}