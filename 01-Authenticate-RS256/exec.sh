#!/usr/bin/env bash
docker build -t auth0-php-api-01-authenticate .
docker run --env-file .env -p 3001:3001 -it auth0-php-api-01-authenticate
