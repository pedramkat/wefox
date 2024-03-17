#!/bin/bash

cp .env.example .env
cp docker-compose.yml.example docker-compose.yml
docker compose up -d

echo "Do you want to install and activate xdebug? y/n"
read xdebug

source .env
if [[ $xdebug = y ]]
then
    bash docker/configs/phpfpm/init-xdebug.sh
    docker exec -u 0 -it php81_$APP_NAME bash
    touch /var/log/xdebug.log
    chown -R 33 /var/log/
fi


echo "Deploying local environment ..."
docker exec -it php81_$APP_NAME bash scripts/deploy_local.sh
echo "Deploying $APP_NAME done"