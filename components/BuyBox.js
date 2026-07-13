'use client';

import { useState } from 'react';
import { useCart } from './CartProvider';

// Counter (109:1422) + "הוספה לסל" (109:1423). They share the quantity, so adding
// to the cart honours what the stepper says.
export default function BuyBox({ slug, label }) {
  const cart = useCart();
  const [qty, setQty] = useState(1);
  const [added, setAdded] = useState(false);

  function add() {
    cart.add(slug, qty);
    setAdded(true);
    setTimeout(function () { setAdded(false); }, 1400);
  }

  return (
    <div className="flex items-center gap-6">
      <button
        onClick={add}
        className={
          'flex h-10 w-[135px] items-center justify-center rounded-pill border px-8 text-xs font-medium transition-colors ' +
          (added
            ? 'border-cat-green bg-cat-green/10 text-cat-green'
            : 'border-white/40 text-white hover:border-white')
        }
      >
        {added ? '✓ נוסף לסל' : label}
      </button>

      {/* dir=ltr keeps minus left / plus right — an RTL flip reads wrong for a stepper. */}
      <div dir="ltr" className="flex items-center gap-[5px]">
        <button
          onClick={function () { setQty(Math.max(1, qty - 1)); }}
          aria-label="הפחתת כמות"
          className="flex h-8 w-8 items-center justify-center rounded-full bg-[#F4ECEC] p-1 transition-opacity hover:opacity-80"
        >
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src="/icons/minus.svg" alt="" className="h-[14px] w-[14px]" />
        </button>

        <span className="w-8 text-center text-[11px] leading-[11.5px] text-white">{qty}</span>

        <button
          onClick={function () { setQty(qty + 1); }}
          aria-label="הוספת כמות"
          className="flex h-8 w-8 items-center justify-center rounded-full bg-[#EAFAE1] p-1 transition-opacity hover:opacity-80"
        >
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src="/icons/plus.svg" alt="" className="h-[14px] w-[14px]" />
        </button>
      </div>
    </div>
  );
}
