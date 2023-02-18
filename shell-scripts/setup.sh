#!/bin/bash


APP_DIR=/var/www/symfony_docker
VENDOR_DIR="${APP_DIR}/vendor"

## Backup previous .env file and generate a new .env file.
## This way, on each run, we get the up-to-date environment settings specified inside the /.env file of this repo.
## 
## Another reason for this is because: 
## Cron uses the environment variables defined inside the symfony app 
## So overwrite the values there with our set values  
## to prevent errors caused by invalid or missing env variable values.
## cron runs with a mostly empty environment,
## so it's almost as if Symfony exports its own .env file to cron: 
## See this SO answer to get an idea why: https://stackoverflow.com/a/53641735/1743192

BACKUP_ENV_FILE="${APP_DIR}/.env.bk"

if [ ! -f ${SYMPHONY_ENV_FILE} ]; then 
    mv ${APP_DIR}/.env ${BACKUP_ENV_FILE}
fi

echo "APP_ENV=${APP_ENV} 
APP_SECRET=${APP_SECRET}
DATABASE_URL=\"${DATABASE_URL}\" 
MESSENGER_TRANSPORT_DSN=\"${MESSENGER_TRANSPORT_DSN}\"
" > ${APP_DIR}/.env


## Install dependencies 
## But only if this is the first run
if [ ! -d ${VENDOR_DIR} ]; then
	echo "Running 'composer install'"
    composer install
fi


## Install and verify browser client drivers used by symfony/panther
#echo "Running 'bdi driver:chromedriver' to install Chrome driver"
#vendor/bin/bdi detect drivers
#vendor/bin/bdi driver:chromedriver
#cp ./chromedriver /usr/local/bin/chromedriver


## Setup the database 
### We don't need to recreate the database, 
### since docker-compose does that already. 
### /usr/local/bin/php bin/console doctrine:database:create
/usr/local/bin/php bin/console doctrine:migrations:migrate
