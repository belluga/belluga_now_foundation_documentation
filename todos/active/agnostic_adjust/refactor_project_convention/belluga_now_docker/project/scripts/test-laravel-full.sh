#!/bin/bash
set -euo pipefail

COMPOSE_PROFILES=local-db docker compose up -d --build mongo mongo-init app
docker compose exec -T app sh -lc "mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/testing storage/framework/views storage/logs && chmod -R ug+rwX bootstrap/cache storage"
docker compose exec -T app env \
    APP_ENV=testing \
    APP_KEY='base64:GmmALtgdmR+nNYciHr0ynX/QoqHXmoXXtbwHVNWg8Pk=' \
    APP_FAKER_LOCALE=pt_BR \
    DB_CONNECTION_LANDLORD=landlord \
    DB_CONNECTION_TENANTS=tenant \
    DB_URI='mongodb://mongo:27017/landlord_test?replicaSet=rs0' \
    DB_URI_LANDLORD='mongodb://mongo:27017/landlord_test?replicaSet=rs0' \
    DB_URI_TENANTS='mongodb://mongo:27017/tenants_test?replicaSet=rs0' \
    DB_DATABASE=landlord_test \
    DB_DATABASE_LANDLORD=landlord_test \
    DB_DATABASE_TENANTS=tenants_test \
    php artisan test
