'use client';

// Carousel pagination dots (e.g. 109:473) — ~13px, ~16px apart, centred beneath
// the row. Clickable when an onSelect handler is supplied.
export default function CarouselDots({ count = 4, active = 0, onSelect }) {
  const dots = Array.from({ length: count }, function (_, i) { return i; });

  return (
    <div className="flex items-center justify-center gap-4">
      {dots.map(function (i) {
        const classes =
          'h-[13px] w-[13px] rounded-full transition-colors ' +
          (i === active ? 'bg-accent' : 'bg-white/20 hover:bg-white/40');

        if (!onSelect) {
          return <span key={i} className={classes} aria-hidden="true" />;
        }

        return (
          <button
            key={i}
            onClick={function () { onSelect(i); }}
            aria-label={'עמוד ' + (i + 1)}
            aria-current={i === active}
            className={classes}
          />
        );
      })}
    </div>
  );
}
