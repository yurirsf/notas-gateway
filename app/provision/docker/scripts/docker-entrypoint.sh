#!/usr/bin/env ash
set -eu

echo "Running $APP_ENV entrypoint..."

if [ $DEPLOY_CMD = "php-fpm" ]; then
    # Inicia o processo do NGiNX
    nginx -g "daemon on;"
fi

echo "Done entrypoint!"

exec "$@"
