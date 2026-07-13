# Pixel-perfect audit

Renders each Figma frame, screenshots the same section on the built site, and diffs them.
Reports a differing-pixel percentage and a height delta per section.

This is the check the client asked for ("perform a Pixel Perfect check between the source
and the site you built, and specify what requires more precision"). Keep it working — it
is also what will validate the WordPress/Elementor build against the same source.

```bash
npm install
npx playwright install chrome     # once

npm run audit                     # against http://127.0.0.1:3440 (a local next dev)
npm run audit:live                # against the deployed Vercel build
node pixel-audit.js http://redpoint.local   # against the WordPress build
```

## Reading the output

```
  worth           1.8%   height exact
  cat-browser    30.8%   +3px vs design (1586)   <-- DRIFT
```

**~2–5% is the noise floor, not a failure.** Figma and Chrome hint and antialias text
differently, so a text-heavy section never reaches zero. Above ~12% is real drift.

**A high score is not automatically a bug — read the diff image.** `shots/pp/<id>-diff.png`
is written for every section. The three product grids sit around 26–31% purely because the
design draws twelve copies of one placeholder card ("Eclipse Duo", ₪610, one photo) while
the build shows a real demo catalogue. Their geometry overlaps exactly. Chasing that number
to zero would mean breaking the catalogue.

Trust the **height delta** over the percentage: it is immune to content differences. Every
section currently lands within ±3px of the design.

## Refreshing the Figma renders

`RENDERS` at the top of `pixel-audit.js` maps each section to a Figma MCP asset URL. Those
URLs are short-lived, but the PNGs are cached to `shots/pp/cache/` on first fetch, so
re-runs work offline. To refresh after a design change: delete the cache, call the Figma MCP
`get_screenshot` for each node id, and paste the new URLs in.

Do NOT use the Figma REST render endpoint for *assets* — it bakes a frame's text into the
PNG. That is exactly what you want here (we compare whole rendered sections) and exactly
what you do not want when exporting a photograph.

## The other two scripts

- **`geometry.js`** — measures card/grid boxes in the DOM against the design's numbers.
  Use this when a section scores high and you need to know whether it is *layout* or
  *content*. The pixel score cannot tell you; this can.
- **`bake-crops.js`** — six of the design's photos are not centre-cropped; the designer
  panned or zoomed the fill inside its frame. `object-cover` always centres, so those cards
  showed the wrong part of the picture. This converts Figma's crop window back into source
  pixels and bakes it into the exported asset, so plain `object-cover` reproduces the design
  with no per-image CSS. Re-run it if the assets are ever re-exported.
