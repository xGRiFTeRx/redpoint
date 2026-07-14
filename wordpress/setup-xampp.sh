#!/bin/bash
#
# Provision the RED POINT WordPress site on XAMPP.
#
#   bash wordpress/setup-xampp.sh
#
# Why XAMPP and not LocalWP: Local provisions on MySQL 8.4, which on this machine takes
# 19-54s to accept connections while Local's health check gives up around 20. It succeeded
# once and failed every time after — a coin flip on disk speed. MariaDB (10s) would fix it
# but could not be selected. XAMPP has no such race, and needs no GUI, so the whole site is
# reproducible from this one script.
#
# Result: http://localhost/redpoint  (admin: http://localhost/redpoint/wp-admin)
#
set -euo pipefail

X="/c/xampp"
DOCROOT="${X}/htdocs/redpoint"
DBNAME="redpoint"
SITEURL="http://localhost/redpoint"
THEME_SRC="$(cd "$(dirname "$0")" && pwd)/redpoint-child"

WP_ADMIN_USER="admin"
WP_ADMIN_PASS="redpoint"          # local dev only — never reachable from outside this box
WP_ADMIN_EMAIL="gevayu@gmail.com"

PHP="${X}/php/php.exe"

# Fetch our own WP-CLI rather than borrowing Local's — Local moves its bundled copy between
# versions (it vanished from extraResources on an update), and this script should not depend
# on Local being installed at all.
WP_PHAR="$(cd "$(dirname "$0")" && pwd)/wp-cli.phar"
if [ ! -f "$WP_PHAR" ]; then
  echo "== fetching wp-cli"
  curl -fsSL -o "$WP_PHAR" https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
fi

# WordPress needs gd (image resizing) and zip; XAMPP ships them but leaves them commented
# out. Enabling is additive and does not affect anything else already in htdocs.
echo "== PHP extensions"
INI="${X}/php/php.ini"
[ -f "${INI}.redpoint.bak" ] || cp "$INI" "${INI}.redpoint.bak"
for ext in gd zip intl; do
  if grep -qE "^\s*;\s*extension\s*=\s*${ext}\s*$" "$INI"; then
    sed -i "s/^\s*;\s*extension\s*=\s*${ext}\s*$/extension=${ext}/" "$INI"
    echo "   enabled ${ext}"
  else
    echo "   ${ext} already on"
  fi
done

echo "== services"
start_if_down() {
  local name="$1" exe="$2"; shift 2
  if tasklist 2>/dev/null | grep -qi "^${name}"; then
    echo "   ${name} already running"
  else
    ( "$exe" "$@" >/dev/null 2>&1 & )
    sleep 4
    echo "   started ${name}"
  fi
}
start_if_down "mysqld.exe" "${X}/mysql/bin/mysqld.exe" --defaults-file="C:/xampp/mysql/bin/my.ini" --standalone
start_if_down "httpd.exe"  "${X}/apache/bin/httpd.exe"

# Wait for MySQL rather than assume — this is the exact failure mode that sank Local.
for i in $(seq 1 30); do
  if "${X}/mysql/bin/mysql.exe" -u root -e "SELECT 1" >/dev/null 2>&1; then
    echo "   mysql accepting connections after ~$((i*2))s"; break
  fi
  sleep 2
  [ "$i" = 30 ] && { echo "   mysql never came up"; exit 1; }
done

echo "== database"
"${X}/mysql/bin/mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS \`${DBNAME}\` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

wp() { "$PHP" -d memory_limit=512M "$WP_PHAR" --path="$DOCROOT" --skip-plugins=elementor "$@"; }

echo "== WordPress core"
mkdir -p "$DOCROOT"
if [ ! -f "${DOCROOT}/wp-settings.php" ]; then
  wp core download --locale=he_IL
fi
if [ ! -f "${DOCROOT}/wp-config.php" ]; then
  wp config create --dbname="$DBNAME" --dbuser=root --dbpass="" --dbhost=127.0.0.1 --locale=he_IL
fi
if ! wp core is-installed 2>/dev/null; then
  wp core install \
    --url="$SITEURL" --title="RED POINT" \
    --admin_user="$WP_ADMIN_USER" --admin_password="$WP_ADMIN_PASS" \
    --admin_email="$WP_ADMIN_EMAIL" --skip-email
fi

echo "== themes + plugins"
wp theme install hello-elementor
wp plugin install elementor woocommerce
# Elementor fatals inside WP-CLI's own bootstrap (core/common/app.php). Harmless to the
# site, but it aborts the command — and it silently stopped WooCommerce activating once.
wp plugin activate elementor || true
wp plugin activate woocommerce

echo "== child theme"
rm -rf "${DOCROOT}/wp-content/themes/redpoint-child"
mkdir -p "${DOCROOT}/wp-content/themes/redpoint-child"
cp -r "${THEME_SRC}/." "${DOCROOT}/wp-content/themes/redpoint-child/"
wp theme activate redpoint-child

# The site is Hebrew, so WordPress must KNOW it is Hebrew — that is what makes it emit
# dir="rtl" and load the RTL stylesheets of core, WooCommerce and Elementor for free.
# Faking RTL in CSS instead is the most expensive mistake available here.
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
wp plugin list --status=active --fields=name,version | sed 's/^/  /'
echo
echo "Site:  ${SITEURL}"
echo "Admin: ${SITEURL}/wp-admin   (${WP_ADMIN_USER} / ${WP_ADMIN_PASS})"
