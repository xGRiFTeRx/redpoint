#!/bin/bash
#
# Seed DEMO blog posts so the Blog Teaser has something to render.
#
#   bash wordpress/setup-posts.sh
#
# THIS IS DEMO DATA. The Figma fills the blog cards with lorem — the client writes real
# articles later. The featured images are the three real blog photos from the design,
# cycled. Six posts so the teaser pages (two pages of three).
#
set -euo pipefail

X="/c/xampp"
DOCROOT="C:/xampp/htdocs/redpoint"
IMAGES="$(cd "$(dirname "$0")/.." && pwd)/public/images"
WP_PHAR="$(cd "$(dirname "$0")" && pwd)/wp-cli.phar"

wp() { MSYS_NO_PATHCONV=1 "${X}/php/php.exe" -d memory_limit=512M "$WP_PHAR" --path="$DOCROOT" --skip-plugins=elementor,elementor-pro "$@"; }

TITLE="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor"
EXCERPT="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna. adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna. tempor incididunt ut labore et dolore magna."
BODY="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."

# Import an image once (matched by attachment slug) and echo its id. No associative-array
# cache — `declare -A` is not reliable in the Git Bash that ships here, and the media list
# lookup already makes re-runs idempotent.
media_id() {
	local name="$1" existing
	existing="$(wp post list --post_type=attachment --name="$name" --field=ID 2>/dev/null | head -1)"
	if [ -z "$existing" ]; then
		existing="$(wp media import "${IMAGES}/${name}.webp" --title="$name" --porcelain 2>/dev/null | tail -1)"
	fi
	echo "$existing"
}

echo "== blog posts"
for i in 1 2 3 4 5 6; do
	slug="rp-post-${i}"
	img="blog-$(( (i - 1) % 3 + 1 ))"   # cycle blog-1/2/3

	id="$(wp post list --post_type=post --name="$slug" --field=ID 2>/dev/null | head -1)"
	if [ -z "$id" ]; then
		id="$(wp post create --post_type=post --post_status=publish \
			--post_title="$TITLE" --post_name="$slug" \
			--post_excerpt="$EXCERPT" --post_content="$BODY" --porcelain)"
	fi

	aid="$(media_id "$img")"
	[ -n "$aid" ] && wp post meta update "$id" _thumbnail_id "$aid" >/dev/null

	printf '   %-11s <- %s\n' "$slug" "$img"
done

echo
echo "posts: $(wp post list --post_type=post --post_status=publish --format=count)"
