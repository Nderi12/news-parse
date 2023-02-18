#!/bin/bash

# Queue a (news parsing) message
/usr/local/bin/php /var/www/symfony_docker/bin/console app:parse-urls
