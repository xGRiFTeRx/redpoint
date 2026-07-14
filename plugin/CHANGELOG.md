# Changelog — redpoint-widgets

Every widget that lands bumps the **minor** version and ships a new zip. Fixes to an
existing widget bump the **patch**. `dist/` keeps every build, so staging can be rolled
back to any of them.

Bump `Version:` in `redpoint-widgets.php` — `build-plugin.sh` reads it from there and
refuses to overwrite a zip that already exists, so a forgotten bump fails loudly instead of
silently shipping the wrong contents.

---

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
