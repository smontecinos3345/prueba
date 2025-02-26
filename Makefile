.PHONY: up down test unit integration

build:
	docker compose --profile production pull
	docker compose --profile production build
	docker compose --profile development pull
	docker compose --profile development build
	docker compose --profile test build
	docker compose --profile integration pull
	docker compose --profile integration build
up:
	docker compose --profile production up

dev:
	docker compose --profile development up

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
