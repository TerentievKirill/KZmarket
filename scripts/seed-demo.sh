#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
docker compose run --rm --entrypoint /bin/sh wpcli -ec 'wp eval-file /opt/kzmarket/scripts/seed-demo.php'
