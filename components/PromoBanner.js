import Media from './Media';
import { promo } from '../lib/data';

// Promo banner (109:295). Section: bg #0C0C0C, padding 50/60.
// Card: 1320x350, 24px radius, 24px padding, photo fill, with a 715px scrim on
// the copy side fading to #0C0C0C (backdrop-blur 3px).
// Copy card: 349px, 24px padding, 32px gap — Futurism 40 title, 16/1.4 body at
// white/80, and a white pill CTA (146x40, #0C0C0C label).
// Carousel chevrons sit at both edges: 32px circles, black/30, white glyph.
function Chevron({ side }) {
  const path = side === 'right' ? 'M12 8l8 8-8 8' : 'M20 8l-8 8 8 8';
  return (
    <span
      className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-black/30"
      aria-hidden="true"
    >
      <svg viewBox="0 0 32 32" className="h-5 w-5" fill="none" stroke="white" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
        <path d={path} />
      </svg>
    </span>
  );
}

// `pad` is the section's vertical padding, and it genuinely differs by page: 50px on the
// home page (109:295, section 450 tall) and 60px on the category page (109:1245, 470).
// Same 1320x350 card either way.
export default function PromoBanner({ image, title, lines, cta, size = 40, pad = 50 }) {
  const heading = title || promo.title;
  const body = lines || promo.lines;
  const action = cta || promo.cta;

  return (
    <section className="mx-auto max-w-shell px-gutter" style={{ paddingTop: pad, paddingBottom: pad }}>
      <div className="relative flex h-[350px] items-center justify-between overflow-hidden rounded-3xl p-6">
        {/* Deliberately NOT rounded. The card already clips with overflow-hidden +
            rounded-3xl; giving the image the same radius nests a second rounded clip
            inside the first, and the two antialiased curves compound into a visible
            light rim on the corner. One clip only. */}
        <Media src={image} alt="" className="absolute inset-0 h-full w-full" />

        {/* Scrim behind the copy. Figma's stops are 15% -> 117%, so at the card's right
            edge the gradient is only ~83% of the way to #0C0C0C — the image still shows
            through and the rounded corners stay visible. Running it to a solid 100%
            black (as I first did) made the right corners vanish into the page. */}
        {/* Figma puts a backdrop-blur(3px) on this scrim. It is deliberately NOT used:
            backdrop-filter promotes the element to its own compositing layer, which
            Chrome does not antialias against the parent's border-radius — leaving a
            visible hairline tracing the card's rounded corners. Verified by toggling it:
            blur on = seam, blur off = clean. A 3px blur over a photograph is invisible;
            the seam is not, so the blur loses. */}
        {/* DEVIATION FROM FIGMA (intentional, client-requested).
            Figma's stops are 15% -> 117%, which lands the card's right edge at ~83%
            black. Against the #0C0C0C page that edge is almost indistinguishable from
            the background, so the rounded corner reads as a faint outlined box — the
            "border" the client kept reporting. Measured: there is no actual rim or seam
            (verified pixel-wise at DPR 1 / 1.25 / 1.5); it is purely the fade going too
            dark. Pushing the far stop to 170% lands the edge at ~57% instead, so the
            banner stays visibly solid to its corner. Restore 117% to match Figma exactly. */}
        <div
          className="pointer-events-none absolute inset-y-0 right-0 w-[715px]"
          style={{
            backgroundImage:
              'linear-gradient(to right, rgba(12,12,12,0) 15%, rgba(12,12,12,1) 170%)',
          }}
          aria-hidden="true"
        />

        {/* Copy group first so RTL paints it on the right, chevron on the far right. */}
        <div className="relative z-10 flex items-center gap-6">
          <Chevron side="right" />

          <div className="flex w-[349px] flex-col gap-8 rounded-[32px] p-6 text-right">
            <div className="flex flex-col gap-3">
              <h3
                className="font-heading leading-none text-white"
                style={{ fontSize: size + 'px' }}
              >
                {heading}
              </h3>

              <p className="text-base leading-[1.4] text-white/80">
                {body.map(function (line, i) {
                  return (
                    <span key={i} className="block">
                      {line}
                    </span>
                  );
                })}
              </p>
            </div>

            {/* justify-start = right edge under RTL, matching the design. */}
            <div className="flex justify-start">
              <button className="flex h-10 w-[146px] items-center justify-center rounded-pill bg-white px-6 text-lg font-semibold text-bg transition-colors hover:bg-body">
                {action}
              </button>
            </div>
          </div>
        </div>

        <div className="relative z-10">
          <Chevron side="left" />
        </div>
      </div>
    </section>
  );
}
