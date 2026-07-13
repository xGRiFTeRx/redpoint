// Geometry probe. For the card-grid sections the pixel score is polluted by the fact
// that Figma repeats ONE placeholder product/post in every card while the build shows
// different demo items — that difference is correct and must not be "fixed". So compare
// the numbers that actually encode the layout instead: box sizes, gaps, paddings.
//
// Expected values on the right are read straight off the Figma node tree.
const { chromium } = require('playwright');

const rect = (el) => {
  const r = el.getBoundingClientRect();
  return { x: Math.round(r.x), y: Math.round(r.y), w: Math.round(r.width), h: Math.round(r.height) };
};

(async () => {
  const b = await chromium.launch({ channel: 'chrome' });
  const p = await b.newPage({ viewport: { width: 1440, height: 1000 } });
  await p.goto((process.argv[2] || 'http://127.0.0.1:3440') + '/', { waitUntil: 'load' });
  await p.evaluate(async () => {
    for (let y = 0; y < document.body.scrollHeight; y += 500) {
      window.scrollTo(0, y); await new Promise((r) => setTimeout(r, 60));
    }
    window.scrollTo(0, 0);
  });
  await p.waitForTimeout(1200);

  const data = await p.evaluate(() => {
    const R = (el) => { const r = el.getBoundingClientRect(); return { w: Math.round(r.width), h: Math.round(r.height), x: Math.round(r.x), y: Math.round(r.y) }; };
    const secs = [...document.querySelectorAll('main section, body > section')];
    const out = {};

    // bestsellers = index 4, worth = 5, blog = 6
    for (const [name, i] of [['bestsellers', 4], ['worth', 5], ['blog', 6]]) {
      const s = secs[i];
      if (!s) { out[name] = 'missing'; continue; }
      const sr = R(s);
      const cs = getComputedStyle(s);
      // A "card" = the repeated grid child. Grab the grid, then its children.
      const grids = [...s.querySelectorAll(':scope > div, :scope > div > div')]
        .filter((d) => d.children.length >= 3 && getComputedStyle(d).display.includes('grid'));
      const g = grids[0];
      const cards = g ? [...g.children].map(R) : [];
      out[name] = {
        section: sr.w + 'x' + sr.h,
        padTop: cs.paddingTop, padBottom: cs.paddingBottom,
        gridGap: g ? getComputedStyle(g).gap : '-',
        cardCount: cards.length,
        card0: cards[0] ? cards[0].w + 'x' + cards[0].h : '-',
        cards: cards.map((c) => c.w + 'x' + c.h).join('  '),
      };
      // Inside card 0: the image block and the info block.
      if (g && g.children[0]) {
        const c0 = g.children[0];
        const img = c0.querySelector('img');
        const parts = [...c0.children].map(R);
        out[name].card0Parts = parts.map((q) => q.w + 'x' + q.h).join(' + ');
        out[name].imgBox = img ? R(img.closest('div') || img).w + 'x' + R(img.closest('div') || img).h : '-';
      }
    }
    return out;
  });

  const EXPECT = {
    bestsellers: { section: '1440x838', card: '318x563', parts: '318x362 + 318x201', gap: '16px', cards: 4 },
    worth:       { section: '1440x1662', card: '429x543', parts: '429x362 + 429x181', gap: '16px', cards: 3 },
    blog:        { section: '1440x877', card: '429x534', parts: '429x305 + 429x229', gap: '16px', cards: 3 },
  };

  for (const k of Object.keys(EXPECT)) {
    const m = data[k];
    const e = EXPECT[k];
    console.log('\n' + k.toUpperCase());
    if (typeof m === 'string') { console.log('  ' + m); continue; }
    const cmp = (label, mine, want) =>
      console.log('  ' + label.padEnd(12) + String(mine).padEnd(24) + 'design ' + String(want).padEnd(22) +
        (String(mine) === String(want) ? 'ok' : '<-- OFF'));
    cmp('section', m.section, e.section);
    cmp('cards', m.cardCount, e.cards);
    cmp('card size', m.card0, e.card);
    cmp('img + info', m.card0Parts, e.parts);
    cmp('grid gap', m.gridGap, e.gap);
    console.log('  padding     top ' + m.padTop + ' / bottom ' + m.padBottom + '   design 90px / (see node)');
  }

  await b.close();
})();
