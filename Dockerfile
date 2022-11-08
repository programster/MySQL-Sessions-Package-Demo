FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

# Fix timezone issue
ENV TZ=Europe/London
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone


RUN apt-get update && apt-get dist-upgrade -y

# Install php 8 PPA
RUN apt-get install -y software-properties-common apt-transport-https \
  && add-apt-repository ppa:ondrej/php -y \
  && apt-get update \
  && apt-get install php8.1-cli -y

# Install the relevant packages
# Need mysql-client for mysqldump command on backup endpoint.
RUN apt-get install -y \
    vim cron apache2 curl unzip git libapache2-mod-php8.1 \
    php8.1-mysql php8.1-curl php8.1-xml php8.1-zip mysql-client

# Install composer 2
RUN apt-get update \
  && apt-get install curl -y \
  && curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/bin/composer \
  && chmod +x /usr/bin/composer

# Enable the apache mods
RUN a2enmod php8.1 && a2enmod rewrite


# expose port 80 for web requests.
EXPOSE 80


# Manually set the apache environment variables in order to get apache to work immediately.
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2

# It appears that the new apache requires these env vars as well
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2/apache2.pid


###### Update the php INI settings #########

# Uncomment any of these if you need them.

# Increase php's max allowed memory size
RUN sed -i 's;memory_limit = .*;memory_limit = -1;' /etc/php/8.1/apache2/php.ini
RUN sed -i 's;memory_limit = .*;memory_limit = -1;' /etc/php/8.1/cli/php.ini

RUN sed -i 's;display_errors = .*;display_errors = On;' /etc/php/8.1/apache2/php.ini
RUN sed -i 's;display_errors = .*;display_errors = On;' /etc/php/8.1/cli/php.ini


# Set the max execution time
#RUN sed -i 's;max_execution_time = .*;max_execution_time = -1;' /etc/php5/apache2/php.ini
#RUN sed -i 's;max_execution_time = .*;max_execution_time = -1;' /etc/php5/cli/php.ini

# This is also needed for execution time
#RUN sed -i 's;max_input_time = .*;max_input_time = -1;' /etc/php5/apache2/php.ini


# Add the site's code to the container.
# We could mount it with volume, but by having it in the container, deployment is easier.
COPY --chown=root:www-data app /var/www/site


# Update our apache sites available with the config we created
ADD docker/apache-config.conf /etc/apache2/sites-enabled/000-default.conf




# Use the crontab file.
# The crontab file was already added when we added "project"
#RUN crontab /var/www/site/project/docker/crons.conf

# Set permissions
#RUN chown root:www-data -R /var/www
RUN cd /var/www/site && composer install && chown root:www-data -R vendor

RUN chown root:www-data /var/www
RUN chmod 750 -R /var/www

# Execute the containers startup script which will start many processes/services
# The startup file was already added when we added "project"
COPY --chown=root:root docker/startup.sh /root/startup.sh
CMD ["/bin/bash", "/root/startup.sh"]
