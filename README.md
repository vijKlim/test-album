RUN TEST PROJECT:

docker-compose up -d

docker-compose run --rm php composer install

docker-compose run --rm php yii migrate

docker-compose run --rm php yii seed


RUN TEST:

docker-compose run --rm php php vendor/bin/codecept run api

