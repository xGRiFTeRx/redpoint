import Media from './Media';
import { productDetail } from '../lib/data';

// Feature panels (109:1576). Each row (109:1578):
//   card   1320x350, bg #252525, 16px radius, 60px gap, items-center
//   photo  652x350 — outer corners 16px, bottom-inner 6px, top-inner square
//   copy   550px — Futurism 40 title + 16/1.4 subline at white/80, both right-aligned
//
// The photo alternates sides: rows 1 and 3 have it on the LEFT, rows 2 and 4 on the
// right. Under RTL the first DOM child paints right, so an image-left row puts the
// copy first and the photo second.
export default function FeatureRows() {
  return (
    <section className="mx-auto flex max-w-shell flex-col gap-20 px-gutter py-[90px]">
      {productDetail.features.map(function (f) {
        // Corner radii mirror with the photo's side, but NOT symmetrically. A photo on the
        // left (109:1579, 109:1591) is 16px on its outer corners with a 6px nick on the
        // bottom-inner; a photo on the right (109:1589, 109:1601) is 16px on its outer
        // corners and square on BOTH inner ones — no 6px. The 6px was being mirrored onto
        // the right-hand rows, which the design does not do.
        const photoRadius = f.imageLeft
          ? 'rounded-l-2xl rounded-br-[6px] rounded-tr-none'
          : 'rounded-r-2xl rounded-bl-none rounded-tl-none';

        // 652 photo + 60 gap + 550 copy = 1262 of the 1320 card, so the copy is inset
        // 58px from the card's outer edge — without it the text runs to the very edge.
        const copyInset = f.imageLeft ? 'md:mr-[58px]' : 'md:ml-[58px]';

        const copy = (
          <div
            className={
              'flex w-full flex-col gap-3 px-6 text-right md:w-[550px] md:px-0 ' + copyInset
            }
          >
            <h3 className="font-heading text-[40px] leading-none text-white">{f.title}</h3>
            <p className="text-base leading-[1.4] text-white/80">{f.line}</p>
          </div>
        );

        const photo = (
          <Media
            src={f.image}
            alt=""
            className={'h-[350px] w-full shrink-0 md:w-[652px] ' + photoRadius}
          />
        );

        return (
          <div
            key={f.id}
            className="flex min-h-[350px] flex-col items-center gap-[60px] overflow-hidden rounded-2xl bg-surface md:flex-row"
          >
            {f.imageLeft ? copy : photo}
            {f.imageLeft ? photo : copy}
          </div>
        );
      })}
    </section>
  );
}
