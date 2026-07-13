import Link from 'next/link';
import Media from './Media';
import SectionHeading from './SectionHeading';
import { pleasureCards, sections } from '../lib/data';

// "למצוא את ההנאה שלך" (109:311). Two equal columns, 16px gap:
// one 600px-tall card and a 2x2 of 292px cards. In the design the tall card sits
// on the LEFT, so under dir=rtl it must come *after* the 2x2 in DOM order.
//
// Each card: photo, a 162px scrim (backdrop-blur 3px, fading to #0c0c0c),
// title Futurism 30, desc white/55, and a "קנו עכשיו" CTA in the card's colour.
const CTA_LABEL = 'קנו עכשיו';

function Card({ card, tall }) {
  return (
    <Link
      href={card.href}
      className={
        'group relative flex flex-col justify-end overflow-hidden ' +
        (tall ? 'h-[600px]' : 'h-[292px]')
      }
    >
      <Media src={card.image} alt="" className="absolute inset-0 h-full w-full" />

      {/* Square corners — the cards carry NO border-radius in the design (109:316,
          109:328 et al). They had a 4px radius here, which is also why the
          backdrop-blur had to go: blur forces a compositing layer that Chrome won't
          antialias against a rounded parent, leaving a hairline on the corners. With
          the radius gone there is nothing for it to fight, so the design's 3px blur
          is back. */}
      <div
        className="absolute inset-x-0 bottom-0 h-[162px] backdrop-blur-[3px]"
        style={{ backgroundImage: 'linear-gradient(180deg, rgba(0,0,0,0) 4.6%, #0C0C0C 87.5%)' }}
        aria-hidden="true"
      />

      <div className="relative z-10 flex flex-col items-start gap-6 px-4 py-6">
        <div className="flex w-full flex-col gap-3 text-right">
          <h3 className="font-heading text-[30px] leading-[30px] text-white">{card.title}</h3>
          <p className="text-xs leading-none text-white/55">{card.desc}</p>
        </div>

        <span className={'flex items-center gap-1.5 text-sm font-medium ' + card.color}>
          {CTA_LABEL}
          <svg viewBox="0 0 14 14" className="h-3.5 w-3.5" fill="none" stroke="currentColor" strokeWidth="1.17" strokeLinecap="round" aria-hidden="true">
            <path d="M11.08 7H2.92M6.42 3.5 2.92 7l3.5 3.5" />
          </svg>
        </span>
      </div>
    </Link>
  );
}

export default function CategoryGrid() {
  const tall = pleasureCards[0];

  // The 2x2 needs the same RTL flip as everything else, and it bites twice here:
  // a two-column grid under dir=rtl fills each row RIGHT-to-LEFT, so the first card
  // of a row lands on the right. Figma's rows read left-to-right —
  //   row 1: לזוגות (109:328) | הגבירו את העוצמה (109:339)
  //   row 2: גלו עוד (109:350) | לבשו את זה (109:361)
  // so each row has to be reversed in DOM order to paint in that arrangement.
  // Without this the whole grid comes out mirrored: right photo, wrong side.
  const [couples, intensity, explore, wear] = pleasureCards.slice(1);
  const rest = [intensity, couples, wear, explore];

  return (
    <section className="mx-auto flex max-w-shell flex-col gap-[50px] px-gutter py-[60px]">
      <SectionHeading
        lead={sections.pleasure.lead}
        accent={sections.pleasure.accent}
        kicker={sections.pleasure.kicker}
        kickerWidth={sections.pleasure.kickerWidth}
      />

      <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div className="grid grid-cols-2 gap-4">
          {rest.map(function (card) {
            return <Card key={card.id} card={card} />;
          })}
        </div>

        <Card card={tall} tall />
      </div>
    </section>
  );
}
