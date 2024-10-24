#!/bin/sh

set -e

composer install --prefer-dist --no-scripts --optimize-autoloader

if [ "${1#-}" != "$1" ]; then
	set -- php "$@"
fi

exec "$@"