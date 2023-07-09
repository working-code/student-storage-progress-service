sudo cp nginx.conf /etc/nginx/conf.d/student-storage-progress-service.conf -f
sudo cp supervisor.conf /etc/supervisor/conf.d/student-storage-progress-service.conf -f
sudo sed -i -- "s|%SERVER_NAME%|$1|g" /etc/nginx/conf.d/student-storage-progress-service.conf

sudo service nginx restart
sudo service php8.1-fpm restart

sudo -u www-data php bin/console cache:clear

sudo service supervisor restart
