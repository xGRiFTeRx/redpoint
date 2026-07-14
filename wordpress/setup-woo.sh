#!/bin/bash
#
# Configure the WooCommerce store for RED POINT.
#
#   bash wordpress/setup-woo.sh
#
# Safe to re-run: every step is idempotent.
#
# This does the parts that come straight from the design and are not a judgement call:
# the store locale, the currency, and the eight product categories. Products and the
# filter-rail attributes are NOT done here — see the note at the bottom.
#
set -euo pipefail

X="/c/xampp"
DOCROOT="${X}/htdocs/redpoint"
WP_PHAR="$(cd "$(dirname "$0")" && pwd)/wp-cli.phar"

# MSYS_NO_PATHCONV: Git Bash rewrites any argument that looks like a unix path, so
# '/%postname%/' arrives at WP-CLI as '/C:/Program Files/Git/%postname%/'. Windows-style
# paths for --path, and no conversion for the rest.
wp() {
  MSYS_NO_PATHCONV=1 "${X}/php/php.exe" -d memory_limit=512M "$WP_PHAR" \
    --path="C:/xampp/htdocs/redpoint" --skip-plugins=elementor "$@"
}

echo "== store locale"
# The design prices in shekels ("₪610", 109:382) and the site is Hebrew. WooCommerce
# defaults to USD / US:CA, which would render every price wrong.
wp option update woocommerce_default_country "IL"
wp option update woocommerce_currency "ILS"
# The design writes the symbol BEFORE the number — "₪610", not "610 ₪" — even though the
# page is RTL. Match the file, not the habit.
wp option update woocommerce_currency_pos "left"
wp option update woocommerce_price_thousand_sep ","
wp option update woocommerce_price_decimal_sep "."
wp option update woocommerce_price_num_decimals "0"   # the design shows no decimals
wp option update woocommerce_weight_unit "kg"
wp option update woocommerce_dimension_unit "cm"

# Stop Woo nagging through the onboarding wizard on every admin load. Cosmetic only —
# Woo changes these option names between versions, so never let it abort the run.
wp option update woocommerce_onboarding_profile '{"skipped":true}' --format=json || true
wp option update woocommerce_task_list_hidden yes || true

# TURN OFF "COMING SOON". Recent WooCommerce ships this ON for a fresh install, and it
# replaces the ENTIRE storefront with a "our store is launching soon" splash — products,
# shop archive and all. It looks exactly like the catalogue is broken, and it is not.
wp option update woocommerce_coming_soon no
wp option update woocommerce_store_pages_only no
wp option update woocommerce_private_link no

# Pretty permalinks. WooCommerce archives and Elementor both need them, and WordPress
# defaults to plain. WP-CLI will NOT write .htaccess ("requires special configuration"),
# so it is written by hand below — without it every URL but the home page 404s.
wp rewrite structure '/%postname%/' --hard || true
wp rewrite flush --hard || true

HTACCESS="${DOCROOT}/.htaccess"
if [ ! -f "$HTACCESS" ]; then
  # RewriteBase is /redpoint/ because the site sits in a SUBDIRECTORY of htdocs, not at
  # the docroot. Getting that wrong is the usual cause of "home works, everything 404s".
  cat > "$HTACCESS" <<'HTA'
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /redpoint/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /redpoint/index.php [L]
</IfModule>
# END WordPress
HTA
  echo "   wrote .htaccess"
fi

echo "== product categories"
# The eight categories from the design (109:1174 in the filter rail, and the nav pills).
# Each owns a colour that borders its nav pill and tints its card CTA — stored as term
# meta so an Elementor widget can read it instead of hardcoding a hex per category.
#
# Order is the site's own reading order: Hebrew is RTL, so לנשים is the FIRST pill the
# eye meets. (Note this is the REVERSE of the order the Figma file lists them in — its
# canvas is LTR. See the RTL note in lib/data.js.)
add_cat() {
  local slug="$1" name="$2" colour="$3" order="$4"
  if ! wp term list product_cat --field=slug | grep -qx "$slug"; then
    wp term create product_cat "$name" --slug="$slug" --porcelain >/dev/null
  fi
  local id
  id="$(wp term list product_cat --slug="$slug" --field=term_id)"
  wp term meta update "$id" rp_colour "$colour" >/dev/null
  wp term meta update "$id" order "$order" >/dev/null
  printf '   %-12s %-16s %s\n' "$slug" "$name" "$colour"
}

add_cat women      "לנשים"          "#FF4FA8" 1
add_cat men        "לגברים"         "#25D9F5" 2
add_cat couples    "לזוגות"         "#FF3DD1" 3
add_cat sex-toys   "צעצועי סקס"     "#FF8A2B" 4
add_cat anal       "חוויה אנאלית"   "#A45CFF" 5
add_cat lingerie   "ביגוד ולונז'ארי" "#FFD13B" 6
add_cat bdsm       "פטיש ו-BDSM"    "#36E07A" 7
add_cat lubricants "חומרי סיכוך"    "#FF3B3B" 8

echo
echo "== verify"
echo "  country:  $(wp option get woocommerce_default_country)"
echo "  currency: $(wp option get woocommerce_currency) ($(wp option get woocommerce_currency_pos))"
echo "  decimals: $(wp option get woocommerce_price_num_decimals)"
echo "  cats:     $(wp term list product_cat --field=name | tr '\n' ' ')"

# NOT DONE HERE, on purpose:
#
#   Products and the filter-rail ATTRIBUTES (זמינות / סוג / חומר / צבע / פונקציה / מותג /
#   מחיר / גודל / דירוג). Only "קטגוריה" is filled in in the Figma — the design leaves the
#   other nine filter groups collapsed and EMPTY. Everything in the Next.js build's
#   `productContent` for those fields is sample data I invented so the rail could be
#   demonstrated working, and it is flagged as such in lib/data.js.
#
#   On the real store these are WooCommerce product attributes driven by the client's
#   actual catalogue. Inventing a taxonomy here and letting it harden into the Elementor
#   build is exactly the kind of drift this project exists to avoid — so the attribute set
#   needs the client's real product data, not a guess.
