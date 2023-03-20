<?php

namespace App\Message;

use Psr\Log\LoggerInterface;
use App\Module\Login\LoginHandler;
use PhpAmqpLib\Message\AMQPMessage;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;

class LoginConsumer implements ConsumerInterface 
{
    public function __construct(
        private LoggerInterface $logger,
        private LoginHandler $loginHandler,
        private Producer $answerProducer,
    )
    {}

    public function execute(AMQPMessage $msg): void
    {
        $message = json_decode($msg -> body, true);
        $request = $this->loginHandler
            ->prepare(
                $message['login'],
                $message['password']
            );
        echo json_encode($request,JSON_PRETTY_PRINT);

        $this->answerProducer
            ->publish(json_encode($request),'answer');
    }
}