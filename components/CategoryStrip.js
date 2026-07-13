import Link from 'next/link';
import { categories } from '../lib/data';

// Category pills (Frame 2147225523): 130x40, bg rgba(37,37,37,0.6), 1px border in
// the category's own colour. The pill for the current category renders filled.
const FILL = {
  'border-cat-red': 'bg-cat-red border-cat-red',
  'border-cat-green': 'bg-cat-green border-cat-green',
  'border-cat-yellow': 'bg-cat-yellow border-cat-yellow',
  'border-cat-purple': 'bg-cat-purple border-cat-purple',
  'border-cat-orange': 'bg-cat-orange border-cat-orange',
  'border-cat-pink': 'bg-cat-pink border-cat-pink',
  'border-cat-cyan': 'bg-cat-cyan border-cat-cyan',
  'border-cat-magenta': 'bg-cat-magenta border-cat-magenta',
};

export default function CategoryStrip({ active }) {
  return (
    <nav className="w-full py-10">
      {/* justify-start = the RIGHT edge under RTL, matching the design's 60px gutter. */}
      <ul className="flex flex-wrap items-center justify-start gap-4">
        {categories.map(function (cat) {
          const isActive = cat.slug === active;
          const style = isActive ? FILL[cat.border] : 'bg-surface/60 ' + cat.border;

          return (
            <li key={cat.slug}>
              <Link
                href={'/category/' + cat.slug}
                className={
                  'flex min-w-[130px] items-center justify-center whitespace-nowrap rounded-pill border px-4 py-3 ' +
                  'text-base font-medium text-white transition-colors ' +
                  (isActive ? style : style + ' hover:bg-surface')
                }
              >
                {cat.name}
              </Link>
            </li>
          );
        })}
      </ul>
    </nav>
  );
}
