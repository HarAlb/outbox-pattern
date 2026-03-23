#!/bin/bash
mkdir -p /var/www/outbox_app/bootstrap/cache /var/www/outbox_app/storage /var/www/tmp
chown -R www-data:www-data /var/www/outbox_app/bootstrap/cache /var/www/outbox_app/storage
chmod -R 775 /var/www/outbox_app/bootstrap/cache /var/www/outbox_app/storage
chmod 666 /tmp/xdeug.log

exec "$@"
