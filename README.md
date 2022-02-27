## Set up project
- build and start docker: docker compose up -d
## You may need to run some of these commands
- update composer: docker exec postcast_crawler_web composer update
- give permission to storage folder: docker exec postcast_crawler_web chmod -R 777 storage
- migrate database: docker exec postcast_crawler_web php artisan migrate
