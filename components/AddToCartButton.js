'use client';

import { useState } from 'react';
import { useCart } from './CartProvider';

// "הוספה לסל" / "Add to Cart". Adds to the cart and flips to a short
// confirmation so the click has visible feedback.
export default function AddToCartButton({ slug, label, className, qty }) {
  const cart = useCart();
  const [added, setAdded] = useState(false);

  function onClick() {
    cart.add(slug, qty || 1);
    setAdded(true);
    setTimeout(function () { setAdded(false); }, 1400);
  }

  return (
    <button
      onClick={onClick}
      className={
        'flex items-center justify-center rounded-pill border transition-colors ' +
        (added
          ? 'border-cat-green bg-cat-green/10 text-cat-green'
          : 'border-white/40 text-white hover:border-white') +
        ' ' + className
      }
    >
      {added ? '✓ נוסף לסל' : label}
    </button>
  );
}
