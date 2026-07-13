import { brandStory } from '../lib/data';

// Section 109:744 (1440x650, pad 140/60). Centred two-line headline at 90px —
// "40 שנים בחושך" white, "עכשיו הדלקנו את האורות." in accent red — then a single
// 573px paragraph. No wordmark, no CTA.
//
// The background is where the red comes from, and it is TWO layers in the design:
//
//  1. A radial gradient on the section itself, anchored at the BOTTOM CENTRE:
//       radialGradient, gradientTransform matrix(0 -72.672 -161 0 720 650)
//       -> an ellipse centred (720, 650) with radii 1610 x 727
//       -> rgba(255,59,59,0.14) at the centre, transparent by 60%
//     This was missing entirely, which is why the section read as flat black.
//
//  2. A soft red rectangle behind the copy (109:745):
//       500x144 at (470, 317.64), rgba(255,59,59,0.1), blur 80px
export default function BrandStory() {
  return (
    <section
      className="relative flex min-h-[650px] flex-col items-center justify-center overflow-hidden px-gutter py-[140px]"
      style={{
        backgroundImage:
          'radial-gradient(1610px 727px at 50% 100%, rgba(255,59,59,0.14) 0%, rgba(255,59,59,0) 60%)',
        backgroundColor: '#0C0C0C',
      }}
    >
      {/* 109:745 — the blurred red block sitting behind the headline. */}
      <div
        className="pointer-events-none absolute h-[144px] w-[500px] bg-accent/10 blur-[80px]"
        style={{ left: '32.6%', top: '48.9%' }}
        aria-hidden="true"
      />

      <div className="relative z-10 flex flex-col items-center gap-8 text-center">
        <h2 className="font-heading text-[90px] leading-[80px] tracking-[-2.2px]">
          <span className="block font-light text-white">{brandStory.lead}</span>
          <span className="block text-accent">{brandStory.accent}</span>
        </h2>

        <p className="w-[573px] max-w-full text-base leading-[1.4] text-white/55">
          {brandStory.body}
        </p>
      </div>
    </section>
  );
}
