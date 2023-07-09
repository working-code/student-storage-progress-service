sudo -u www-data vendor/bin/codecept run unit

sudo -u www-data sed -i -- "s|%DATABASE_HOST%|$1|g" .env.test
sudo -u www-data sed -i -- "s|%DATABASE_USER%|$2|g" .env.test
sudo -u www-data sed -i -- "s|%DATABASE_PASSWORD%|$3|g" .env.test
sudo -u www-data sed -i -- "s|%DATABASE_NAME%|$4|g" .env.test

sudo -u www-data php bin/console doctrine:migrations:migrate --no-interaction --env=test

sudo -u www-data vendor/bin/codecept run functional
