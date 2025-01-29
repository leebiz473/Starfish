# Load the environment variables from .env file (if applicable)
ifneq ("$(wildcard .env)","")
	include .env
endif

# Directory where the SSH key is located (APP_KEY_DIR from env)
APP_KEY_DIR ?= .docker/php/app/.ssh


DEFAULT_GOAL := help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

.PHONY: generate_key
generate_key: ## Generate SSH key if it doesnt exist
	echo "Generating SSH key..."; \
    		chmod +x generate_ssh_key.sh; \
    		./generate_ssh_key.sh;

.PHONY: docker-status
docker-status: ## Check Docker container status
	docker ps -a

.PHONY: docker-build
docker-build: ## Build up docker containers
	docker-compose build --no-cache

.PHONY: docker-start
docker-start: ## Start up docker containers
	docker-compose build --no-cache && docker-compose up -d --build --force-recreate

.PHONY: docker-up
docker-up: ## Build up docker containers
	docker-compose up -d --build --force-recreate

.PHONY: docker-down
docker-down: ## Take down docker containers
	docker-compose down --remove-orphans # Clean up stopped containers

.PHONY: docker-restart
docker-restart: ## Take down and restart docker containers
	docker-compose down --remove-orphans; \
    docker-compose up -d --build --force-recreate; \
    docker ps -a

.PHONY: docker-refresh
docker-refresh: ## Take down and rebuild docker containers from scratch
	docker-compose down --remove-orphans;
	docker builder prune -f; \
	docker container prune -f; \
	docker volume prune -f; \
	docker image prune --all -f; \
	docker-compose build --no-cache; \
	docker-compose up -d --build --force-recreate; \
    docker ps -a
