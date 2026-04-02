#!/bin/sh

set -eu

PORT_VALUE="${PORT:-8080}"
MAX_ATTEMPTS="${MIGRATION_MAX_ATTEMPTS:-3}"
SLEEP_SECONDS="${MIGRATION_RETRY_DELAY:-3}"
ATTEMPT=1

mkdir -p storage/logs storage/cache storage/uploads storage/sessions
chmod -R 775 storage || true

echo "Starting Elo 42 container on port ${PORT_VALUE}"

MIGRATION_OK=0
while [ "${ATTEMPT}" -le "${MAX_ATTEMPTS}" ]; do
  echo "Running migrations (attempt ${ATTEMPT}/${MAX_ATTEMPTS})..."

  if php migrate.php run; then
    echo "Migrations completed successfully."
    MIGRATION_OK=1
    break
  fi

  echo "Migration attempt failed. Retrying in ${SLEEP_SECONDS}s..."
  ATTEMPT=$((ATTEMPT + 1))
  sleep "${SLEEP_SECONDS}"
done

if [ "${MIGRATION_OK}" -eq 0 ]; then
  echo "WARNING: Migrations failed after ${MAX_ATTEMPTS} attempts. Starting server anyway."
fi

echo "Starting PHP server on port ${PORT_VALUE}..."
exec php -S "0.0.0.0:${PORT_VALUE}" -t public
