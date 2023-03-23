<?php

namespace App\Message;

use Psr\Log\LoggerInterface;
use App\Module\Login\LoginHandler;
use PhpAmqpLib\Message\AMQPMessage;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;

class LoginConsumer 
    extends CommonForConsumer 
        implements ConsumerInterface 
{
    public function __construct(
        private LoggerInterface $logger,
        private LoginHandler $loginHandler,
        private Producer $answerProducer,
    )
    {}

    public function execute(AMQPMessage $msg): void
    {

        $message    = json_decode($msg -> body, true);
        $property   = $msg -> get_properties();

        $request    = [
            "status"    => '',
            "request"   => $message,
        ];

        $routingKey = 'answer';

        try{
            $this->validate($msg);

            $request['request'] = $this->loginHandler
                ->prepare(
                    $message['login'],
                    $message['password'],
                );

                $request['status'] = 'OK';

        }
        catch (\DomainException $e) {
            $request['status']  = 'Error';
            $request['message'] = $e->getMessage();
        }
        catch (\ErrorException $e) {
            $request['status']  = 'Error';
            $request['message'] = $e->getMessage();
            $routingKey = 'errors';
        }

        $this->answerProducer
            ->publish(
                json_encode($request),
                $routingKey,
                ['correlation_id' => ($property['correlation_id'] ?? 0)]
            );
    }
}