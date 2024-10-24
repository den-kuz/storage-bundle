DOCKER_COMPOSE?=docker compose

help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n"} /^[$$()% a-zA-Z_-]+:.*?##/ { printf "  \033[32m%-30s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

build: ## Build container
	COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 $(DOCKER_COMPOSE) build

up: ## Up containers
	$(DOCKER_COMPOSE) up -d --remove-orphans

stop: ## Stop containers
	$(DOCKER_COMPOSE) stop

down: ## Kill containers
	$(DOCKER_COMPOSE) down

in-app: ## Exec bash in container
	$(DOCKER_COMPOSE) exec app bash

phpcsfix: ## Apply code style fixes
	$(DOCKER_COMPOSE) exec app composer phpcsfix
