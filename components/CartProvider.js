'use client';

import { createContext, useContext, useMemo, useState } from 'react';

// Minimal client-side cart so "הוספה לסל" does something visible. On the real
// site WooCommerce owns this — here it just drives the header badge and the
// button's confirmation state.
const CartContext = createContext(null);

export function CartProvider({ children }) {
  const [items, setItems] = useState({});

  const value = useMemo(
    function () {
      const count = Object.values(items).reduce(function (n, q) { return n + q; }, 0);

      return {
        items: items,
        count: count,
        add: function (slug, qty) {
          const n = qty || 1;
          setItems(function (prev) {
            const next = Object.assign({}, prev);
            next[slug] = (next[slug] || 0) + n;
            return next;
          });
        },
      };
    },
    [items]
  );

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart() {
  return useContext(CartContext);
}
