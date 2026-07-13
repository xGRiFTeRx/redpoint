import Link from 'next/link';
import Image from 'next/image';
import { categories, footer, navLinks } from '../lib/data';

// Footer Links (109:764), 1320x224. Four blocks; in the design (LTR canvas) they
// read logo | Contact Info. | Website Categories | Redpoint Sex Shop left-to-right,
// so under dir="rtl" the order below is reversed to land them the same way.
//
// Column heading: Futurism 24, accent red. Links: Google Sans 16/24, white, gap 16.
// The footer wordmark is 100px tall — larger than the 60px one in the header.
function Column({ heading, children }) {
  return (
    <div className="flex flex-col items-start gap-4 text-right">
      <h2 className="font-heading text-2xl leading-none text-accent">{heading}</h2>
      <ul className="flex w-full flex-col gap-4 text-base leading-6 text-white">{children}</ul>
    </div>
  );
}

export default function Footer() {
  return (
    // Footer Section (109:750): flex column, align-items flex-start,
    // padding 32/60, gap 40, bg #0C0C0C.
    <footer className="flex flex-col items-start gap-10 bg-bg pb-8">
      <div className="mx-auto flex w-full max-w-shell flex-wrap justify-between gap-10 px-gutter pt-8">
        <Column heading={footer.brand}>
          {navLinks.slice(1, 5).map(function (link) {
            return (
              <li key={link.href}>
                <Link href={link.href} className="hover:text-accent">
                  {link.label}
                </Link>
              </li>
            );
          })}
        </Column>

        <Column heading={footer.categoriesHeading}>
          {categories.slice(0, 4).map(function (cat) {
            return (
              <li key={cat.slug}>
                <Link href={'/category/' + cat.slug} className="hover:text-accent">
                  {cat.name}
                </Link>
              </li>
            );
          })}
        </Column>

        <Column heading={footer.contactHeading}>
          <li>{footer.phoneLabel}</li>
          <li>{footer.phone}</li>
          {navLinks.slice(5).map(function (link) {
            return (
              <li key={link.href}>
                <Link href={link.href} className="hover:text-accent">
                  {link.label}
                </Link>
              </li>
            );
          })}
        </Column>

        {/* Logo block last so RTL paints it leftmost, as in the design. */}
        <div className="flex w-[237px] flex-col gap-6">
          <div className="flex h-[100px] items-center justify-start gap-2">
            <Image
              src="/images/logomark.webp"
              alt=""
              width={71}
              height={100}
              className="h-[100px] w-[71px] shrink-0 object-contain"
            />
            <div className="font-heading text-right text-[64px] leading-[0.56]">
              <span className="block text-[#D8103A]">רד</span>
              <span className="block text-white">פוינט</span>
            </div>
          </div>

          <p className="font-heading text-right text-2xl leading-[1.3] text-white">
            {footer.tagline}
          </p>
        </div>
      </div>

      <div className="mx-auto w-full max-w-shell px-gutter text-left text-sm leading-5 text-muted">
        {footer.copyright}
      </div>
    </footer>
  );
}
