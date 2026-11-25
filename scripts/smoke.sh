#!/usr/bin/env bash
set -euo pipefail

site_url="${1:-http://localhost:8080}"
site_url="${site_url%/}"

for path in / /shop/ /cart/ /checkout/ /my-account/; do
  status="$(curl -sS -L -o /dev/null -w '%{http_code}' "${site_url}${path}")"
  if [[ "$status" != 200 ]]; then
    echo "FAIL ${path}: HTTP ${status}" >&2
    exit 1
  fi
  echo "OK ${path}: HTTP ${status}"
done

homepage="$(curl -fsSL "$site_url")"
if [[ "$homepage" != *'Каталог'* && "$homepage" != *'catalog-toggle'* ]]; then
  echo 'Homepage does not appear to contain the shop navigation.' >&2
  exit 1
fi
echo 'Basic smoke checks passed.'
