build:
	docker-compose build --pull
	docker-compose up -d

start:
	docker-compose up -d

shell:
	docker exec -it onespec sh

test\:%:
	docker exec -it onespec sh -c "/app/scripts/tests.sh $@ '$(path)' '$(class)' '$(line)'"
