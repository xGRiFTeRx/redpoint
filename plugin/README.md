# RED POINT plugin

Custom Elementor widgets for the RED POINT store. **One widget per section of the Figma
design** — so a broken section is an isolated fix, not a rebuild.

```
plugin/
  redpoint-widgets/            <- the plugin source. THIS is the folder to work in.
    redpoint-widgets.php         constants, panel category, registration, enqueues
    widgets/                     one Widget_Base class per section
    assets/css/                  one stylesheet per widget, plus a shared redpoint.css
    assets/icons/                the design's own SVGs
  dist/                        <- built zips, ready to upload to staging
  build-plugin.sh
```

## Build a zip for staging

```bash
bash plugin/build-plugin.sh
```

Produces `plugin/dist/redpoint-widgets-<VERSION>.zip`, with `redpoint-widgets/` at the
archive root — what **Plugins → Add New → Upload Plugin** expects.

The version comes from the `Version:` header in `redpoint-widgets.php`. Bump it there and
nowhere else.

## Adding a widget

1. `widgets/class-<name>-widget.php` — `namespace RedPoint\Widgets;`, extend
   `\Elementor\Widget_Base`, `get_categories()` returns `['redpoint']`.
2. `assets/css/redpoint-<name>.css` — BEM, prefixed `.rp-<name>__…`.
3. Add a `require_once` + `$widgets_manager->register()` line, and a `wp_enqueue_style`
   line, in `redpoint-widgets.php`.

**Design values go in as control `default`s, not hardcoded CSS.** The point of a widget is
that the client can edit the copy, colours and type in the Elementor UI. Bind style
controls with `'selectors' => [ '{{WRAPPER}} .rp-x__y' => 'color: {{VALUE}};' ]`.

## Things this design will bite you with

**Every card row comes out mirrored.** The page is RTL, so a flex row fills right-to-left
and the first item paints on the RIGHT — but Figma's canvas is LTR and its rows read
left-to-right. Force the ROW to `direction: ltr` (and each item back to `rtl` for its
text), so the client's repeater order matches what they see. Reversing the array looks
right but leaves them editing a list that runs backwards. *This was the single most
recurring bug on the Next.js build of this design — check the handedness of every row
before calling a section done.*

**Elementor's icon control cannot render these icons.** The design's SVGs are stroke paths
on a `fill="none"` root. Elementor forces `fill: <color>` onto the svg *and every path*,
which fills the outline in and turns each icon into a solid blob. Use `redpoint_icon()` to
inline the SVG from `assets/icons/` and colour it through `currentColor`.

**An inline SVG carries a descender gap.** It sits on the text baseline, so a 20px icon in
a 44px pill renders 50px tall. `display: flex` on the wrapper.

## Verifying a section

Every section is checked against the Figma frame it reproduces, with the same harness that
validated the Next.js build (`tools/pixel-audit/`). ~2–5% is the text-antialiasing noise
floor; trust the **height delta**, which is immune to content differences.

| widget | Figma node | design | renders | diff |
|---|---|---|---|---|
| Trust Strip | 109:257 | 1440×154 | 1440×154 | 0.4% |
