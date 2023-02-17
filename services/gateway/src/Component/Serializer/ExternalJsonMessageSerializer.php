<?php

declare(strict_types=1);

namespace Gateway\Component\Serializer;

use Exception;
use Gateway\Component\Serializer\Exception\ExternalMessageDecodingException;
use Gateway\Component\Serializer\Utility\FileFinder;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class ExternalJsonMessageSerializer implements SerializerInterface
{
    private const CLASS_ID = '__PHP_Incomplete_Class_Name';

    /**
     * Сериализация пришедшего из вне message
     *
     * @param array $encodedEnvelope
     * @return Envelope
     * @throws ExternalMessageDecodingException
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $this->assertIsEmptyBody($body = $encodedEnvelope['body']);

        $data = $this->unserializeBody($body);

        $this->assertIsValidUnserializedData($data);

        $data = $this->prepareUnserializedData($data);

        $messageFileName = array_shift($data);
        try {
            $message = $this->createMessage($messageFileName, $data);
        } catch (Exception $e) {
            throw new ExternalMessageDecodingException(
                'Error during creation of an internal message after decoding an external message.'
            );
        }

        $stamps = $this->prepareStamps($encodedEnvelope['headers']);

        return $this->createEnvelope($message, $stamps);
    }

    // TODO: сделать кодировку (сериализацию)
    public function encode(Envelope $envelope): array
    {
        return [];
    }

    private function createEnvelope(mixed $data, array $stamps): Envelope
    {
        $envelope = (new Envelope($data));
        return $envelope->with(... $stamps);
    }

    private function createMessage(string $messageFileName, array $data): mixed
    {
        return new $messageFileName(...$data);
    }

    private function assertIsEmptyBody(mixed $body): void
    {
        if (empty($body)) {
            throw new ExternalMessageDecodingException('Encoded envelope should have at least a "body".');
        }
    }

    // TODO: в интерфейс и разные реализации
    private function assertIsValidUnserializedData(mixed $data): void
    {
        if (is_null($data)) {
            throw new ExternalMessageDecodingException('Invalid JSON.');
        }
    }

    private function unserializeBody(string $body): mixed
    {
        $body = json_decode($body);
        $data = $body->data->command;

        return unserialize($data);
    }

    private function prepareUnserializedData(object $data): array
    {
        foreach ($data as $key => $value) {
            if ($key == self::CLASS_ID) {
                $result['class_name'] = $this->prepareMessageFileName($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result ?? [];
    }

    private function prepareStamps(array $headers): array
    {
        return isset($headers['stamps']) ? unserialize($headers['stamps']) : [];
    }

    // TODO: вынести в заменяемый класс (интерфейс) ---- пока это в случае принятия ларавел сообщений
    private function prepareMessageClassName(string $old, string $replaceFrom = 'Job', string $replaceTo = 'Message'): string
    {
        try {
            // TODO: different strategy
            $fileName = explode("\\", $old);
            $className = array_pop($fileName);

            // TODO: вот этот механизм может разниться для разных внешних сообщений
            return str_replace($replaceFrom, $replaceTo, $className);
        } catch (Exception $e) {
            throw new ExternalMessageDecodingException('External message class name is empty or incorrect.');
        }
    }

    private function prepareMessageFileName(string $value): string
    {
        $className = $this->prepareMessageClassName($value);
        $extension = '.php';

        // TODO: config services -> from env
        $namespaceBegin = 'Gateway';
        $serverNamespaceBegin = './src';

        $filePaths = FileFinder::findInFolder($className . $extension);

        // TODO: вынести в отдельные функции
        $filePath = $filePaths[0];
        $filePath = str_replace($serverNamespaceBegin, $namespaceBegin, $filePath);
        $filePath = rtrim($filePath, $extension);
        return str_replace('/', '\\', $filePath);
    }
}
