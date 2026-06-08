SHELL := /bin/bash

.PHONY: up-dev up-dev-tunnel up-stage up-main down ps logs test-laravel-full

up-dev:
	COMPOSE_PROFILES=local-db docker compose up -d --build

up-dev-tunnel:
	COMPOSE_PROFILES=local-db,local-tunnel docker compose --env-file .env --env-file .env.local.tunnel up -d --build

up-stage:
	COMPOSE_PROFILES= docker compose up -d --build

up-main:
	COMPOSE_PROFILES=production docker compose up -d --build

down:
	docker compose down

ps:
	docker compose ps

logs:
	docker compose logs -f --tail=200

test-laravel-full:
	@if [ -f project/scripts/test-laravel-full.sh ]; then \
		bash project/scripts/test-laravel-full.sh; \
	else \
		echo "No project-specific test script found. Skipping."; \
	fi
