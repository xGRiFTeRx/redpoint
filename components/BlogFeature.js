import Media from './Media';
import { blogFeature } from '../lib/data';

// Wide single blog card used on the Category and Product pages (rather than the
// three-up teaser on the home page). Section 109:1272: 1440x520, 60px padding, inner
// 1320x400 split 720 photo / 600 copy — NOT 50/50, which is what it was. Under RTL the
// first grid column is the rightmost, so photo (720) first puts it on the right and the
// copy panel (600) on the left, matching 109:1274/109:1284.
//
// The copy panel's own padding is 16px (109:1275 sits at x=16 in a 600px panel and runs
// 568 wide), with the block centred vertically — it was 48px, which squeezed the copy.
export default function BlogFeature() {
  return (
    <section className="mx-auto max-w-shell px-gutter py-[60px]">
      <div className="grid grid-cols-1 overflow-hidden rounded-3xl md:grid-cols-[720px_600px]">
        <Media src={blogFeature.image} alt="" className="h-[400px] w-full" />

        <div className="flex h-[400px] flex-col items-start justify-center gap-4 bg-surface p-4 text-right">
          <span className="text-xs leading-[1.4] text-muted">{blogFeature.date}</span>

          <h3 className="text-[40px] font-medium leading-[52px] text-white">{blogFeature.title}</h3>

          <p className="text-sm leading-[1.4] text-[#C5C5C5]">{blogFeature.excerpt}</p>

          <span className="mt-2 flex items-center gap-1.5 text-sm font-medium text-accent">
            {blogFeature.cta}
            <svg viewBox="0 0 14 14" className="h-3.5 w-3.5" fill="none" stroke="currentColor" strokeWidth="1.17" strokeLinecap="round" aria-hidden="true">
              <path d="M11.08 7H2.92M6.42 3.5 2.92 7l3.5 3.5" />
            </svg>
          </span>
        </div>
      </div>
    </section>
  );
}
