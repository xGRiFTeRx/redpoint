import Stars from './Stars';
import BuyBox from './BuyBox';
import { productDetail } from '../lib/data';

// Product info column (109:1390), 500px, right-aligned, 32px gaps:
//   title    Futurism 60 white
//   tagline  Google Sans 20 white
//   brand    14 / #A8A8B0
//   reviews  5 x 12px stars + "(10 ביקורות)" 12 / #A8A8B0
//   price    old ₪ 20 red strikethrough + new ₪ 28 white
//   blurb    16/1.4 / #818181
//   actions  heart + search chips, then counter + a 135x40 outline "הוספה לסל"
//   pills    #B20143 hairline, gradient text (#B20143 -> #ED579D), uppercase
//   help     #252525 card, 16px radius — heading, subline, and three buttons
function Pill({ children }) {
  return (
    <span className="rounded-full border border-[#B20143] px-[13px] py-[5px]">
      <span className="bg-gradient-to-r from-[#B20143] to-[#ED579D] bg-clip-text text-xs uppercase leading-4 tracking-[0.55px] text-transparent">
        {children}
      </span>
    </span>
  );
}

function IconChip({ src, label }) {
  return (
    <button
      aria-label={label}
      className="flex h-8 w-8 items-center justify-center rounded-full bg-surface/20 p-1.5 transition-colors hover:bg-surface/50"
    >
      {/* eslint-disable-next-line @next/next/no-img-element */}
      <img src={src} alt="" className="h-5 w-5" />
    </button>
  );
}

export default function ProductInfo({ product }) {
  return (
    <div className="flex w-full flex-col items-start gap-8 text-right md:w-[500px]">
      <div className="flex w-full flex-col items-start gap-6">
        <div className="flex flex-col items-start gap-4">
          <h1 className="font-heading text-[60px] leading-none text-white">{product.name}</h1>

          <p className="text-xl leading-none text-white">{productDetail.tagline}</p>

          <span className="text-sm leading-none text-[#A8A8B0]">{productDetail.vendor}</span>

          <div className="flex items-center gap-2">
            <Stars size={12} gap="gap-1" />
            <span className="text-xs leading-none text-[#A8A8B0]">{productDetail.reviewCount}</span>
          </div>
        </div>

        {/* Current price first so RTL paints it right, with the struck-through
            old price to its left — the order the design shows. */}
        <div className="flex items-center gap-3 leading-none">
          <span className="text-[28px] text-white">{'₪ ' + product.price}</span>
          {product.oldPrice ? (
            <span className="text-xl text-accent line-through">{'₪ ' + product.oldPrice}</span>
          ) : null}
        </div>

        <p className="w-full text-base leading-[1.4] text-muted">
          {productDetail.blurb.map(function (line, i) {
            return (
              <span key={i} className="block">
                {line}
              </span>
            );
          })}
        </p>
      </div>

      <div className="flex flex-col items-start gap-4">
        <div className="flex items-center gap-4">
          <IconChip src="/icons/search.svg" label="הגדלה" />
          <IconChip src="/icons/heart.svg" label="הוספה למועדפים" />
        </div>

        <BuyBox slug={product.slug} label={productDetail.addToCart} />
      </div>

      <div className="flex items-center gap-2">
        <Pill>{productDetail.stock}</Pill>
        <Pill>{productDetail.shipping}</Pill>
      </div>

      <div className="flex w-full flex-col items-start gap-[18px] rounded-2xl bg-surface p-4">
        <div className="flex w-[226px] flex-col items-start gap-2">
          <p className="w-full text-base font-medium leading-none text-white">
            {productDetail.help.title}
          </p>
          <p className="w-full text-sm leading-[1.4] text-[#DADADA]">{productDetail.help.subtitle}</p>
        </div>

        <div className="flex w-full items-center justify-start gap-6">
          <div className="flex items-center gap-2">
            <button className="h-8 w-[110px] rounded-pill border border-white/40 text-xs font-medium text-[#DADADA]">
              {productDetail.help.name}
            </button>
            <button className="h-8 w-[180px] rounded-pill border border-white/40 text-xs font-medium text-[#DADADA]">
              {productDetail.help.phone}
            </button>
          </div>

          <button className="h-8 w-[146px] rounded-pill bg-white text-xs font-semibold text-bg transition-colors hover:bg-body">
            {productDetail.help.submit}
          </button>
        </div>
      </div>
    </div>
  );
}
