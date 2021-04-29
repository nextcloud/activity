#!/bin/bash
git clone https://github.com/nextcloud/activity /var/www/html/apps/activity
su www-data -c "
php occ config:system:set force_language --value en
php /var/www/html/occ app:enable activity
php /var/www/html/occ app:list
"
