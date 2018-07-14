#!/usr/bin/env bash
printf "\033c"

source ./config.sh;

docker exec -tiu root $CONTAINER_NAME bash;