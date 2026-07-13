import Link from 'next/link';
import Hero from '../../../components/Hero';
import ProductGallery from '../../../components/ProductGallery';
import ProductInfo from '../../../components/ProductInfo';
import SectionHeading from '../../../components/SectionHeading';
import SpecPanel from '../../../components/SpecPanel';
import ProductGrid from '../../../components/ProductGrid';
import FeatureRows from '../../../components/FeatureRows';
import PromoBanner from '../../../components/PromoBanner';
import BrandStory from '../../../components/BrandStory';
import VivePanel from '../../../components/VivePanel';
import BlogFeature from '../../../components/BlogFeature';
import NewsletterBanner from '../../../components/NewsletterBanner';
import { products, getProduct, getCategory, productDetail, sections } from '../../../lib/data';
import { promoImage, newsletterImage, viveImage, specBackdrop, specPhoto, galleryMain } from '../../../lib/images';

export function generateStaticParams() {
  return products.map(function (p) { return { slug: p.slug }; });
}

// Product Details — frame 109:1330 (1440x8307). The hero is the compact 220px
// variant: nav + category strip only, no photo and no title.
export default function ProductPage({ params }) {
  const product = getProduct(params.slug);

  if (!product) {
    return (
      <div className="mx-auto max-w-shell px-gutter py-24 text-center text-white">
        המוצר לא נמצא
      </div>
    );
  }

  const category = getCategory(product.category);
  const others = products.filter(function (p) { return p.slug !== params.slug; });

  // "שדרוג רכישה" (109:1475) is a single row with NO carousel dots beneath it, while
  // "מוצרים שאולי תאהבו" (109:1624) has them — that dot row is worth 64px of section
  // height. Four products give the carousel one page, so it drew no dots and the related
  // section came up short; feeding it a second page's worth makes the dots real.
  const upsell = others.slice(0, 4);
  const related = others.slice(4, 11);

  return (
    <>
      <Hero compact activeCategory={product.category} />

      <nav className="mx-auto max-w-shell px-gutter py-2 text-xs text-body">
        <Link href="/">דף הבית</Link>
        {' / '}
        <Link href={'/category/' + product.category}>
          {category ? category.name : product.category}
        </Link>
        {' / '}
        <span className="text-white">{product.name}</span>
      </nav>

      {/* Main block (109:1376): gallery beside the 500px info column, 95px apart.
          The description below is part of the SAME frame in the design, only 32px under
          the gallery (109:1442 sits at y=692, the gallery ends at 660). Kept as two
          sections here because they translate to two Elementor widgets, but the padding
          is split so the gap between them is 32px and not 50+50 — that alone was making
          this block 66px taller than the design. */}
      <section className="mx-auto max-w-shell px-gutter pb-0 pt-[50px]">
        {/* Info first so RTL paints it right and the gallery left, as designed.
            1320 = gallery 775 (107 rail + 16 gap + 652 main) + 45 gap + info 500. */}
        <div className="flex flex-col gap-[45px] md:flex-row md:items-start">
          <ProductInfo product={product} />

          <div className="flex-1">
            <ProductGallery images={product.gallery} main={galleryMain} />
          </div>
        </div>
      </section>

      {/* "תיאור המוצר" (109:1442) — heading then the long description, 32px under the
          gallery and 50px clear of the spec panel below. */}
      <section className="mx-auto flex max-w-shell flex-col gap-6 px-gutter pb-[50px] pt-8">
        <SectionHeading
          lead={productDetail.description.lead}
          accent={productDetail.description.accent}
          size="small"
        />

        {/* Latin copy: dir=ltr keeps the punctuation at the correct end — under RTL the
            trailing full stop jumps to the front of the line. */}
        <p dir="ltr" className="text-right text-base leading-[1.6] text-muted">
          {productDetail.description.body}
        </p>
      </section>

      <SpecPanel backdrop={specBackdrop} photo={specPhoto} />

      <ProductGrid heading={sections.upsell} products={upsell} columns={4} dots={false} />

      <FeatureRows />

      <PromoBanner image={promoImage} />

      <BrandStory />

      <ProductGrid heading={sections.youMayLike} products={related} columns={4} />

      <VivePanel image={viveImage} pad={50} />

      <BlogFeature />

      <NewsletterBanner image={newsletterImage} />
    </>
  );
}
