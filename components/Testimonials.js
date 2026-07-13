import Media from './Media';
import Stars from './Stars';
import CarouselDots from './CarouselDots';
import { testimonials, sections } from '../lib/data';
import { testimonialBg } from '../lib/images';

// "מדברים עלינו" (109:698). Full-bleed pink gradient photo behind two black
// cards (rgba(0,0,0,0.8), 24px radius, 448px tall): a 267x400 portrait beside
// stars + date + quote + name. Heading is white Futurism 80, centred.
export default function Testimonials() {
  return (
    <section className="relative flex flex-col items-center gap-[60px] overflow-hidden px-gutter py-20">
      <Media src={testimonialBg} alt="" className="absolute inset-0 h-full w-full" />

      <h2 className="relative z-10 font-heading text-[80px] leading-[0.9] text-white">
        {sections.testimonials.lead}
      </h2>

      {/* Reversed: the RTL grid fills right-to-left, which put Edward's card (109:720,
          the design's right-hand card) on the left and Sonia's on the right. */}
      <div className="relative z-10 grid w-full grid-cols-1 gap-4 lg:grid-cols-2">
        {testimonials.slice().reverse().map(function (t) {
          return (
            <figure
              key={t.id}
              className="flex min-h-[448px] items-center gap-4 rounded-3xl bg-black/80 p-6"
            >
              {/* Copy first so RTL puts it right and the portrait left, as in the design. */}
              <div className="flex flex-1 flex-col items-start gap-4 p-4">
                <div className="flex flex-col items-start gap-3">
                  <Stars size={16} gap="gap-1.5" />

                  <span className="text-xs leading-[1.4] text-muted">{t.date}</span>

                  <blockquote className="text-right text-sm leading-[1.4] text-white">
                    {t.quote}
                  </blockquote>
                </div>

                <figcaption className="text-sm leading-[1.4] text-muted">{t.name}</figcaption>
              </div>

              {/* Square — the portrait carries no radius in the design (109:703). */}
              <Media
                src={t.portrait}
                alt=""
                className="h-[400px] w-[267px] shrink-0"
              />
            </figure>
          );
        })}
      </div>

      <div className="relative z-10">
        <CarouselDots count={4} active={0} />
      </div>
    </section>
  );
}
