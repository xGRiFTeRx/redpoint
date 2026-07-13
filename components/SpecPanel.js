'use client';

import { useState } from 'react';
import Media from './Media';
import { productDetail } from '../lib/data';

// Spec / FAQ block (109:1445). Two dark collapsible headers — "שאלות ותשובות" and
// "מפרט ומשלוח", both Futurism 24 with the trailing word in accent red. Opening one
// reveals a LIGHT textured panel (109:1454):
//   photo 652x450, 16px radius, beside five white rows
//   row: 50px, rgba(250,250,250,0.6), 1px #E1E1E1, 8px radius, 16px semibold black
//   open row reveals body copy in #2D2D2D
//
// The design only fills in the "מפרט ומשלוח" panel; the client asked for the FAQ header
// to open the same component, so both drive one shared panel with their own rows.
function Caret({ open, className }) {
  return (
    <svg
      viewBox="0 0 22 22"
      className={'shrink-0 transition-transform ' + (open ? 'rotate-180' : '') + ' ' + className}
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
      aria-hidden="true"
    >
      <path d="M5 8l6 6 6-6" />
    </svg>
  );
}

// Caret FIRST so RTL paints it rightmost, with the label to its left. That is the
// design's handedness — and it's what keeps the two carets in a straight column:
// both header groups hug the right gutter, so the caret lands at the same x on each
// row regardless of how long the label is. Putting the caret after the label instead
// pins it to the end of the text, and the two rows go ragged.
function DarkHeader({ lead, accent, open, onClick }) {
  return (
    <button
      onClick={onClick}
      aria-expanded={open}
      className="flex w-full items-center justify-start gap-4 bg-bg px-gutter pb-6 pt-4 transition-opacity hover:opacity-80"
    >
      <Caret open={open} className="h-[22px] w-[22px] text-white" />
      <span className="font-heading text-2xl leading-[0.9] text-white">
        <span className="font-light">{lead}</span>
        <span className="text-accent">{' ' + accent}</span>
      </span>
    </button>
  );
}

function Row({ title, body, latin, open, onClick }) {
  return (
    <div className="flex w-full flex-col gap-4">
      {/* Latin rows read LTR (title left, caret right); Hebrew Q&As read RTL. */}
      <button
        dir={latin ? 'ltr' : 'rtl'}
        onClick={onClick}
        aria-expanded={open}
        className="flex min-h-[50px] w-full items-center justify-between gap-4 rounded-lg border border-[#E1E1E1] bg-[rgba(250,250,250,0.6)] px-6 py-2.5 text-right transition-colors hover:bg-[rgba(250,250,250,0.85)]"
      >
        <span className="text-base font-semibold leading-snug text-black">{title}</span>
        <Caret open={open} className="h-[18px] w-[18px] text-accent" />
      </button>

      {open ? (
        <p
          dir={latin ? 'ltr' : 'rtl'}
          className="w-full px-2 text-right text-base leading-[1.5] text-[#2D2D2D]"
        >
          {body}
        </p>
      ) : null}
    </div>
  );
}

const SECTIONS = {
  faq: { meta: productDetail.faq, rows: productDetail.faqRows, latin: false },
  spec: { meta: productDetail.spec, rows: productDetail.specRows, latin: true },
};

export default function SpecPanel({ backdrop, photo }) {
  const [openSection, setOpenSection] = useState('spec');
  const [openRow, setOpenRow] = useState(0);

  function toggle(id) {
    if (openSection === id) {
      setOpenSection(null);
      return;
    }
    setOpenSection(id);
    setOpenRow(0);
  }

  const current = openSection ? SECTIONS[openSection] : null;

  // mx-auto max-w-shell is load-bearing: without it this section runs to the true window
  // edge on viewports wider than 1440, while every other section stays inside the 1440
  // shell — so the tab headers sat further right than the "תיאור המוצר" heading above.
  return (
    <section className="mx-auto flex w-full max-w-shell flex-col">
      <DarkHeader
        lead={productDetail.faq.lead}
        accent={productDetail.faq.accent}
        open={openSection === 'faq'}
        onClick={function () { toggle('faq'); }}
      />

      <DarkHeader
        lead={productDetail.spec.lead}
        accent={productDetail.spec.accent}
        open={openSection === 'spec'}
        onClick={function () { toggle('spec'); }}
      />

      {current ? (
        <div className="relative flex w-full flex-col items-end overflow-hidden px-gutter py-[50px]">
          <Media src={backdrop} alt="" className="absolute inset-0 h-full w-full" />

          {/* Rows first so RTL paints them right and the photo left, as in the design. */}
          <div className="relative z-10 flex w-full max-w-[1320px] flex-col items-start gap-4 md:flex-row">
            <div className="flex min-w-0 flex-1 flex-col gap-4">
              {current.rows.map(function (row, i) {
                return (
                  <Row
                    key={row.id}
                    title={row.title}
                    body={row.body}
                    latin={current.latin}
                    open={openRow === i}
                    onClick={function () { setOpenRow(openRow === i ? null : i); }}
                  />
                );
              })}
            </div>

            <Media src={photo} alt="" className="h-[450px] w-full shrink-0 rounded-2xl md:w-[652px]" />
          </div>
        </div>
      ) : null}
    </section>
  );
}
