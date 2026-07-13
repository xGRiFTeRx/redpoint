#!/bin/bash
#
# Provision a Local (LocalWP) site for RED POINT from scratch.
#
#   bash wordpress/setup-local.sh [site-folder-name]
#
# Assumes: LocalWP has already created the site (Local's GUI is the only way to do that)
# and the site is RUNNING — MySQL has to be up.
#
# Everything else is done here, so the site is reproducible instead of being a pile of
# manual clicks nobody can repeat:
#   - Hello Elementor (parent theme)
#   - Elementor + WooCommerce, activated
#   - the Red Point child theme, activated
#   - Hebrew locale, so WordPress serves RTL natively
#   - admin UI forced back to English (the SITE is Hebrew; the DASHBOARD needn't be)
#
set -euo pipefail

SITE_NAME="${1:-redpoint}"
SITE_DIR="/c/Users/Admin/Local Sites/${SITE_NAME}/app/public"
THEME_SRC="$(cd "$(dirname "$0")" && pwd)/redpoint-child"

LOCAL_RES="/c/Users/Admin/AppData/Local/Programs/Local/resources/extraResources"
PHP_BIN="${LOCAL_RES}/lightning-services/php-8.2.29+0/bin/win64/php.exe"
PHP_EXT="C:/Users/Admin/AppData/Local/Programs/Local/resources/extraResources/lightning-services/php-8.2.29+0/bin/win64/ext"
WP_PHAR="C:/Users/Admin/AppData/Local/Programs/Local/resources/extraResources/bin/wp-cli/wp-cli.phar"

if [ ! -d "$SITE_DIR" ]; then
  echo "No site at: $SITE_DIR"
  echo "Create it in Local first (name it exactly '${SITE_NAME}'), let it finish, then re-run."
  exit 1
fi

# Local's MySQL listens on a per-site port and tells its OWN php.ini about it. An external
# php.exe defaults to 3306 and fails with "Error establishing a database connection", so
# find the port from the running mysqld rather than hardcoding it — it differs per site and
# changes if Local reassigns ports.
DB_PORT="$(
  netstat -ano | grep LISTENING | grep -E ':(100[0-9]{2})\b' | awk '{print $2, $5}' |
  while read -r addr pid; do
    if tasklist //FI "PID eq ${pid}" 2>/dev/null | grep -qi mysqld; then
      echo "${addr##*:}"
    fi
  done | tail -1
)"

if [ -z "${DB_PORT}" ]; then
  echo "Could not find a running mysqld. Is the site started in Local?"
  exit 1
fi
echo "MySQL on port ${DB_PORT}"

# Elementor fatals inside WP-CLI's bootstrap (core/common/app.php) — a CLI-context bug in
# Elementor, harmless to the site itself. Skipping it on CLI is the standard workaround.
wp() {
  "$PHP_BIN" \
    -d extension_dir="$PHP_EXT" \
    -d extension=php_mysqli.dll -d extension=php_mbstring.dll \
    -d extension=php_openssl.dll -d extension=php_curl.dll \
    -d extension=php_zip.dll -d extension=php_gd.dll -d extension=php_exif.dll \
    -d mysqli.default_port="${DB_PORT}" \
    -d memory_limit=512M \
    "$WP_PHAR" --path="$SITE_DIR" --skip-plugins=elementor "$@"
}

echo "== WordPress $(wp core version) at $(wp option get siteurl)"

echo "== themes + plugins"
wp theme install hello-elementor
wp plugin install elementor woocommerce
wp plugin activate elementor || true   # see the Elementor CLI note above
wp plugin activate woocommerce

echo "== child theme"
rm -rf "${SITE_DIR}/wp-content/themes/redpoint-child"
mkdir -p "${SITE_DIR}/wp-content/themes/redpoint-child"
cp -r "${THEME_SRC}/." "${SITE_DIR}/wp-content/themes/redpoint-child/"
wp theme activate redpoint-child

# The site is Hebrew, so WordPress must KNOW it is Hebrew — that is what makes it emit
# dir="rtl" and load every RTL stylesheet (core, Woo, Elementor) for free. Faking RTL with
# CSS instead is the single most expensive mistake available here; on the Next.js build,
# mirrored rows were far and away the most recurring bug.
echo "== locale"
wp language core install he_IL --activate

# ...but the DASHBOARD in Hebrew helps nobody. Per-user locale overrides the site locale.
for uid in $(wp user list --field=ID); do
  wp user meta update "$uid" locale en_US >/dev/null
done

echo
echo "== verify"
wp eval 'echo "  RTL:    " . ( is_rtl() ? "yes" : "NO — RTL styles will not load" ) . "\n";'
echo "  theme:  $(wp theme list --status=active --field=name)"
echo "  parent: $(wp theme list --field=name --status=parent)"
wp plugin list --status=active --fields=name,version | sed 's/^/  /'
echo
echo "Done: $(wp option get siteurl)"
