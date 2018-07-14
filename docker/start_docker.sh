#!/usr/bin/env bash

source ./config.sh;

docker-compose down
docker-compose up -d --build
# start php, memcached
docker exec -u root $CONTAINER_NAME service php7.2-fpm start
# start nginx
docker exec -u root $CONTAINER_NAME service nginx start &
# start supervisor
# docker exec -u deployer $CONTAINER_NAME /usr/bin/supervisord

if [ "$?" = "0" ]; then
	title="Do you want to enter into the container?"
	prompt="Pick an option: "
	options=("Yes, as root" "Yes, as deployer" "No")
	echo "$title"
	PS3="$prompt "

	select opt in "${options[@]}"; do
		case "$REPLY" in

		1 ) /bin/bash ./enter_root.sh
		break;;
		2 ) /bin/bash ./enter_deployer.sh
		break;;
		*) break;;

		esac
	done

else
	echo "--------------------------------------" 1>&2
	exit 1
fi;
