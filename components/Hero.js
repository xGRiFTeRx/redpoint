import Media from './Media';
import CategoryStrip from './CategoryStrip';

// Hero (109:212). Figma:
//   flex column, justify-content: space-between, align-items: flex-start,
//   padding 0, gap 565, 1440x800
//   background: linear-gradient(90.16deg, rgba(0,0,0,0.2) 0.14%,
//               rgba(102,102,102,0.2) 107.68%), url(6037377_Woman_Sexy_1280x720)
//
// That fill is a VIDEO in Figma, not a still. Drop the source clip at
// public/video/hero.mp4 and it plays; until then the exported poster frame
// (hero.webp) stands in. Node "s" adds a 280px fade to #0C0C0C at the foot.
//
// Three shapes:
//   Home     1440x800 — media, title 100/80, italic strapline, pill strip
//   Category 1440x800 — media, category name + strapline, pill strip
//   Product  1440x220 — no media, no title, pill strip only
export default function Hero({
  image,
  video,
  title,
  subtitle,
  italicSubtitle,
  activeCategory,
  compact,
}) {
  const height = compact ? 'min-h-[220px]' : 'h-[800px]';

  return (
    <section className={'relative flex flex-col items-start justify-between ' + height}>
      {!compact ? (
        <>
          {video ? (
            <video
              className="absolute inset-0 h-full w-full object-cover"
              poster={image}
              autoPlay
              muted
              loop
              playsInline
              aria-hidden="true"
            >
              <source src={video} type="video/mp4" />
            </video>
          ) : (
            <Media src={image} alt="" className="absolute inset-0 h-full w-full" />
          )}

          {/* Hero's own background gradient, straight from the design. */}
          <div
            className="pointer-events-none absolute inset-0"
            style={{
              backgroundImage:
                'linear-gradient(90.16deg, rgba(0,0,0,0.2) 0.14%, rgba(102,102,102,0.2) 107.68%)',
            }}
            aria-hidden="true"
          />

          {/* Node "s": 280px fade to the page background at the foot of the hero. */}
          <div
            className="pointer-events-none absolute inset-x-0 bottom-0 h-[280px]"
            style={{ backgroundImage: 'linear-gradient(180deg, rgba(12,12,12,0) 0%, #0C0C0C 88%)' }}
            aria-hidden="true"
          />

          {/* Not in the design — Figma's single hero photo is dark, so the white nav
              reads fine over it. Category heroes vary, and the nav vanished on the
              lighter ones. This scrim is invisible on dark images and rescues light ones. */}
          <div
            className="pointer-events-none absolute inset-x-0 top-0 h-[180px]"
            style={{ backgroundImage: 'linear-gradient(180deg, rgba(12,12,12,0.55) 0%, rgba(12,12,12,0) 100%)' }}
            aria-hidden="true"
          />
        </>
      ) : null}

      {/* Nav occupies the top (Header, absolute); the title block centres in the
          remaining space and the pill strip pins to the foot. */}
      <div className="relative z-10 mx-auto flex w-full max-w-shell flex-1 flex-col px-gutter pt-[100px]">
        {title ? (
          <div className="flex flex-1 flex-col justify-center gap-6 py-6 text-right">
            {/* First line Futurism Light, second Regular (109:237). */}
            <h1 className="font-heading text-[100px] leading-[80px] text-white">
              {title.map(function (line, i) {
                return (
                  <span key={i} className={'block ' + (i === 0 ? 'font-light' : '')}>
                    {line}
                  </span>
                );
              })}
            </h1>

            {subtitle ? (
              <p className={'text-xl leading-[1.3] text-body ' + (italicSubtitle ? 'italic' : '')}>
                {subtitle.map(function (line, i) {
                  return (
                    <span key={i} className="block">
                      {line}
                    </span>
                  );
                })}
              </p>
            ) : null}
          </div>
        ) : null}

        <CategoryStrip active={activeCategory} />
      </div>
    </section>
  );
}
