import ProductCard from './ProductCard';
import SectionHeading from './SectionHeading';
import Carousel from './Carousel';
import Button from './Button';

// Product row (Frame 2147226900, 1320x563, 16px gap), heading 50px above.
// The dots beneath are a working carousel: each page shows `columns` cards.
//
// `flush` drops the bottom padding. The home page's "הנמכרים ביותר" (109:372) is the
// one grid whose frame ends flush with its carousel dots — 90px top, 0 bottom — while
// every other grid carries the usual 68px. That looks like an oversight in the design
// rather than intent, but the reference build follows the file; worth a word with the
// designer before it gets baked into Elementor.
export default function ProductGrid({ heading, products, columns = 4, dots = true, cta, flush }) {
  const cols = {
    3: 'md:grid-cols-3',
    4: 'md:grid-cols-4',
  }[columns];

  return (
    <section
      className={
        'mx-auto flex max-w-shell flex-col gap-[50px] px-gutter pt-[90px] ' +
        (flush ? 'pb-0' : 'pb-[68px]')
      }
    >
      {heading ? (
        <SectionHeading lead={heading.lead} accent={heading.accent} kicker={heading.kicker} />
      ) : null}

      <Carousel perPage={columns} cols={cols} dots={dots}>
        {products.map(function (p, i) {
          return <ProductCard key={p.slug + '-' + i} product={p} />;
        })}
      </Carousel>

      {cta ? (
        <div className="flex justify-center">
          <Button variant="solid">{cta}</Button>
        </div>
      ) : null}
    </section>
  );
}
