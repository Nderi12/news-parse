#!/bin/bash

chmod 777 /usr/shell-scripts

/usr/shell-scripts/setup.sh 
/usr/shell-scripts/create-cron-job.sh &
php-fpm
