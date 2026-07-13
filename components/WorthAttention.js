import ProductCard from './ProductCard';
import SectionHeading from './SectionHeading';
import ValueBadges from './ValueBadges';
import Button from './Button';
import { sections } from '../lib/data';

// "מוצרים ששווים תשומת הלב" (109:478). Unlike the other product sections this one
// interleaves the value strip between two rows of three, and closes with a solid
// pill instead of carousel dots:
//   heading -> row of 3 (109:482) -> value strip (109:555) -> row of 3 (109:576)
//   -> "לכל המוצרים" (109:649)
export default function WorthAttention({ products }) {
  // Reversed for the same reason as Carousel: an RTL grid fills right-to-left, so
  // without this each row of three comes out mirrored against the design.
  const first = products.slice(0, 3).reverse();
  const second = products.slice(3, 6).reverse();

  return (
    <section className="mx-auto flex max-w-shell flex-col gap-[50px] px-gutter pb-[68px] pt-[90px]">
      <SectionHeading lead={sections.worthAttention.lead} accent={sections.worthAttention.accent} />

      <div className="grid grid-cols-2 gap-4 md:grid-cols-3">
        {first.map(function (p) {
          return <ProductCard key={p.slug} product={p} />;
        })}
      </div>

      <ValueBadges />

      <div className="grid grid-cols-2 gap-4 md:grid-cols-3">
        {second.map(function (p) {
          return <ProductCard key={p.slug} product={p} />;
        })}
      </div>

      <div className="flex justify-center">
        <Button variant="solid" className="w-[400px]">לכל המוצרים</Button>
      </div>
    </section>
  );
}
