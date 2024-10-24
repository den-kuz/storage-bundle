DOCKER_COMPOSE?=docker compose

help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n"} /^[$$()% a-zA-Z_-]+:.*?##/ { printf "  \033[32m%-30s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

build: ## Собрать контейнеры
	COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 $(DOCKER_COMPOSE) build

pull: ## Обновить образа
	$(DOCKER_COMPOSE) pull

up: ## Поднять контейнеры
	$(DOCKER_COMPOSE) up -d --remove-orphans

stop: ## Остановить контейнеры
	$(DOCKER_COMPOSE) stop

down: ## Убить контейнеры
	$(DOCKER_COMPOSE) down

in-app: ## Войти в app контейнер
	$(DOCKER_COMPOSE) exec app bash
