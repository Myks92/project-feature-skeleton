.DEFAULT_GOAL: help

DOCKER_COMPOSE = docker compose
PHP = $(DOCKER_COMPOSE) run --rm api-php-cli
COMPOSER = $(PHP) composer
APP_CLI = $(COMPOSER) app

help:
	@$(COMPOSER) && @$(APP_CLI)
	@grep -E '^[1-9a-zA-Z_-]+:.*?## .*$$|(^#--)' $(MAKEFILE_LIST)  | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m %-43s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m #-- /[33m/'

#-- Управление проектом
init: init-ci ## Инициализация
init-ci: docker-down-clear api-clear docker-pull docker-build docker-up api-init  ## Инициализация CI
up: docker-up ## Запуск контейнеров
down: docker-down ## Остановка контейнеров
restart: down up ## Перезапуск контейнеров

check: lint check-cs analyze check-deps check-scheme test  ## Проверка
lint: api-check-lint ## Проверка синтаксиса файлов
analyze: api-analyze ## Анализ кода
test: api-test api-fixtures ## Запуск тестов
test-unit: api-test-unit ## Запуск Unit тестов
test-functional: api-test-functional api-fixtures ## Запуск Functional тестов
check-cs: api-check-cs ## Проверка стиля кода
check-scheme: api-check-schema ## Проверка схемы базы данных
check-deps: api-check-composer-validate api-check-composer-audit api-check-composer-unused ## Проверка зависимостей

update-deps: api-composer-update restart ## Обновление зависимостей

fix: fix-cs fix-refactor fix-types ## Исправление ошибок
fix-cs: api-fix-cs ## Исправление ошибок стиля кода
fix-refactor: api-fix-refactor ## Исправление рефакторинга кода
fix-types: api-fix-types ## Исправление типизации

#-- Docker
docker-up: ## Запуск контейнеров
	$(DOCKER_COMPOSE) up -d

docker-down: ## Остановка контейнеров
	$(DOCKER_COMPOSE) down --remove-orphans

docker-down-clear: ## Остановка контейнеров с очисткой volumes
	$(DOCKER_COMPOSE) down -v --remove-orphans

docker-pull: ## Получение образов
	$(DOCKER_COMPOSE) pull

docker-build: ## Сборка контейнеров
	$(DOCKER_COMPOSE) build --pull

#-- Api
api-init: api-permissions api-composer-install api-wait-db api-migrations api-fixtures ## Инициализация

api-clear: ## Очистка временных файлов
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/*'
	@echo "Cache and logs have been deleted !"

api-permissions: ## Установка разрешений
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var/cache var/log

api-composer-install: ## Установка зависимостей
	$(COMPOSER) install

api-composer-update: ## Обновление зависимостей
	$(COMPOSER) update

api-wait-db:
	$(PHP) wait-for-it api-postgres:5432 -t 30

api-migrations: ## Применение миграций
	$(APP_CLI) doctrine:migrations:migrate -- --no-interaction --allow-no-migration

api-migrations-diff: ## Создание миграций
	$(APP_CLI) doctrine:migrations:diff -- --no-interaction

api-fixtures: ## Применение фикстур
	- $(APP_CLI) doctrine:fixtures:load -- --no-interaction

api-check: ## Проверка
	make -j api-check-composer-validate api-check-composer-audit api-check-composer-unused api-check-schema api-check-lint api-check-cs api-analyze api-test

api-check-lint: ## Проверка синтаксиса кода
	$(COMPOSER) lint
	$(APP_CLI) lint:container
	$(APP_CLI) lint:twig templates
	$(APP_CLI) lint:yaml config -- --parse-tags

api-check-cs: ## Проверка стиля кода
	$(COMPOSER) php-cs-fixer fix -- --dry-run --diff

api-check-schema: ## Проверка схемы база данных
	$(APP_CLI) doctrine:schema:validate

api-check-composer-validate: ## Проверка валидности composer.json
	$(COMPOSER) validate

api-check-composer-audit: ## Проверить пакеты на уязвимость и безопасность
	$(COMPOSER) audit

api-check-composer-unused: ## Проверить неиспользуемые пакеты
	$(COMPOSER) unused

api-analyze: api-analyze-layer api-analyze-refactor api-analyze-types ## Анализ

api-analyze-layer: ## Анализ слоёв
	$(COMPOSER) deptrac

api-analyze-refactor: ## Анализ рефакторинга
	$(COMPOSER) rector -- --dry-run

api-analyze-types: ## Анализ типизации
	$(COMPOSER) psalm -- --no-diff

api-test: ## Запуск тестов
	$(COMPOSER) test

api-test-coverage: ## Запуск тестов с покрытием кода
	$(COMPOSER) test-coverage

api-test-unit: ## Запуск Unit тестов
	$(COMPOSER) test -- --testsuite=unit

api-test-unit-coverage: ## Запуск Unit тестов с покрытием кода
	$(COMPOSER) test-coverage -- --testsuite=unit

api-test-functional: ## Запуск Functional тестов
	$(COMPOSER) test -- --testsuite=functional

api-test-functional-coverage: ## Запуск Functional тестов с покрытием кода
	$(COMPOSER) test-coverage -- --testsuite=functional

api-fix-cs: ## Исправление стиля кода
	$(COMPOSER) php-cs-fixer fix

api-fix-refactor: ## Исправление рефакторинга
	$(COMPOSER) rector

api-fix-types: ## Исправление типизации
	$(COMPOSER) psalm
