include .env

explain:
	@echo "Run attach_fpm"

start:
	docker-compose up -d --build

stop:
	docker-compose down

restart: | stop start

attach_fpm:
	docker exec -it ${PROJECT_NAME}_fpm bash
