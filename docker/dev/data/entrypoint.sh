#!/usr/bin/env bash

# Starting The Queues
nohup php artisan queue:work --tries=3 --timeout=120> /dev/null &
nohup php artisan queue:work --queue=expiration --tries=3 --timeout=60 > /dev/null &

nohup crond -f &

chmod -R 777 /www
