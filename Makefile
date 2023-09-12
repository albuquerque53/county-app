build:
	cp .env.example .env
	docker-compose up -d || docker-compose up -d
	docker exec -t county_app sh -c "composer install && php artisan key:generate"
	exit
	npm install
	npm run build
	docker-compose down
up:
	vendor/bin/sail up
up_d:
	vendor/bin/sail up -d
	npm run dev &
stop:
	vendor/bin/sail down
test:
	vendor/bin/sail test
