#!/bin/sh

if [ "$1" = "integration" ]; then
    echo "Waiting for database to be ready..."
    /usr/local/bin/wait-for-it.sh "$MYSQL_HOST:3306" \
        --timeout=30 --strict \
            -- echo "Database is up"
    exec composer run "$@"
elif [ "$1" = "unit" ]; then
    exec composer run "$@"
fi

exec "$@"
