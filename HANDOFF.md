# Handoff — Redpoint Figma → Next.js

State as of 2026-07-12. Read this plus `README.md` before touching anything.

## Where it stands

The three designed pages (Home, Category, Product) are converted, building clean
(25 static routes), and deployed:

- **Live client preview:** https://redpoint-preview.vercel.app
- Vercel project `redpoint-preview`, SSO protection **disabled** (so the client can
  actually open it), `noindex` + `robots.txt` disallow active.
- Local: `npm run dev`.

## Open task

**The client/user reports issues with the conversion that have not yet been
identified.** They have not yet been listed. Ask for specifics — which section, what's
wrong — before changing anything. Do not re-convert blindly.

The user asked for the redo to be done via **Figma MCP** (the connector is healthy at
the claude.ai account level but its tools were not exposed in the previous session;
a restart was expected to register them). If MCP tools are unavailable, the Figma REST
API works — see below.

## Known-good facts (do not re-derive)

- Figma file key: `Kx2t5PDOZvCVbhCeAnqFgl`
- Frames: Homepage `109:210`, Category `109:795`, Product Details `109:1330`
- **These are the only three pages in the design.** No cart/checkout/account/blog/legal
  exists. Header nav links to `/about`, `/contact`, `/shipping`, `/privacy`,
  `/what-is-redpoint` — all 404 because they were never designed.
- `lib/data.js` copy is transcribed from the design, not invented.
- `lib/images.js` maps 26 assets in `public/images/` (WebP, ~1.1 MB total).

## Known remaining gaps

1. **Fonts.** Futurism and Google Sans are licensed and not public — they fall back to
   Heebo. Playfair Display (section titles, wordmark) is real and loaded.
2. **Spacing not pixel-verified.** Real `itemSpacing`/padding values from the design were
   used, but the rendered pages were never pixel-diffed against the frames.
3. **6 minor assets never exported** (Figma rate-limited): testimonial portraits,
   newsletter background variant, footer wordmark, 2 category-page section images.
   Current stand-ins are working images, not the design's exact ones.

## Figma extraction — read this before fetching anything

- Use **`GET /v1/files/:key/images`** for photography. It returns raw `imageRef → URL`
  image fills.
- Do **NOT** use `GET /v1/images` (render endpoint) for photography: it renders the whole
  node and **bakes the frame's text into the PNG**. This bug already happened once — the
  hero came back with the headline burned in, doubling against the live text.
- `GET /v1/files/:key` (full file) rate-limits hard (429) and stays limited for a long
  time. Use `/nodes?ids=…` subtrees and cache to disk.

## Credentials

Both tokens that were pasted into chat are now dead — nothing live is left in the repo:

- **`VERCEL_TOKEN`** — deleted from https://vercel.com/account/tokens. To deploy again,
  create a fresh one there and put it in `.env.local`. Don't send it over chat or email.
- **`FIGMA_TOKEN`** — expired on its own. Not needed: the Figma **MCP connector** is the
  source of truth for anything visual, and it carries its own auth. Only issue a new PAT
  if a script genuinely needs the REST API.

`.env.local` is gitignored (`.env*.local`) and vercelignored, so it never uploads — but
it is still plaintext on disk, so treat anything you put there accordingly.
