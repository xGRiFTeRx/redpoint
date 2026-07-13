import Hero from '../components/Hero';
import TrustBadges from '../components/TrustBadges';
import PromoBanner from '../components/PromoBanner';
import CategoryGrid from '../components/CategoryGrid';
import ProductGrid from '../components/ProductGrid';
import WorthAttention from '../components/WorthAttention';
import BlogTeaser from '../components/BlogTeaser';
import Testimonials from '../components/Testimonials';
import BrandStory from '../components/BrandStory';
import NewsletterBanner from '../components/NewsletterBanner';
import { products, hero, sections } from '../lib/data';
import { heroImage, heroVideo, promoImage, newsletterImage } from '../lib/images';

// Homepage — frame 109:210 (1440x7907). Section order below follows the frame
// top to bottom; see README for the frame-to-component map.
export default function HomePage() {
  // The row shows four at a time; the dots page through the rest.
  const bestSellers = products;
  // The design runs two rows of three here, closed by a "לכל המוצרים" pill.
  const worthAttention = products.slice(4, 10);

  return (
    <>
      <Hero
        image={heroImage}
        video={heroVideo}
        title={hero.title}
        subtitle={hero.subtitle}
        italicSubtitle
      />

      <TrustBadges />

      <PromoBanner image={promoImage} />

      <CategoryGrid />

      <ProductGrid heading={sections.bestSellers} products={bestSellers} columns={4} flush />

      <WorthAttention products={worthAttention} />

      <BlogTeaser />

      <Testimonials />

      <BrandStory />

      <NewsletterBanner image={newsletterImage} />
    </>
  );
}
