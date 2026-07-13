'use client';

import { useState } from 'react';
import { filterGroups } from '../lib/data';

// Filter panel (109:1165). Figma metrics — the whole panel is 280x784, so the
// spacing is tight and every value below is load-bearing:
//   panel     280 wide, bg #252525, padding 24/16, 16px gap
//   title     "סינון" 24px bold + a RED rule beneath (Line 143)
//   groups    16px apart; a collapsed group is only 28px tall
//   header    16px row — label on the RIGHT, caret on the LEFT
//   options   115px column pinned RIGHT, 20px rows, 12px apart
//   divider   hairline under each group (Line 145)
//
// Only "קטגוריה" is filled in in the design; the other nine groups are collapsed and
// empty there, so their options are sample data (see lib/data.js).

// Figma uses a CaretDown instance: down when collapsed, flipped up when open.
function Caret({ open }) {
  return (
    <svg
      viewBox="0 0 16 16"
      className={'h-4 w-4 shrink-0 text-white transition-transform ' + (open ? 'rotate-180' : '')}
      fill="none"
      stroke="currentColor"
      strokeWidth="1.5"
      aria-hidden="true"
    >
      <path d="m4 6 4 4 4-4" />
    </svg>
  );
}

// The native checkbox paints a solid white box when unchecked, which reads far too
// heavy. The design's "_Tag checkbox" is a hairline square that fills green (#82C47B)
// with a white tick when checked — so the input is hidden and this is drawn instead.
function Checkbox({ checked, onChange }) {
  return (
    <span className="relative inline-flex h-[18px] w-[18px] shrink-0">
      <input
        type="checkbox"
        checked={checked}
        onChange={onChange}
        className="peer absolute inset-0 cursor-pointer appearance-none rounded-[3px] border border-white/40 bg-transparent transition-colors checked:border-[#82C47B] checked:bg-[#82C47B]"
      />
      <svg
        viewBox="0 0 14 14"
        className="pointer-events-none absolute inset-0 m-auto h-[10px] w-[10px] text-white opacity-0 peer-checked:opacity-100"
        fill="none"
        stroke="currentColor"
        strokeWidth="2.4"
        strokeLinecap="round"
        strokeLinejoin="round"
        aria-hidden="true"
      >
        <path d="M2.5 7.5l3 3 6-6" />
      </svg>
    </span>
  );
}

// Options may be plain strings, {label, value} or {label, test} — normalise them.
function optionLabel(opt) {
  return typeof opt === 'string' ? opt : opt.label;
}

// `filters` / `onChange` are lifted so ProductBrowser can actually filter on them.
export default function FilterSidebar({ filters, onChange }) {
  const [open, setOpen] = useState({ category: true });
  const [internal, setInternal] = useState({});

  const checked = filters || internal;
  const setChecked = onChange || setInternal;

  function toggleGroup(id) {
    setOpen(function (prev) {
      const next = Object.assign({}, prev);
      next[id] = !next[id];
      return next;
    });
  }

  function toggleOption(key) {
    const next = Object.assign({}, checked);
    next[key] = !next[key];
    setChecked(next);
  }

  return (
    <aside className="flex w-full shrink-0 flex-col gap-4 self-start rounded bg-surface px-4 py-6 md:w-[280px]">
      <div className="flex flex-col gap-4">
        <h2 className="text-2xl font-bold leading-none text-white">סינון</h2>
        <div className="h-px w-full bg-accent" aria-hidden="true" />
      </div>

      <div className="flex flex-col gap-4">
        {filterGroups.map(function (group) {
          const isOpen = !!open[group.id];

          return (
            <div key={group.id} className="flex flex-col gap-3">
              {/* Label first so RTL paints it right, caret left — as in the design. */}
              <button
                onClick={function () { toggleGroup(group.id); }}
                aria-expanded={isOpen}
                className="flex h-4 w-full items-center justify-between"
              >
                <span className="text-base font-medium leading-none text-white">{group.label}</span>
                <Caret open={isOpen} />
              </button>

              {/* 115px column pinned to the RIGHT edge. self-start, not self-end —
                  under RTL `end` resolves to the left. The checkbox comes first so it
                  lands on the right, with the label right-aligned against it. */}
              {isOpen ? (
                <div className="flex w-[130px] flex-col gap-3 self-start">
                  {group.options.map(function (opt) {
                    const label = optionLabel(opt);
                    const key = group.id + '::' + label;

                    return (
                      <label
                        key={key}
                        className="flex h-5 w-full cursor-pointer items-center gap-2 text-sm leading-5 text-[#DADADA] hover:text-white"
                      >
                        <Checkbox
                          checked={!!checked[key]}
                          onChange={function () { toggleOption(key); }}
                        />
                        <span className="flex-1 text-right">{label}</span>
                      </label>
                    );
                  })}
                </div>
              ) : null}

              <div className="h-px w-full bg-white/10" aria-hidden="true" />
            </div>
          );
        })}
      </div>
    </aside>
  );
}
