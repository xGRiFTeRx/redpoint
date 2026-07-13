import Link from 'next/link';
import Logo from './Logo';
import NavIcons from './NavIcons';
import { navLinks } from '../lib/data';

// Navigation Bar (109:214), 1440x100, pad 20/60, justify-between.
// In the design (LTR canvas) it reads: icons | links | logo. Under dir=rtl the
// first DOM child lands on the right, so the order here is logo → links → icons.
export default function Header() {
  return (
    <header className="absolute inset-x-0 top-0 z-50">
      {/* Figma: flex row, space-between, align-center, padding 20/60, gap 89, height 100. */}
      <div className="mx-auto flex h-[100px] max-w-shell items-center justify-between gap-[89px] px-gutter py-5">
        <Logo />

        <nav className="hidden items-start gap-6 md:flex">
          {navLinks.map(function (link) {
            const classes = link.active
              ? 'text-base font-bold text-white'
              : 'text-base font-medium text-navlink transition-colors hover:text-white';
            return (
              <Link key={link.href} href={link.href} className={classes}>
                {link.label}
              </Link>
            );
          })}
        </nav>

        <NavIcons />
      </div>
    </header>
  );
}
