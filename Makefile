build:
	cp .env.example .env
	docker-compose up -d || docker-compose up -d
	docker exec -t county_app sh -c "composer install && php artisan key:generate"
	exit
	npm install
	docker-compose down
up:
	npm run build
	vendor/bin/sail up
up_d:
	vendor/bin/sail up -d
	npm run dev &
stop:
	vendor/bin/sail down
test:
	vendor/bin/sail test
