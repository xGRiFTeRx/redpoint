'use client';

import { useMemo, useState } from 'react';
import ProductCard from './ProductCard';
import FilterSidebar from './FilterSidebar';
import NavIcons from './NavIcons';
import Button from './Button';
import { filterGroups } from '../lib/data';

// Category archive body (109:843). Layout per the design:
//   toolbar (109:844)  — FULL-WIDTH row, 1320x52
//   below it           — products column (1024) + filter rail (280), tops aligned
//
// Nesting the toolbar inside the products column is what pushed the grid below the
// filter rail, so it lives above the split.
//
// The filter rail, Sort by and "טען מוצרים נוספים" all drive this one list.
// WooCommerce owns this on the real site; here it runs client-side so the page behaves.
const PAGE_SIZE = 12;

const SORTS = [
  { id: 'popular', label: 'הכי פופולרי' },
  { id: 'price-asc', label: 'מחיר: מהנמוך לגבוה' },
  { id: 'price-desc', label: 'מחיר: מהגבוה לנמוך' },
  { id: 'rating', label: 'דירוג' },
  { id: 'name', label: 'שם המוצר' },
];

// A product must satisfy EVERY group that has something ticked, and within a group any
// one ticked option is enough — standard facet behaviour, same as WooCommerce.
function matches(product, checked) {
  return filterGroups.every(function (group) {
    const picked = group.options.filter(function (opt) {
      const label = typeof opt === 'string' ? opt : opt.label;
      return checked[group.id + '::' + label];
    });

    if (!picked.length) return true;

    return picked.some(function (opt) {
      if (typeof opt !== 'string' && opt.test) return opt.test(product);
      if (typeof opt !== 'string' && opt.value) return product[group.field] === opt.value;
      return product[group.field] === opt;
    });
  });
}

export default function ProductBrowser({ products }) {
  const [checked, setChecked] = useState({});
  const [sort, setSort] = useState('popular');
  const [sortOpen, setSortOpen] = useState(false);
  const [shown, setShown] = useState(PAGE_SIZE);

  const activeCount = Object.values(checked).filter(Boolean).length;

  const visible = useMemo(
    function () {
      let list = products.filter(function (p) { return matches(p, checked); });

      if (sort === 'price-asc') list = list.slice().sort(function (a, b) { return a.price - b.price; });
      if (sort === 'price-desc') list = list.slice().sort(function (a, b) { return b.price - a.price; });
      if (sort === 'rating') list = list.slice().sort(function (a, b) { return b.rating - a.rating; });
      if (sort === 'name') list = list.slice().sort(function (a, b) { return a.name.localeCompare(b.name); });

      return list;
    },
    [products, checked, sort]
  );

  const page = visible.slice(0, shown);
  const sortLabel = SORTS.find(function (s) { return s.id === sort; }).label;

  return (
    <div className="flex flex-col gap-4">
      {/* Toolbar: icons + Sort by sit at the LEFT of the row (so under RTL they come
          last). The design leaves the right of this row empty — the result count and
          clear-filters go there, keeping them out of the products column so the grid
          stays level with the top of the filter rail. */}
      <div className="flex items-center justify-between px-6 py-2">
        <div className="flex items-center gap-3">
          <span className="text-xs text-muted">{visible.length + ' מוצרים'}</span>

          {activeCount ? (
            <button
              onClick={function () { setChecked({}); }}
              className="text-xs text-accent hover:underline"
            >
              {'ניקוי סינון (' + activeCount + ')'}
            </button>
          ) : null}
        </div>

        <div className="flex items-center gap-6">
          <div className="relative">
            {/* Sort by (109:854): a PILL on surface/20 — label then caret. dir=ltr keeps
                the caret to the right of the English label, as the design has it. */}
            <button
              dir="ltr"
              onClick={function () { setSortOpen(!sortOpen); }}
              aria-expanded={sortOpen}
              title={sortLabel}
              className="flex items-center gap-2 rounded-pill bg-surface/20 py-2 pl-3 pr-2 text-base font-medium text-white transition-colors hover:bg-surface/40"
            >
              Sort by
              <svg viewBox="0 0 20 20" className={'h-5 w-5 shrink-0 transition-transform ' + (sortOpen ? 'rotate-180' : '')} fill="none" stroke="currentColor" strokeWidth="1.5" aria-hidden="true">
                <path d="m6 8 4 4 4-4" />
              </svg>
            </button>

            {sortOpen ? (
              <ul className="absolute right-0 top-full z-30 mt-2 w-56 overflow-hidden rounded bg-surface py-1 shadow-lg">
                {SORTS.map(function (s) {
                  return (
                    <li key={s.id}>
                      <button
                        onClick={function () { setSort(s.id); setSortOpen(false); }}
                        className={
                          'block w-full px-4 py-2 text-right text-sm transition-colors hover:bg-white/10 ' +
                          (s.id === sort ? 'text-accent' : 'text-white')
                        }
                      >
                        {s.label}
                      </button>
                    </li>
                  );
                })}
              </ul>
            ) : null}
          </div>

          <NavIcons />
        </div>
      </div>

      {/* Filter rail first so RTL puts it on the right; both columns start level. */}
      <div className="flex flex-col gap-4 md:flex-row md:items-start">
        <FilterSidebar filters={checked} onChange={setChecked} />

        {/* min-w-0 lets this column shrink — a flex child defaults to min-width:auto. */}
        <div className="flex min-w-0 flex-1 flex-col gap-4">
          {page.length ? (
            <div className="grid grid-cols-2 gap-4 lg:grid-cols-4">
              {page.map(function (p, i) {
                return <ProductCard key={p.slug + '-' + i} product={p} compact />;
              })}
            </div>
          ) : (
            <p className="py-16 text-center text-white">לא נמצאו מוצרים בסינון הזה.</p>
          )}

          {/* Always rendered: the design shows this pill unconditionally (109:1163,
              217x40, centred, 32px under the grid — gap-4 here plus pt-4 = 32). It used
              to be conditional on there being more products to load, and since the mock
              catalogue is exactly the 12 cards the design draws, it never appeared and
              the section came up 69px short. A real catalogue always has more to load;
              with this one it simply has nothing left, so it disables. */}
          <div className="flex justify-center pt-4">
            <Button
              variant="solid"
              className="w-[217px] disabled:cursor-default disabled:opacity-40"
              disabled={shown >= visible.length}
              onClick={function () { setShown(shown + PAGE_SIZE); }}
            >
              טען מוצרים נוספים
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
}
