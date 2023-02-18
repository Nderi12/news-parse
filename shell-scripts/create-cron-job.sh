#!/bin/bash

## Wait for the (Symfony) app to be ready 
until $(curl --output /dev/null --silent --head --fail http://nginx:80/login); do
    printf '.'
    sleep 5
done

echo "${CRON_SCHEDULE} /bin/bash /usr/shell-scripts/run-news-parser.sh >> /var/log/cron.log 2>&1" >> /etc/cron.d/parser_cron

crontab /etc/cron.d/parser_cron

## Run cron and supervisord (used to automatically start our news parser queue consumer)
cron &
supervisord -c /etc/supervisor/conf.d/supervisord.conf &
