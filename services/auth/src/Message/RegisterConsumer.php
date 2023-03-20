<?php

namespace App\Message;

use Psr\Log\LoggerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;

class RegisterConsumer implements ConsumerInterface 
{
    public function __construct(
        private LoggerInterface $logger,

    )
    {}

    public function execute(AMQPMessage $msg): void
    {
        $message = json_decode($msg -> body, true);
        $property= $msg->get_properties();
        var_dump($message);
        var_dump($property);
        $this->logger->info("Register Service" . json_encode($message));
    }
}