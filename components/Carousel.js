'use client';

import { useState, Children } from 'react';
import CarouselDots from './CarouselDots';

// Paginates its children into pages of `perPage` and drives the dots beneath.
// The design shows four dots under each product/blog/testimonial row; this makes
// them real without changing the look.
export default function Carousel({ children, perPage = 4, cols = 'md:grid-cols-4', dots = true }) {
  const all = Children.toArray(children);
  const pages = Math.max(1, Math.ceil(all.length / perPage));
  const [page, setPage] = useState(0);

  const start = page * perPage;

  // Reversed for RTL. A grid under dir=rtl fills right-to-left, so the first child
  // paints on the RIGHT — which mirrors every card row against the design, where the
  // sequence runs left-to-right (109:375 puts card 1 at x=0). Reversing the DOM order
  // of the visible page puts item 1 back on the left without touching the data or the
  // paging maths.
  //
  // Worth flagging for the Elementor build: WooCommerce will order an RTL product loop
  // right-to-left by default, so the live store will look mirrored against the Figma
  // unless the same flip is applied. The design was drawn on an LTR canvas; whether the
  // left-to-right card order is intentional is a question for the designer.
  const visible = all.slice(start, start + perPage).reverse();

  return (
    <div className="flex flex-col gap-[50px]">
      <div className={'grid grid-cols-2 gap-4 ' + cols}>{visible}</div>

      {dots && pages > 1 ? (
        <CarouselDots count={pages} active={page} onSelect={setPage} />
      ) : null}
    </div>
  );
}
