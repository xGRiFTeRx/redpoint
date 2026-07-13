// Six of the photos are not centre-cropped in Figma — the designer panned/zoomed the
// fill inside its frame. object-cover always centres, so those cards showed the wrong
// part of the picture no matter how correct the geometry was.
//
// Figma expresses the crop as the <img> box relative to its frame, e.g.
//   109:658  w-full  h-[210.96%]  top-[-26.4%]     (frame 429x305)
// which means: scale the source to 429 wide (=> 643.4 tall), then show the 305px window
// starting 80.5px down. Converting that window back into SOURCE pixels and baking it
// into the exported asset means plain object-cover reproduces the design exactly, with
// no per-image CSS to maintain.
//
//   scale s   = renderedWidth / sourceWidth
//   window    = (offset / s) .. ((offset + frameSize) / s)
const sharp = require('sharp');

const A = 'https://www.figma.com/api/mcp/asset/';
const DEST = 'C:/Users/Admin/Downloads/Redpoint/public/images/';

// name, asset, source WxH, crop box in SOURCE px (left, top, width, height), out width
const CROPS = [
  // 109:377 bestsellers card 1 — frame 318x362, zoomed in ~17% and panned up
  ['product-1', 'eb513dc3-6173-485d-889b-4599d41c4eb1', { left: 1037, top: 160, width: 2025, height: 2305 }, 900],
  // 109:531 worth row 1 card 3 — frame 429.33x362, zoomed and panned right
  ['product-7', '82885bec-093b-4ae0-85f4-26da79d60763', { left: 977, top: 198, width: 2769, height: 2335 }, 900],
  // 109:578 worth row 2 card 1 — frame 429.33x362, panned well DOWN the portrait
  ['product-8', '61479c92-89fa-496e-9800-bf3a024cfd6b', { left: 0, top: 1385, width: 2734, height: 2305 }, 900],
  // 109:625 worth row 2 card 3 — frame 429.33x362, panned left
  ['product-10', 'c9679979-4328-40c7-8862-75b99bc1e5a5', { left: 311, top: 0, width: 3237, height: 2730 }, 900],
  // 109:658 blog card 1 — frame 429x305, panned up the portrait
  ['blog-1', 'adb8d749-294a-4bf6-97d3-7d03fba2fb80', { left: 0, top: 512, width: 2731, height: 1942 }, 900],
  // 109:703 testimonial portrait (Sonia) — frame 266.667x400, panned right
  ['portrait-1', '94ac2b55-1528-42cc-96a6-ebb3eead0947', { left: 1645, top: 0, width: 1821, height: 2731 }, 700],
];

(async () => {
  for (const [name, id, box, outW] of CROPS) {
    const r = await fetch(A + id);
    if (!r.ok) { console.log('FAIL ' + name + ' ' + r.status); continue; }
    const buf = Buffer.from(await r.arrayBuffer());
    const m = await sharp(buf).metadata();

    // Clamp, so a rounding error can't push the box past the edge.
    const left = Math.max(0, Math.min(box.left, m.width - 1));
    const top = Math.max(0, Math.min(box.top, m.height - 1));
    const width = Math.min(box.width, m.width - left);
    const height = Math.min(box.height, m.height - top);

    await sharp(buf)
      .extract({ left, top, width, height })
      .resize({ width: outW, withoutEnlargement: true })
      .webp({ quality: 88 })
      .toFile(DEST + name + '.webp');

    console.log(
      name.padEnd(11) + ' src ' + (m.width + 'x' + m.height).padEnd(10) +
      ' crop ' + (width + 'x' + height + ' @' + left + ',' + top).padEnd(24) +
      ' aspect ' + (width / height).toFixed(3)
    );
  }
})();
