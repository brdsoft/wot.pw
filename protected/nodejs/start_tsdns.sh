#!/bin/bash

cd /var/www/site.wot.pw/web/protected/nodejs/
kill -TERM $(cat tsdns.pid)
nohup nodejs tsdns.js > tsdns.log 2>&1 &
echo $! > tsdns.pid &
echo 'TS DNS Server started'
