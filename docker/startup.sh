# Please do not manually call this file!
# This script is run by the docker container when it is "run"


# Run the apache process in the background
/usr/sbin/service apache2 stop

# Sleep for a bit to give database time to spin up.
sleep 5


# Create the .env file for site to load env vars from.
/usr/bin/php /var/www/site/scripts/create-env-file.php "/.env"
chown root:www-data /.env
chmod 750 /.env


# Run migrations
echo "Running migrations..."
/usr/bin/php /var/www/site/scripts/migrate.php
echo "Migrations finished."


# Run the apache process in the background
#/usr/sbin/apache2 -D APACHE_PROCESS &
/usr/sbin/service apache2 start


# Start the cron service in the foreground
# We dont run apache in the FG, so that we can restart apache without container
# exiting.
cron -f
