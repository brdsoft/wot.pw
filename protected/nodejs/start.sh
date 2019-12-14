#!/bin/bash

cd /var/www/site.wot.pw/web/protected/nodejs/

kill -INT $(cat server.pid)

ulimit -n 65536

nohup nodejs server.js 55710 > node.log 2>&1 &

echo $! > server.pid &
echo 'Server started'

