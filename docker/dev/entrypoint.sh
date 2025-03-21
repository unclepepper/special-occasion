#!/bin/sh

#git config --global --add safe.directory /app
composer install

chown -R www-data:www-data /var/www/app



unitd --no-daemon --control unix:/var/run/control.unit.sock &
sleep 1
curl --unix-socket /var/run/control.unit.sock -X PUT -d @/docker-entrypoint.d/config.json http://localhost/config

wait