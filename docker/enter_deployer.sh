#!/usr/bin/env bash
printf "\033c"

source ./config.sh;

docker exec -tiu deployer $CONTAINER_NAME bash;