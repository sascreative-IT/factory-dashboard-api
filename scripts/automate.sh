#!/bin/sh
cd /var/www/html/factory-dashboard-api
sudo php artisan pull-orders:merch
