#!/bin/bash
#
# Seed the DEMO catalogue so the Elementor product widgets have something to bind to.
#
#   bash wordpress/setup-products.sh
#
# Safe to re-run: products are matched by slug and updated, not duplicated.
#
# ------------------------------------------------------------------------------------
# THIS IS DEMO DATA. It is NOT the client's catalogue.
#
# The Figma repeats ONE placeholder product in every card ("Eclipse Duo", ₪610, the same
# photo twelve times). A real catalogue is needed to lay out a product grid honestly, so
# the Next.js reference invented twelve — same names, prices and photos used here, so the
# two builds can be compared card for card.
#
# The PHOTOGRAPHS are real: each is the fill pulled from its own card node in the Figma.
# The NAMES and PRICES are invented. Delete all of it before the client's catalogue is
# imported — `wp post list --post_type=product --format=ids | xargs wp post delete --force`
# ------------------------------------------------------------------------------------
set -euo pipefail

X="/c/xampp"
DOCROOT="${X}/htdocs/redpoint"
IMAGES="$(cd "$(dirname "$0")/.." && pwd)/public/images"
WP_PHAR="$(cd "$(dirname "$0")" && pwd)/wp-cli.phar"

wp() { "${X}/php/php.exe" -d memory_limit=512M "$WP_PHAR" --path="$DOCROOT" --skip-plugins=elementor "$@"; }

DESC="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna."

# slug | name | regular | sale (or -) | category | image
PRODUCTS="
eclipse-duo|Eclipse Duo|679|610|couples|product-1
velvet-touch|Velvet Touch|320|-|women|product-2
midnight-pulse|Midnight Pulse|520|450|men|product-3
silk-bind-set|Silk Bind Set|210|-|bdsm|product-4
aura-glide|Aura Glide|140|-|lubricants|product-5
lace-bodysuit|Lace Bodysuit|430|380|lingerie|product-6
crimson-wand|Crimson Wand|290|-|women|product-7
onyx-ring|Onyx Ring|165|-|men|product-8
satin-blindfold|Satin Blindfold|95|-|bdsm|product-9
obsidian-plug|Obsidian Plug|120|-|anal|product-10
pulse-mini|Pulse Mini|260|-|sex-toys|product-1
duo-gift-box|Duo Gift Box|390|340|couples|product-2
"

# Import each photo ONCE and remember its attachment id — otherwise re-running the script
# fills the media library with duplicates.
declare -A MEDIA
media_id() {
  local name="$1"
  if [ -n "${MEDIA[$name]:-}" ]; then echo "${MEDIA[$name]}"; return; fi
  local existing
  existing="$(wp post list --post_type=attachment --name="$name" --field=ID 2>/dev/null | head -1)"
  if [ -z "$existing" ]; then
    existing="$(wp media import "${IMAGES}/${name}.webp" --title="$name" --porcelain 2>/dev/null | tail -1)"
  fi
  MEDIA[$name]="$existing"
  echo "$existing"
}

echo "== products"
echo "$PRODUCTS" | while IFS='|' read -r slug name regular sale cat img; do
  [ -z "$slug" ] && continue

  id="$(wp post list --post_type=product --name="$slug" --field=ID 2>/dev/null | head -1)"
  if [ -z "$id" ]; then
    id="$(wp post create --post_type=product --post_title="$name" --post_name="$slug" \
          --post_status=publish --post_content="$DESC" --porcelain)"
  fi

  # The SHORT description (post_excerpt) is what the product card shows — the design draws a
  # 3-line description on every card, and without it each card's info block is ~60px short,
  # pulling the Best Sellers / Worth Attention grids off their design height. Real products
  # carry one; the demo seed must too.
  wp post update "$id" --post_excerpt="$DESC" >/dev/null

  wp post meta update "$id" _regular_price "$regular" >/dev/null
  if [ "$sale" != "-" ]; then
    wp post meta update "$id" _sale_price "$sale" >/dev/null
    wp post meta update "$id" _price "$sale" >/dev/null
  else
    wp post meta delete "$id" _sale_price >/dev/null 2>&1 || true
    wp post meta update "$id" _price "$regular" >/dev/null
  fi
  wp post meta update "$id" _stock_status "instock" >/dev/null
  wp post meta update "$id" _manage_stock "no" >/dev/null
  wp post meta update "$id" _visibility "visible" >/dev/null
  # The design draws five stars on every card. Seed the aggregate so the widgets have a
  # rating to render without needing fake reviews attached to fake customers.
  wp post meta update "$id" _wc_average_rating "5.00" >/dev/null
  wp post meta update "$id" _wc_review_count "10" >/dev/null

  wp post term set "$id" product_cat "$cat" >/dev/null
  wp post term set "$id" product_type simple >/dev/null

  aid="$(media_id "$img")"
  [ -n "$aid" ] && wp post meta update "$id" _thumbnail_id "$aid" >/dev/null

  price="$([ "$sale" != "-" ] && echo "$sale (was $regular)" || echo "$regular")"
  printf '   %-18s %-16s ₪%-16s %s\n' "$slug" "$name" "$price" "$cat"
done

echo
echo "== verify"
echo "  products: $(wp post list --post_type=product --format=count)"
wp eval '
$p = wc_get_products( array( "limit" => 1, "slug" => "eclipse-duo" ) );
if ( $p ) {
  $x = $p[0];
  echo "  sample:   " . $x->get_name() . " — " . strip_tags( $x->get_price_html() ) . "\n";
  echo "  in cat:   " . implode( ", ", wp_list_pluck( get_the_terms( $x->get_id(), "product_cat" ), "name" ) ) . "\n";
  echo "  image:    " . ( $x->get_image_id() ? "yes" : "MISSING" ) . "\n";
}'
