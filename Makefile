start:
	docker-compose up -d || docker-compose up
stop:
	docker-compose down || docker-compose down
attach:
	docker exec -it county_app /bin/bash
install:
	composer install
