<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'rabbitmq'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => 'localhost',
            'queue' => 'default',
            'retry_after' => 90,
            'block_for' => 0,
            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => false,
        ],

        'rabbitmq' => [
            'driver' => 'rabbitmq',
            'dsn' => env('MESSENGER_TRANSPORT_RABBIT', null),
            'queue' => env('QUEUE_AUTH', 'gateway_auth'),
//            'queue' => 'test, gateway_auth',

            'factory_class' => Enqueue\AmqpLib\AmqpConnectionFactory::class,
            'hosts' => [
                [
                    'host' => env('RABBITMQ_HOST', 'common-rabbitmq'),
                    'port' => env('RABBITMQ_PORT', 5672),
                    'user' => env('RABBITMQ_USER', 'guest'),
                    'password' => env('RABBITMQ_PASSWORD', 'guest'),
                    'vhost' => env('RABBITMQ_VHOST', '/'),
                ],
            ],
            'options' => [
                'exchange' => [
                    'name' => env('EXCHANGE_AUTH'),
                    'declare' => env('RABBITMQ_EXCHANGE_DECLARE', true),
                    'type' => env('EXCHANGE_AUTH_TYPE', \Interop\Amqp\AmqpTopic::TYPE_DIRECT),
// https://www.rabbitmq.com/tutorials/amqp-concepts.html
//                    'passive' => env('RABBITMQ_EXCHANGE_PASSIVE', false),
//                    'durable' => env('RABBITMQ_EXCHANGE_DURABLE', true),
//                    'auto_delete' => env('RABBITMQ_EXCHANGE_AUTODELETE', false),
//                    'arguments' => env('RABBITMQ_EXCHANGE_ARGUMENTS'),
                ],

                'queue' => [
                    'declare' => env('RABBITMQ_QUEUE_DECLARE', true),
                    'bind' => env('RABBITMQ_QUEUE_DECLARE_BIND', true),
//                    'name' => env('QUEUE_AUTH', 'gateway_auth'),

                    'job' => VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob::class,

// https://www.rabbitmq.com/tutorials/amqp-concepts.html
//                    'passive' => env('RABBITMQ_QUEUE_PASSIVE', false),
//                    'durable' => env('RABBITMQ_QUEUE_DURABLE', true),
//                    'exclusive' => env('RABBITMQ_QUEUE_EXCLUSIVE', false),
//                    'auto_delete' => env('RABBITMQ_QUEUE_AUTODELETE', false),
//                    'arguments' => env('RABBITMQ_QUEUE_ARGUMENTS'),
                ],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    // TODO: подумать что будет когда будет fail
    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];
