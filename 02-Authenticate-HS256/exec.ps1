docker build -t auth0-php-api-02-authenticate .
docker run --env-file .env -p 3010:3010 -it auth0-php-api-02-authenticate
