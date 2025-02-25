.PHONY: up down test unit integration

all:
	docker compose --profile production build
	docker compose --profile development build
	docker compose --profile test build
	docker compose --profile integration build
up:
	docker compose up -d apache mysql phpmyadmin

down:
	docker compose down -v

test: unit integration

unit:
	@docker compose  --profile test up -d app_test
	@docker compose  --profile test run --rm app_test unit
	@docker compose  --profile test down -v


integration: 
	@docker compose --profile integration up -d mysql_integration app_test 
	@docker compose --profile integration run --rm app_test integration
	@docker compose --profile integration down -v

clean:
	docker compose down --rmi all --volumes
	docker system prune -a -f
