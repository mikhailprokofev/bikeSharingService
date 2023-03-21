<?php

namespace App\Message;

use Psr\Log\LoggerInterface;
use App\Module\Login\LoginHandler;
use PhpAmqpLib\Message\AMQPMessage;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;

abstract class CommonForConsumer
{
    public function validate(AMQPMessage $msg): void
    {
        $propertys = $msg->get_properties();

        if (empty($propertys['correlation_id'])) {
            throw new \ErrorException('Missing correlation_ID');
        }
    }
}