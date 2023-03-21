php bin/console
php bin/console doctrine:schema:create
php bin/console lexik:jwt:generate-keypair

php bin/console rabbitmq:consumer register