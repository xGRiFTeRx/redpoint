// Pixel-perfect audit: Figma frame render vs the live site, section by section.
//
// The Figma reference comes from GET /v1/images, which BAKES text into the PNG.
// That is wrong when you want an image *asset* (it burns headlines into the photo),
// but it is exactly right here: we want Figma's own rendering of the whole section
// to diff against Chrome's rendering of the whole section.
//
// Sections are addressed by INDEX, not by CSS selector — selectors kept silently
// missing and the audit reported "skipped", which reads like a pass.
const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');
const sharp = require('sharp');
const pixelmatch = require('pixelmatch').default || require('pixelmatch');
const { PNG } = require('pngjs');

const BASE = process.argv[2] || 'http://127.0.0.1:3440';
const OUT = 'shots/pp';
const CACHE = 'shots/pp/cache';
const FILE_KEY = 'Kx2t5PDOZvCVbhCeAnqFgl';

// Reference renders come from the Figma MCP connector (get_screenshot), not the REST
// API — the personal access token has expired, and the connector is the sanctioned
// path anyway. These URLs are short-lived; the PNGs are cached to disk on first run,
// so re-runs work offline. To refresh: re-call get_screenshot per node and paste here.
const RENDERS = {
  hero:         'https://www.figma.com/api/mcp/asset/c2c7ff9b-b5f9-44ba-98f9-c1fef8f7270d',
  trust:        'https://www.figma.com/api/mcp/asset/f5ba326d-50cc-49e8-8fe8-507ecb1eee30',
  promo:        'https://www.figma.com/api/mcp/asset/f126cdcf-3082-4537-ba56-963798d1b09f',
  pleasure:     'https://www.figma.com/api/mcp/asset/ab674351-a3dd-4675-ac1a-b768d704969c',
  bestsellers:  'https://www.figma.com/api/mcp/asset/96db0125-9365-4f62-ab16-2199a0e955f1',
  worth:        'https://www.figma.com/api/mcp/asset/e732832e-c749-4d86-9813-433590e5e308',
  blog:         'https://www.figma.com/api/mcp/asset/296b34d8-f7b4-4582-a138-b00b45c96aae',
  testimonials: 'https://www.figma.com/api/mcp/asset/0e3e6c0c-7669-42d6-97b2-5945671625cd',
  brandstory:   'https://www.figma.com/api/mcp/asset/5ebb325d-c895-4317-9a7b-b75b60fe47b8',
  'cat-hero':    'https://www.figma.com/api/mcp/asset/8328a023-af5c-4521-8313-27bcf02fb46a',
  'cat-browser': 'https://www.figma.com/api/mcp/asset/592eb8f9-a73c-40f7-99ca-1e58eacd6861',
  'cat-promo':   'https://www.figma.com/api/mcp/asset/503492aa-ccd0-4c67-a57a-bdbc17d86112',
  'cat-vive':    'https://www.figma.com/api/mcp/asset/3c6538fc-e7a0-4ec7-ab0a-257f0dd744cb',
  'cat-blog':    'https://www.figma.com/api/mcp/asset/6f4db27d-3cf0-4859-847c-6027d2d63ffe',
  'prod-main':     'https://www.figma.com/api/mcp/asset/d1ca7592-fe31-44b6-a913-2cafcd82dcb4',
  'prod-spec':     'https://www.figma.com/api/mcp/asset/8682e679-a41f-43c3-850f-687f762b42b6',
  'prod-upsell':   'https://www.figma.com/api/mcp/asset/f83305c1-344c-4cbc-9429-296db8ad9b10',
  'prod-features': 'https://www.figma.com/api/mcp/asset/fd510c7d-15a1-4502-841e-38ffbaecdaf1',
  'prod-related':  'https://www.figma.com/api/mcp/asset/abcbdbc8-f026-4e9a-8493-d914477d4e2c',
  'prod-vive':     'https://www.figma.com/api/mcp/asset/edd0f6ee-5745-4fab-bad6-bf96967e7769',
  'prod-blog':     'https://www.figma.com/api/mcp/asset/7f82084e-c307-43cd-abe4-f8118b3ba254',
};

// Section order on each page, Figma node <-> live section index. Heights are Figma's,
// kept here so a height delta is immediately readable.
const PAGES = [
  {
    url: '/',
    sections: [
      { id: 'hero',         node: '109:212', i: 0, h: 800 },
      { id: 'trust',        node: '109:257', i: 1, h: 154 },
      { id: 'promo',        node: '109:295', i: 2, h: 450 },
      { id: 'pleasure',     node: '109:311', i: 3, h: 842 },
      { id: 'bestsellers',  node: '109:372', i: 4, h: 838 },
      { id: 'worth',        node: '109:478', i: 5, h: 1662 },
      { id: 'blog',         node: '109:652', i: 6, h: 877 },
      { id: 'testimonials', node: '109:698', i: 7, h: 813 },
      { id: 'brandstory',   node: '109:744', i: 8, h: 650 },
    ],
  },
  {
    // Category page — Figma frame 109:795.
    url: '/category/couples',
    sections: [
      { id: 'cat-hero',    node: '109:797',  i: 0, h: 800 },
      { id: 'cat-browser', node: '109:842',  i: 1, h: 1586 },
      { id: 'cat-promo',   node: '109:1245', i: 2, h: 470 },
      { id: 'cat-vive',    node: '109:1261', i: 3, h: 520 },
      { id: 'cat-blog',    node: '109:1272', i: 4, h: 520 },
    ],
  },
  {
    // Product page — Figma frame 109:1330.
    // `i` may be a [from, to] range: the design's main frame (109:1376) holds the gallery,
    // the buy box AND the description, which are two <section>s in the build. The capture
    // then spans the union of both boxes.
    url: '/product/eclipse-duo',
    sections: [
      { id: 'prod-main',     node: '109:1376', i: [1, 2], h: 876 },
      { id: 'prod-spec',     node: '109:1445', i: 3,      h: 674 },
      { id: 'prod-upsell',   node: '109:1475', i: 4,      h: 843 },
      { id: 'prod-features', node: '109:1576', i: 5,      h: 1820 },
      { id: 'prod-related',  node: '109:1624', i: 8,      h: 906 },
      { id: 'prod-vive',     node: '109:1730', i: 9,      h: 500 },
      { id: 'prod-blog',     node: '109:1741', i: 10,     h: 520 },
      // i=6 promo (109:1602) and i=7 brand story (109:1618) are the same components as the
      // home page, with identical padding, and both measure height-exact — covered there.
    ],
  },
];

async function figmaRender(nodes) {
  fs.mkdirSync(CACHE, { recursive: true });
  for (const n of nodes) {
    const f = CACHE + '/' + n.id + '.png';
    if (fs.existsSync(f)) continue;
    const url = RENDERS[n.id];
    if (!url) { console.log('  no render URL for ' + n.id); continue; }
    const res = await fetch(url);
    if (!res.ok) { console.log('  render URL expired for ' + n.id + ' (' + res.status + ')'); continue; }
    fs.writeFileSync(f, Buffer.from(await res.arrayBuffer()));
    console.log('  fetched ' + n.id);
  }
}

async function shotSections(browser, page) {
  const p = await browser.newPage({ viewport: { width: 1440, height: 1000 } });
  await p.goto(BASE + page.url, { waitUntil: 'load' });

  // Walk the page so every lazy image commits, then wait for all of them to decode.
  await p.evaluate(async () => {
    for (let y = 0; y < document.body.scrollHeight; y += 500) {
      window.scrollTo(0, y);
      await new Promise((r) => setTimeout(r, 80));
    }
    window.scrollTo(0, 0);
  });
  await p
    .waitForFunction(() => [...document.querySelectorAll('img')].every((i) => i.complete && i.naturalWidth > 0), { timeout: 30000 })
    .catch(() => {});
  await p.waitForTimeout(900);

  const els = await p.$$('section');
  const out = {};
  for (const s of page.sections) {
    const f = OUT + '/' + s.id + '-mine.png';

    if (Array.isArray(s.i)) {
      // Range: clip the union of the two sections' boxes (one Figma frame, two <section>s).
      const [a, z] = s.i;
      if (!els[a] || !els[z]) { out[s.id] = null; continue; }
      await els[a].scrollIntoViewIfNeeded();
      await p.waitForTimeout(250);
      const box = await p.evaluate(([a, z]) => {
        const ss = document.querySelectorAll('section');
        const r1 = ss[a].getBoundingClientRect();
        const r2 = ss[z].getBoundingClientRect();
        return {
          x: Math.min(r1.left, r2.left) + window.scrollX,
          y: Math.min(r1.top, r2.top) + window.scrollY,
          width: Math.max(r1.right, r2.right) - Math.min(r1.left, r2.left),
          height: Math.max(r1.bottom, r2.bottom) - Math.min(r1.top, r2.top),
        };
      }, [a, z]);
      await p.screenshot({ path: f, clip: box, fullPage: true });
      out[s.id] = f;
      continue;
    }

    const el = els[s.i];
    if (!el) { out[s.id] = null; continue; }
    await el.scrollIntoViewIfNeeded();
    await p.waitForTimeout(250);
    await el.screenshot({ path: f });
    out[s.id] = f;
  }
  await p.close();
  return out;
}

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const browser = await chromium.launch({ channel: 'chrome' });
  const rows = [];

  for (const page of PAGES) {
    await figmaRender(page.sections);
    const mine = await shotSections(browser, page);

    for (const s of page.sections) {
      const figFile = CACHE + '/' + s.id + '.png';
      const mineFile = mine[s.id];
      if (!fs.existsSync(figFile) || !mineFile) { rows.push([s.id, null, 'MISSING']); continue; }

      const fm = await sharp(figFile).metadata();
      const mm = await sharp(mineFile).metadata();
      const W = 1320; // compare the content column, not the full-bleed frame
      const a = await sharp(figFile).resize({ width: W }).png().toBuffer();
      const b = await sharp(mineFile).resize({ width: W }).png().toBuffer();

      const pa = PNG.sync.read(a);
      const pb = PNG.sync.read(b);
      const H = Math.min(pa.height, pb.height);

      const ca = PNG.sync.read(await sharp(a).extract({ left: 0, top: 0, width: W, height: H }).png().toBuffer());
      const cb = PNG.sync.read(await sharp(b).extract({ left: 0, top: 0, width: W, height: H }).png().toBuffer());

      const diff = new PNG({ width: W, height: H });
      const bad = pixelmatch(ca.data, cb.data, diff.data, W, H, { threshold: 0.22 });
      const pct = (bad / (W * H)) * 100;

      fs.writeFileSync(OUT + '/' + s.id + '-diff.png', PNG.sync.write(diff));
      const dh = mm.height - fm.height;
      rows.push([s.id, pct, (dh === 0 ? 'height exact' : (dh > 0 ? '+' : '') + dh + 'px vs design (' + fm.height + ')')]);
    }
  }

  await browser.close();

  rows.sort((x, y) => (y[1] === null ? 1 : x[1] === null ? -1 : y[1] - x[1]));
  console.log('');
  console.log('PIXEL-PERFECT AUDIT  (Figma render vs live site, normalised to 1320px)');
  console.log('='.repeat(70));
  for (const [id, pct, note] of rows) {
    const score = pct === null ? ' MISSING' : (pct.toFixed(1) + '%').padStart(7);
    const flag = pct === null ? '' : pct > 12 ? '  <-- DRIFT' : pct > 6 ? '  <-- check' : '';
    console.log('  ' + id.padEnd(13) + score + '   ' + note.padEnd(30) + flag);
  }
  console.log('');
  console.log('  Figma and Chrome hint/antialias text differently, so ~2-5% is the noise');
  console.log('  floor for a text-heavy section. >12% is real layout drift.');
})();
