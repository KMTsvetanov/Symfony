#!/bin/sh

#docker-compose -f docker-compose.yml -f docker-compose.dev.yml --env-file .env.local up -d

XDEBUG_MODE=${XDEBUG_MODE:-off} docker compose -f docker-compose.yml -f docker-compose.dev.yml \
  --env-file ./app/.env.local up "$@"