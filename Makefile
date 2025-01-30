# Load the environment variables from .env file (if applicable)
ifneq ("$(wildcard .env)","")
	include .env
endif

# For local builds we always want to use "latest" as tag per default
ifeq ($(ENV),local)
	TAG:=latest
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


CURRENT_DIR := $(shell pwd)


DOCKER_COMPOSE_PROJECT_NAME:=starfish

DOCKER_DIR:=$(pwd)/.docker
DOCKER_COMPOSE_DIR:=$(CURRENT_DIR)

DOCKER_ENV_FILE:=$(CURRENT_DIR)/.env
DOCKER_COMPOSE_FILE:=$(DOCKER_COMPOSE_DIR)/docker-compose.yml

DOCKER_COMPOSE_COMMAND:=docker compose -p $(DOCKER_COMPOSE_PROJECT_NAME) --env-file $(DOCKER_ENV_FILE)

DOCKER_COMPOSE:=$(DOCKER_COMPOSE_COMMAND) -f $(DOCKER_COMPOSE_FILE) --verbose

DOCKER_SERVICE_NAME?=

.PHONY: docker-dry-run
docker-dry-run: ## Checks configuration is parsed correctly and is runnable
	$(DOCKER_COMPOSE) up --build --dry-run

.PHONY: docker-status
docker-status: ## Check Docker container status
	docker ps -a

.PHONY: docker-build
docker-build: ## Build up docker containers
	$(DOCKER_COMPOSE) build --progress=plain $(DOCKER_SERVICE_NAME) --no-cache

.PHONY: docker-start
docker-start: ## Start up docker containers
	docker-compose build --no-cache && docker-compose up -d --build --force-recreate

.PHONY: docker-up
docker-up: ## Build up docker containers
	$(DOCKER_COMPOSE) up -d $(DOCKER_SERVICE_NAME);

.PHONY: docker-down
docker-down: ## Take down docker containers
	$(DOCKER_COMPOSE) down --remove-orphans; \
	docker builder prune -f; \
	docker container prune -f; \
	docker volume prune -f; \
	docker image prune --all -f; \

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

.PHONY: docker-rebuild
docker-rebuild: ## Rebuild containers after failure
	docker-compose up -d --build; \
    docker ps -a