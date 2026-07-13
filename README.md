# Redpoint — Next.js Reference Build

A Next.js + Tailwind reference implementation of the **RED POINT** Figma design.

**This is not a storefront and is not meant to be deployed.** It exists so the Figma
design can be read as clean, componentized code, making it straightforward to rebuild
each section as Elementor widgets on the real WordPress/WooCommerce site. It is not a
headless WordPress front end.

## What the design actually contains

The Figma file (`Kx2t5PDOZvCVbhCeAnqFgl`) has **three page designs only**:

| Frame      | Page            | Route                  |
| ---------- | --------------- | ---------------------- |
| `109:210`  | Homepage        | `/`                    |
| `109:795`  | Category        | `/category/[slug]`     |
| `109:1330` | Product Details | `/product/[slug]`      |

There is **no cart, checkout, my-account, blog index, or legal page in the design.**
The header links to `/about`, `/contact`, `/shipping`, `/privacy` and
`/what-is-redpoint`, but those pages were never designed — the links 404 by design,
not by mistake. If those pages are needed, they need designing first.

## Design tokens

Read from the Figma file and encoded in `tailwind.config.js`:

- **Colours** — `bg #0C0C0C`, `panel #111111`, `surface #252525`, `well #000000`,
  `accent #FF3B3B`, `body #E6E6E6`, `muted #818181`, `navlink #D7D7D7`.
- **Category CTA colours** — each card in "למצוא את ההנאה שלך" has its own:
  `cat-pink #FF3DD1`, `cat-orange #FF8A2B`, `cat-yellow #FFD13B`, `cat-red #FF3B3B`.
- **Layout** — 1440px shell, 60px gutters, `rounded-pill` = 100px.

## Fonts

The design uses three faces:

| Face                | Used for                                   | Status                          |
| ------------------- | ------------------------------------------ | ------------------------------- |
| **Playfair Display**| Section titles (80/72), "רד פוינט" wordmark | Free Google font — **loaded**   |
| **Futurism**        | Headings (30 / 40 / 50 / 70 / 100)          | Licensed — **not public**       |
| **Google Sans**     | Body, nav, buttons                          | Licensed — **not public**       |

Futurism and Google Sans fall back to **Heebo** (loaded in `app/layout.js`) so Hebrew
renders correctly. To use the real faces, drop the licensed files into `public/fonts/`
and uncomment the `@font-face` rules in `app/globals.css`.

## Content

`lib/data.js` is transcribed from the design, not invented. That includes the eight
real categories, the nav links, hero and section copy, the trust/value badge strips,
and the promo and newsletter text. Product names and prices beyond the design's single
placeholder ("Eclipse Duo", ₪610/₪679) are filler, and the product descriptions are the
design's own lorem ipsum.

## Images

`public/images/` holds assets pulled from Figma and resized to WebP (~1.1 MB total).

They are the **raw image fills**, fetched via `GET /v1/files/:key/images`, which returns
an `imageRef → URL` map. Do *not* use `GET /v1/images` (the render endpoint) for these:
it renders a whole node, which **bakes the frame's text and child layers into the PNG**,
so you end up with headlines burned into the hero.

Both endpoints are aggressively rate-limited (HTTP 429) on the full-file `GET /v1/files`
call in particular — fetch node subtrees instead, and back off.

## Structure

- `app/` — the three routes above, plus the shared layout.
- `components/` — one component per Figma section. `Media` is the image slot (it renders
  a grey placeholder when an asset is missing); `Icon` carries the Phosphor icon name
  through to markup as `data-phosphor-icon` so Elementor can map each to its icon widget.
- `lib/data.js` — all design copy. `lib/images.js` — the asset map.

## Running locally

```
npm install
npm run dev      # http://localhost:3000
```
