build:
	docker-compose build --pull
	docker-compose up -d

start:
	docker-compose up -d

shell:
	docker exec -it onespec sh

compile:
	docker exec -it onespec /app/bin/build.sh

test:
	docker exec -it onespec /app/bin/onespec run $(hash)
