import Media from './Media';
import { productDetail } from '../lib/data';

// "הסדרה החדשה VIVE" (109:1261). A split panel, 400px tall, 24px corners:
// a 720px photo on one side and a 600px WHITE panel on the other carrying the
// 70px headline (VIVE in accent red), the spec line, and a black pill CTA.
//
// `pad` differs by page, like the promo banner: 60px on the category page (109:1261,
// section 520 tall) and 50px on the product page (109:1730, 500).
export default function VivePanel({ image, pad = 60 }) {
  return (
    <section
      className="mx-auto max-w-shell px-gutter"
      style={{ paddingTop: pad, paddingBottom: pad }}
    >
      <div className="flex flex-col overflow-hidden rounded-3xl md:flex-row">
        {/* White panel first so RTL places it on the right, as in the design. */}
        <div className="flex h-[400px] flex-col justify-center gap-8 bg-white p-6 md:w-[600px]">
          <div className="flex flex-col gap-3 text-right">
            <h3 className="font-heading text-[70px] leading-none text-black">
              <span className="font-light">הסדרה החדשה </span>
              <span className="text-accent">VIVE</span>
            </h3>

            <p className="text-base leading-[1.4] text-black/80">{productDetail.specLine}</p>
          </div>

          <div className="flex justify-start">
            <button className="flex h-10 w-[146px] items-center justify-center rounded-pill bg-black px-6 text-lg font-semibold text-white transition-opacity hover:opacity-80">
              קנו עכשיו
            </button>
          </div>
        </div>

        <Media src={image} alt="" className="h-[400px] flex-1" />
      </div>
    </section>
  );
}
