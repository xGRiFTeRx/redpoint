# Changelog — redpoint-widgets

Every widget that lands bumps the **minor** version and ships a new zip. Fixes to an
existing widget bump the **patch**. `dist/` keeps every build, so staging can be rolled
back to any of them.

Bump `Version:` in `redpoint-widgets.php` — `build-plugin.sh` reads it from there and
refuses to overwrite a zip that already exists, so a forgotten bump fails loudly instead of
silently shipping the wrong contents.

---
## 1.6.0
- **Best Sellers** — "הנמכרים ביותר" (109:372). The first widget that binds to WooCommerce:
  it queries products (best-selling / featured / newest / on-sale / by category) and renders
  the shared product card. Height exact; the pixel diff is dominated by the store surfacing
  different products than the design's four repeated placeholders — the card structure,
  geometry and height all overlap.
- New **shared product card** (`includes/product-card.php` + `redpoint-product-card.css`) —
  Best Sellers, Worth Attention, upsell and "you may like" all render it. Real prices in ₪
  (old struck red, new white), gold star rating, on-sale/new badge, working add-to-cart.
- New **carousel JS** (`redpoint-carousel.js`) — dot paging for the product/blog/testimonial
  rows. Dependency-free, enqueued only where used, degrades to the first page with JS off.
- Demo products now carry a short description (setup-products.sh) — the card shows it, and
  without it each card is ~60px short, pulling the grid off the design height.

## 1.5.0
- **Category Grid** — "למצוא את ההנאה שלך" (109:311). 1440x842, height exact, 3.2% pixel
  diff (the noise floor; the Next.js build scores 3.2% on the same section).
- The section HEADING (two-tone Futurism 80 + kicker) moved to the shared redpoint.css, so
  every section that opens with it — bestsellers, worth-attention, blog — reuses one style.
- The 2x2 needs the RTL pair-swap: an RTL grid fills each row right-to-left, so without it
  the cards come out mirrored (right photo, wrong side). This was THE recurring bug on the
  Next.js build. The repeater stays in reading order; the swap is render-only.

## 1.3.0
- **Hero** (109:212). 1440x800 — height exact, 2.4% pixel diff (the noise floor; the Next.js
  build scores 2.5% on the same comparison).
- The pill strip reads the eight categories **live from WooCommerce**, colour included —
  `setup-woo.sh` stores each category's design colour as `rp_colour` term meta, so adding a
  category in Woo makes it appear here. Switch to a manual list if that is not wanted.
- Three layouts in one widget, because the design uses the section three ways: **full**
  (800px, media + title + pills) for home and category, **compact** (220px, pills only) for
  the product page.
- The hero fill is a **VIDEO** in the Figma (`6037377_Woman_Sexy_1280x720`), not a still.
  The shipped image is only its poster frame — set the Video URL when the client supplies
  the clip.
- The top scrim is **NOT in the design**. Figma's one hero photo is dark so the white nav
  reads over it; the category heroes vary and the nav vanished on the lighter ones. It is
  invisible over a dark image. Toggle it off to match the Figma exactly.


## 1.2.0
- **Footer** (109:750 / 109:763). Blocks land within a pixel of the design's node positions;
  copyright sits LEFT, as the file has it. Footer wordmark is 64px, not the header's 42px.
- The design's third column is 224px tall — a heading plus a 184px list, i.e. **five links**.
  Defaults ship two. **The real list still needs confirming against the Figma.**

## 1.1.0
- **Header** (109:214). Bar 1440x100; logo 176x60 at 60px from the right, icon group 176x32
  at 60px from the left.
- Links needed no RTL flip; the icon row did. Same section, opposite answers.
- Carries the client-requested logo deviation: leading 0.72, where the Figma says 0.56.

## 1.0.0
- **Trust Strip** (109:257). 1440x154 — height exact, 0.4% pixel diff.
- Elementor's icon control cannot render the design's stroke icons (it forces `fill` and
  turns each outline into a solid blob). Icons are inlined from the plugin instead.
- Fixed the plugin zip: PowerShell's `Compress-Archive` writes backslash path separators and
  WordPress refuses the archive (`Could not copy file. redpoint-widgets\assets\`). Built with
  PHP's `ZipArchive` now, and the build verifies the separators before it hands you a file.
