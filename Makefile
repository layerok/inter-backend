db-seed:
	docker compose run --rm php php artisan migrate:fresh --seed
