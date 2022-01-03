FROM ghcr.io/nextcloud/continuous-integration-server:latest

RUN mkdir /var/www/html/data
RUN chown -R www-data:www-data /var/www/html/data

ENTRYPOINT /usr/local/bin/initAndRun.sh
