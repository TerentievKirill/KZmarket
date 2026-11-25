#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."
if [[ ! -f .env ]]; then
  echo 'Create .env from .env.example and set real credentials first.' >&2
  exit 1
fi
if grep -Eq 'replace-with-|admin@example.com' .env; then
  echo 'Replace all example passwords and the admin email in .env before installation.' >&2
  exit 1
fi

docker compose up -d db wordpress

docker compose run --rm --entrypoint /bin/sh wpcli -ec '
  until test -f wp-includes/version.php && wp db check >/dev/null 2>&1; do
    echo "Waiting for WordPress and database..."
    sleep 3
  done

  if ! wp core is-installed >/dev/null 2>&1; then
    wp core install \
      --url="$SITE_URL" \
      --title="$SITE_TITLE" \
      --admin_user="$WP_ADMIN_USER" \
      --admin_password="$WP_ADMIN_PASSWORD" \
      --admin_email="$WP_ADMIN_EMAIL" \
      --skip-email
    wp option update blog_public 0
  fi

  if ! wp plugin is-installed woocommerce; then
    wp plugin install woocommerce --activate
  else
    wp plugin activate woocommerce
  fi

  if ! wp option get kzmarket_initial_setup_done >/dev/null 2>&1; then
    wp theme activate kzmarket
  fi
  wp eval-file /opt/kzmarket/scripts/setup-woocommerce.php
  wp eval-file /opt/kzmarket/scripts/setup-pages.php

  echo "Shop manager role is provided by WooCommerce. Create manager users in Users → Add New."
'

echo "Ready: visit your configured SITE_URL. Admin: SITE_URL/wp-admin/"
