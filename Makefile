.DEFAULT_GOAL: help

DOCKER_COMPOSE = docker-compose
EXEC_PHP = $(DOCKER_COMPOSE) run --rm api-php-cli
COMPOSER = $(EXEC_PHP) composer
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

#-- Тестирование и проверка
check: check-lint check-cs check-analyze check-deps check-scheme test  ## Проверка
check-lint: api-check-lint ## Проверка синтаксиса файлов
check-cs: api-check-cs ## Проверка стиля кода
check-analyze: api-check-analyze ## Проверка анализаторами кода
check-scheme: api-check-schema-validate ## Проверка схемы базы данных
check-deps: api-check-composer-validate api-check-composer-audit api-check-composer-unused ## Проверка зависимостей
test: api-test api-fixtures ## Запуск тестов
test-unit: api-test-unit ## Запуск Unit тестов
test-functional: api-test-functional api-fixtures ## Запуск Functional тестов

#-- Автоматическое исправление и обновление
fix: fix-cs ## Исправление
fix-cs: api-fix-cs ## Исправление стиля кода
fix-refactor: api-fix-refactor ## Исправить рефакторинг кода
update-deps: api-composer-update restart ## Обновление зависимостей

#-- Docker
docker-up: ## Запуск контейнеров
	docker-compose up -d

docker-down: ## Остановка контейнеров
	docker-compose down --remove-orphans

docker-down-clear: ## Остановка контейнеров с очисткой volumes
	docker-compose down -v --remove-orphans

docker-pull: ## Получение образов
	docker-compose pull

docker-build: ## Сборка контейнеров
	docker-compose build --pull

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
	$(EXEC_PHP) wait-for-it api-postgres:5432 -t 30

api-migrations: ## Применение миграций
	- $(COMPOSER) app doctrine:migrations:migrate -- --no-interaction

api-migrations-diff: ## Создание миграций
	$(COMPOSER) app doctrine:migrations:diff -- --no-interaction

api-fixtures: ## Применение фикстур
	- $(COMPOSER) app doctrine:fixtures:load -- --no-interaction

api-check: ## Проверка
	make -j api-check-composer-validate api-check-composer-audit api-check-composer-unused api-check-schema-validate api-check-lint api-check-cs api-check-analyze api-test

api-check-lint: ## Проверка синтаксиса кода
	$(COMPOSER) lint
	$(COMPOSER) app lint:container
	$(COMPOSER) app lint:twig templates
	$(COMPOSER) app lint:yaml config -- --parse-tags

api-check-cs: ## Проверка стиля кода
	$(COMPOSER) php-cs-fixer fix -- --dry-run --diff

api-check-analyze: api-check-layer api-check-refactor api-check-analyze-psalm ## Проверка статическими анализаторами

api-check-layer: ## Проверить зависимости слоёв кода
	$(COMPOSER) deptrac

api-check-refactor: ## Проверка автоматического рефакторинга и актуализация кода
	$(COMPOSER) rector -- --dry-run

api-check-analyze-psalm: ## Проверка анализатора Psalm
	$(COMPOSER) psalm -- --no-diff

api-check-schema-validate: ## Проверка валидация схемы база данных
	$(COMPOSER) app doctrine:schema:validate

api-check-composer-validate: ## Валидация зависимостей
	$(COMPOSER) validate

api-check-composer-audit: ## Проверить пакеты на уязвимость и безопасность
	$(COMPOSER) audit

api-check-composer-unused: ## Проверить неиспользуемые пакеты
	$(COMPOSER) unused

api-fix-cs: ## Исправление стиля кода
	$(COMPOSER) php-cs-fixer fix

api-fix-refactor: ## Исправить рефакторинг кода
	$(COMPOSER) rector

api-fix-psalm: ## Исправление анализатора Psalm кода
	$(COMPOSER) psalm

api-test: ## Запуск тестов
	$(COMPOSER) test

api-test-coverage: ## Запуск тестов с покрытием кода
	$(COMPOSER) test-coverage

api-test-unit: ## Запуск Unit тестов
	$(COMPOSER) test -- --testsuite=unit

api-test-unit-coverage: ## Запуск Unit тестов с покрытием кода
	$(COMPOSER) test-coverage -- --testsuite=unit

api-test-functional:  ## Запуск Functional тестов
	$(COMPOSER) test -- --testsuite=functional

api-test-functional-coverage: ## Запуск Functional тестов с покрытием кода
	$(COMPOSER) test-coverage -- --testsuite=functional