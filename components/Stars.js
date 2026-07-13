// 5-star rating row. 11px on product cards, 16px on testimonial cards.
// The design's star is a filled #FFD13B glyph.
export default function Stars({ size = 11, gap = 'gap-1' }) {
  const stars = [0, 1, 2, 3, 4];

  return (
    <div className={'flex items-center ' + gap} aria-label="5 מתוך 5 כוכבים">
      {stars.map(function (i) {
        return (
          <svg
            key={i}
            viewBox="0 0 11 11"
            width={size}
            height={size}
            className="shrink-0 text-star"
            fill="currentColor"
            aria-hidden="true"
          >
            <path d="M5.5 0.75 6.97 3.73 10.26 4.21 7.88 6.53 8.44 9.81 5.5 8.26 2.56 9.81 3.12 6.53 0.74 4.21 4.03 3.73Z" />
          </svg>
        );
      })}
    </div>
  );
}
