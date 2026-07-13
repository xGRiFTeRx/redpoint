import Link from 'next/link';
import Media from './Media';
import Stars from './Stars';
import AddToCartButton from './AddToCartButton';

// Product card (109:398). 318x563:
//   image 362px, 1px white/10 border, rounded top, badge pill top-LEFT on white/20
//   body  #111, 16px padding, 16px gap, rounded bottom
//         row  : price group (old = red strikethrough, new = white) | name 18px
//         stars: 5 x 11px
//         desc : #818181 14px, leading 1.4, right-aligned
//         button: full width, 40px, 1px white/40, pill, 12px label
// The category grid (109:861) uses a smaller card: 244x438, 279px image,
// 14px name, 12px price, 10px stars, a 32px button labelled "Add to Cart".
const SIZES = {
  full: {
    image: 'h-[362px]',
    pad: 'p-4',
    gap: 'gap-4',
    innerGap: 'gap-3', // 12px between name row / stars / desc (109:379)
    name: 'text-lg',
    price: 'text-base',
    desc: 'text-sm',
    star: 11,
    button: 'h-10 text-xs',
    label: 'הוספה לסל',
    badge: 'text-lg px-3 py-2',
  },
  compact: {
    image: 'h-[279px]',
    pad: 'p-3',
    gap: 'gap-3',
    innerGap: 'gap-2', // 8px, NOT 12 — the compact card tightens this (109:866)
    name: 'text-sm',
    price: 'text-xs',
    desc: 'text-xs',
    star: 10,
    button: 'h-8 text-xs',
    label: 'Add to Cart',
    badge: 'text-sm px-2.5 py-1.5',
  },
};

export default function ProductCard({ product, compact }) {
  const href = '/product/' + product.slug;
  const s = compact ? SIZES.compact : SIZES.full;

  return (
    <div className="flex flex-col">
      <Link
        href={href}
        className={'relative block overflow-hidden rounded-t border border-white/10 ' + s.image}
      >
        <Media src={product.image} alt={product.name} className="absolute inset-0 h-full w-full" />

        {product.badge ? (
          <span
            className={
              'absolute left-[15px] top-[15px] rounded-pill bg-white/20 font-medium leading-none text-white ' +
              s.badge
            }
          >
            {product.badge}
          </span>
        ) : null}
      </Link>

      <div className={'flex flex-col rounded-b bg-panel ' + s.pad + ' ' + s.gap}>
        {/* items-start = right edge under RTL, matching the design's items-end in LTR. */}
        <div className={'flex flex-col items-start ' + s.innerGap}>
          {/* Name first so RTL paints it right, price to its left — as the design shows.
              Within the price group the current price sits right of the struck-through one. */}
          {/* leading-none belongs on the text spans, not this row: Tailwind's text-lg
              carries its own 28px line-height, so a leading utility on the parent loses
              to it. The design sets name and price to line-height 1 (109:382, 109:383),
              making the row exactly as tall as the 18px name. Left as-is, each card ran
              ~9px tall, and multiplied across the grids it pushed the bestsellers and
              worth-attention sections well past their design heights. */}
          <div className="flex w-full items-center justify-between">
            <span className={'font-medium leading-none text-white ' + s.name}>{product.name}</span>

            <div className={'flex items-center gap-3 leading-none ' + s.price}>
              <span className="leading-none text-white">{'₪' + product.price}</span>
              {product.oldPrice ? (
                <span className="leading-none text-accent line-through">{'₪' + product.oldPrice}</span>
              ) : null}
            </div>
          </div>

          <Stars size={s.star} />

          {/* Latin copy: dir=ltr keeps the punctuation at the correct end. */}
          <p dir="ltr" className={'w-full text-right leading-[1.4] text-muted ' + s.desc}>
            {product.desc}
          </p>
        </div>

        <AddToCartButton slug={product.slug} label={s.label} className={'w-full px-8 ' + s.button} />
      </div>
    </div>
  );
}
