import Hero from '../../../components/Hero';
import ProductBrowser from '../../../components/ProductBrowser';
import PromoBanner from '../../../components/PromoBanner';
import VivePanel from '../../../components/VivePanel';
import BlogFeature from '../../../components/BlogFeature';
import NewsletterBanner from '../../../components/NewsletterBanner';
import { categories, getCategory, getProductsByCategory, products } from '../../../lib/data';
import {
  categoryHeroes,
  heroCategoryImage,
  promoImage,
  newsletterImage,
  viveImage,
} from '../../../lib/images';

export function generateStaticParams() {
  return categories.map(function (c) { return { slug: c.slug }; });
}

const STRAPLINE = [
  'Lorem ipsum dolor sit amet, consectetur',
  'adipiscing elit. Sed do eiusmod tempor incididunt',
];

// Category — frame 109:795. Hero carries the category name + strapline, then a
// toolbar (icons + Sort by), a 4-column grid (12 cards) and a 280px filter rail.
export default function CategoryPage({ params }) {
  const category = getCategory(params.slug);
  const matched = getProductsByCategory(params.slug);

  // The design shows a full 4x3 grid; the mock catalogue is thin, so lead with the
  // category's own products and pad with the rest so the filters have something to bite on.
  const rest = products.filter(function (p) { return p.category !== params.slug; });
  const grid = matched.concat(rest);
  const title = category ? category.name : 'קטגוריה';

  return (
    <>
      <Hero
        image={categoryHeroes[params.slug] || heroCategoryImage}
        title={[title]}
        subtitle={STRAPLINE}
        italicSubtitle
        activeCategory={params.slug}
      />

      <section className="mx-auto max-w-shell px-gutter py-[50px]">
        <ProductBrowser products={grid} />
      </section>

      <PromoBanner image={promoImage} pad={60} />

      <VivePanel image={viveImage} />

      <BlogFeature />

      <NewsletterBanner image={newsletterImage} />
    </>
  );
}
