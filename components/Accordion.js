'use client';

import { useState } from 'react';

export default function Accordion({ items }) {
  const [openId, setOpenId] = useState(null);

  return (
    <div className="divide-y divide-white/10 border-t border-b border-white/10">
      {items.map(function (item) {
        const isOpen = openId === item.id;
        return (
          <div key={item.id}>
            <button
              className="w-full flex items-center justify-between py-4 text-white text-right"
              onClick={function () { setOpenId(isOpen ? null : item.id); }}
            >
              <span className="font-heading font-light text-2xl leading-[22px]">{item.title}</span>
              <span>{isOpen ? '-' : '+'}</span>
            </button>
            {isOpen ? (
              <div className="pb-4 text-sm text-body/70">{item.content}</div>
            ) : null}
          </div>
        );
      })}
    </div>
  );
}
