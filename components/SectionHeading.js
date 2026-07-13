// Section heading (e.g. 109:373). Futurism 80px, line-height 0.9, with the
// trailing words in accent red — "הנמכרים" white + "ביותר" red.
// An optional kicker paragraph sits on the far side of the row.
export default function SectionHeading({ lead, accent, kicker, kickerWidth = 211, centered, size }) {
  const scale = size === 'small' ? 'text-2xl' : 'text-[80px]';

  const title = (
    <h2 className={'font-heading leading-[0.9] text-right ' + scale}>
      <span className="font-light text-white">{lead}</span>
      {accent ? <span className="text-accent">{' ' + accent}</span> : null}
    </h2>
  );

  if (centered) {
    return <div className="flex justify-center text-center">{title}</div>;
  }

  // Title first so RTL places it on the right, with the kicker opposite.
  return (
    <div className="flex items-end justify-between gap-16">
      {title}

      {kicker ? (
        // 211px box, Google Sans 20/1.3 (109:313). Each entry in `kicker` is one line
        // of the design's copy and must stay one line: Chrome measures the longest of
        // them at 211.2px against Figma's 211px box, so with normal wrapping it breaks
        // to three lines instead of two. The 0.2px is a text-metric difference between
        // the two renderers, not a layout bug — nowrap holds the intended break points.
        <p
          className="text-body text-xl leading-[1.3] shrink-0"
          style={{ width: kickerWidth + 'px' }}
        >
          {kicker.map(function (line, i) {
            return (
              <span key={i} className="block whitespace-nowrap">
                {line}
              </span>
            );
          })}
        </p>
      ) : (
        <span />
      )}
    </div>
  );
}
