<?php

namespace App\Message;

use Psr\Log\LoggerInterface;
use App\Module\Register\RegisterHandler;
use PhpAmqpLib\Message\AMQPMessage;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;

class RegisterConsumer
    extends CommonForConsumer 
        implements ConsumerInterface 
{
    public function __construct(
        private LoggerInterface $logger,
        private RegisterHandler $registerHandler,
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

            $request['request'] = $this->registerHandler
                ->prepare($message);

                $request['status'] = 'OK';

        }
        catch (\DomainException|\Exception $e) {
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