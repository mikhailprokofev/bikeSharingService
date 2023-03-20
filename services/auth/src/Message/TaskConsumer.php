<?php

namespace App\Message;

use Psr\Log\LoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;

class TaskConsumer implements ConsumerInterface 
{
    public function __construct(
        private LoggerInterface $logger,

    )
    {}

    public function execute(AMQPMessage $msg): void
    {
        $message = json_decode($msg -> body, true);
        $property= $msg->get_properties();
        // var_dump($msg);
        var_dump($message);
        var_dump($property);
        $this->logger->info(json_encode($message));
    }
}