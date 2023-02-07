# RabbitMQ

### Очередь для общения микросервисов: gateway - auth
##### Gateway
    php bin/console messenger:consume auth
##### Auth
    php artisan queue:work
---