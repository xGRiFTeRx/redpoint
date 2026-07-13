'use client';

import { useCart } from './CartProvider';

// Icon chips from the nav bar and the category toolbar (109:215).
// Each: 32x32, bg rgba(37,37,37,0.2), fully rounded, 20px Phosphor glyph.
//
// Figma's LTR order is bag → user → heart → search (bag leftmost). Under dir="rtl"
// the first child paints rightmost, so the list is reversed to keep bag on the left.
const ICONS = [
  { name: 'MagnifyingGlass', label: 'חיפוש', src: '/icons/search.svg' },
  { name: 'Heart', label: 'מועדפים', src: '/icons/heart.svg' },
  { name: 'UserCircle', label: 'חשבון', src: '/icons/user.svg' },
  { name: 'ShoppingBag', label: 'עגלה', src: '/icons/bag.svg', cart: true },
];

export default function NavIcons({ gap = 'gap-4' }) {
  const cart = useCart();

  return (
    <div className={'flex items-center ' + gap}>
      {ICONS.map(function (icon) {
        const showBadge = icon.cart && cart && cart.count > 0;

        return (
          <button
            key={icon.name}
            aria-label={icon.label + (showBadge ? ' (' + cart.count + ')' : '')}
            data-phosphor-icon={icon.name}
            className="relative flex h-8 w-8 items-center justify-center rounded-pill bg-surface/20 p-1.5 transition-colors hover:bg-surface/50"
          >
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img src={icon.src} alt="" className="h-5 w-5" />

            {showBadge ? (
              <span className="absolute -right-1 -top-1 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-accent px-1 text-[10px] font-semibold leading-none text-white">
                {cart.count}
              </span>
            ) : null}
          </button>
        );
      })}
    </div>
  );
}
