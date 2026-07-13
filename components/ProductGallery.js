'use client';

import { useState } from 'react';
import Media from './Media';

// Gallery (109:1378). Figma:
//   row        flex, gap 16, items-start
//   thumb rail 107px wide, gap 16 — five 107x107 frames, radius 16, opacity 60%
//   main       652x600, radius 16, 24px padding, prev/next chevrons inside
//
// The design draws every thumb at 60% opacity with no selected state (it's a static
// mock). Here the active one goes to full opacity so the gallery reads as interactive.
function Chevron({ side, onClick }) {
  const path = side === 'right' ? 'M12 8l8 8-8 8' : 'M20 8l-8 8 8 8';
  return (
    <button
      onClick={onClick}
      aria-label={side === 'right' ? 'הקודם' : 'הבא'}
      className="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-black/30 transition-colors hover:bg-black/60"
    >
      <svg viewBox="0 0 32 32" className="h-5 w-5" fill="none" stroke="white" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
        <path d={path} />
      </svg>
    </button>
  );
}

// `main` is the design's 652x600 frame (109:1385), which is a different photo from any
// of the five thumbs. active = -1 means "untouched", so the gallery opens on exactly what
// the design draws; the first click on a thumb or chevron hands control to the rail.
export default function ProductGallery({ images = [], main }) {
  const frames = images.length ? images : [null, null, null, null, null];
  const [active, setActive] = useState(-1);

  const step = function (delta) {
    setActive(function (i) {
      return ((i < 0 ? 0 : i) + delta + frames.length) % frames.length;
    });
  };

  const shown = active < 0 ? main || frames[0] : frames[active];

  return (
    <div className="flex items-start gap-4">
      {/* Main frame first so RTL paints it on the right and the rail on the left. */}
      <div className="relative flex h-[600px] flex-1 items-center justify-between overflow-hidden rounded-2xl p-6">
        <Media src={shown} alt="" className="absolute inset-0 h-full w-full rounded-2xl" />

        <Chevron side="right" onClick={function () { step(-1); }} />
        <Chevron side="left" onClick={function () { step(1); }} />
      </div>

      <div className="flex w-[107px] shrink-0 flex-col gap-4">
        {frames.map(function (src, i) {
          const isActive = i === active;
          return (
            <button
              key={i}
              onClick={function () { setActive(i); }}
              aria-label={'תמונה ' + (i + 1)}
              aria-current={isActive}
              className={
                'h-[107px] w-[107px] overflow-hidden rounded-2xl transition-opacity ' +
                (isActive ? 'opacity-100' : 'opacity-60 hover:opacity-90')
              }
            >
              <Media src={src} alt="" className="h-full w-full rounded-2xl" />
            </button>
          );
        })}
      </div>
    </div>
  );
}
