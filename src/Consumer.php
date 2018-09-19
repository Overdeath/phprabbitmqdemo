<?php
namespace RabbitMqDemo;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer
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
     * @var string
     */
    private $queue;

    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * @var string
     */
    private $consumerTag;

    public function __construct(AMQPStreamConnection $conn, string $exchange, string $queue)
    {
        $this->conn = $conn;
        $this->channel = $conn->channel();
        $this->exchange = $exchange;
        $this->queue = $queue;

        $this->channel->queue_declare($this->queue, false, true, false, false);
        $this->channel->exchange_declare($exchange, 'fanout', false, true, false);
        $this->channel->queue_bind($queue, $exchange);

        $this->consumerTag = 'consumer' . getmypid();
    }

    public function consume(){
        /*
         * don't dispatch a new message to a worker until it has processed and
         * acknowledged the previous one. Instead, it will dispatch it to the
         * next worker that is not still busy.
         */
        $this->channel->basic_qos(
            null,   #prefetch size - prefetch window size in octets, null meaning "no specific limit"
            1,      #prefetch count - prefetch window in terms of whole messages
            null    #global - global=null to mean that the QoS settings should apply per-consumer, global=true to mean that the QoS settings should apply per-channel
        );

        /*
            queue: Queue from where to get the messages
            consumer_tag: Consumer identifier
            no_local: Don't receive messages published by this consumer.
            no_ack: Tells the server if the consumer will acknowledge the messages.
            exclusive: Request exclusive consumer access, meaning only this consumer can access the queue
            nowait: don't wait for a server response. In case of error the server will raise a channel
                    exception
            callback: A PHP Callback
        */

        $this->channel->basic_consume(
            $this->queue,
            $this->consumerTag,
            false,
            false,
            false,
            false,
            [$this, 'processMessage']
        );

        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }

    }

    public function processMessage(AMQPMessage $message){
        echo "\n--------\n";
        echo $message->body;
        echo "\n--------\n";
        usleep(300 * 1000);
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

    }

}