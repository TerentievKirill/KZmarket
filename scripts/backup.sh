#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
if [[ ! -f .env ]]; then
  echo 'Missing .env' >&2
  exit 1
fi

backup_dir="backups/$(date -u +%Y%m%dT%H%M%SZ)"
mkdir -p "$backup_dir"

docker compose exec -T db sh -ec \
  'exec mariadb-dump --single-transaction -u "$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
  > "$backup_dir/database.sql"

docker compose exec -T wordpress tar -czf - -C /var/www/html wp-content \
  > "$backup_dir/wp-content.tar.gz"

echo "Backup saved to $backup_dir"
echo 'Store this directory securely: it contains customers, orders and personal data.'
