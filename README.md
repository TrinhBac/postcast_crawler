## Set up project
- build and start docker: docker compose up -d
## You may need to run some of these commands
- update composer: docker exec postcast_crawler_web composer update
- give permission to storage folder: docker exec postcast_crawler_web chmod -R 777 storage
- migrate database: docker exec postcast_crawler_web php artisan migrate
## Link API
- crawl genre: POST: http://0.0.0.0:85/api/genres
- list genre: GET: http://0.0.0.0:85/api/genres
- detail genre: GET: http://0.0.0.0:85/api/genres/{id}
- crawl postcast: POST: http://0.0.0.0:85/api/postcasts
- list postcast: GET: http://0.0.0.0:85/api/postcasts
- detail postcast: GET: http://0.0.0.0:85/api/postcasts/{id}
- 
