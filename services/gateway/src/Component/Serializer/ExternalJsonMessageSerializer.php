<?php

declare(strict_types=1);

namespace Gateway\Component\Serializer;

use Gateway\Infrastructure\Messager\Message\Auth\AcceptRefreshTokenMessage;
use Gateway\Message\Auth\RefreshTokenMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ExternalJsonMessageSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];

        if (empty($body)) {
            throw new MessageDecodingFailedException(
                'Encoded envelope should have at least a "body", or maybe you should implement your own serializer.'
            );
        }

        $headers = $encodedEnvelope['headers'];

        $body = json_decode($body);
        $data = $body->data->command;

        $data = unserialize($data);

        if (null === $data) {
            throw new MessageDecodingFailedException('Invalid JSON');
        }

        // in case of redelivery, unserialize any stamps
        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }

        $message = 'RefreshToken' . 'Message';
var_dump($data);
//        $directory = $_SERVER['MESSAGE_DIRECTORY'];
        $directory = 'Gateway\Infrastructure\Messager\Message\Auth\AcceptRefreshTokenMessage';
//        $test = "$directory$message";
//        var_dump($test);

        // из $data получить название класса Message // взять из commandName последнюю после э//э подстроку и вырезать слово Job
//        $data = str_replace("Job", "Message", serialize($data));
//        var_dump($data);
//        $envelope = new Envelope(new ($directory)(serialize($data)));
        $envelope = new Envelope(new AcceptRefreshTokenMessage('s','f','g'));

        $envelope = $envelope->with(... $stamps);
        var_dump(($envelope));
        return $envelope;
    }

    public function encode(Envelope $envelope): array
    {
        return [];
    }
}