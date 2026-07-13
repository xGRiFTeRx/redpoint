'use client';

import { useState } from 'react';
import Media from './Media';
import { newsletter } from '../lib/data';

// Newsletter card (109:751). Sits inside the Footer Section frame, which is
// flex column, align-items flex-start, padding 32/60, gap 40, bg #0C0C0C.
//
// Card:  1320x400, 24px radius, photo + a flat black/20 wash.
// Panel: 486px, black/40, 32px radius, 24px padding, 32px gap (109:752).
//
// The form has no backend here — it validates and confirms, which is as far as a
// reference build should go. WooCommerce/Mailchimp owns the real submit.
export default function NewsletterBanner({ image }) {
  const [email, setEmail] = useState('');
  const [sent, setSent] = useState(false);

  function onSubmit(e) {
    e.preventDefault();
    if (!email.includes('@')) return;
    setSent(true);
    setEmail('');
    setTimeout(function () { setSent(false); }, 2600);
  }

  return (
    <section className="mx-auto max-w-shell px-gutter pt-8">
      <div className="relative flex h-[400px] items-center justify-center overflow-hidden rounded-3xl">
        {/* Figma scales the photo to 220% of the card and offsets it -43.4%, so the
            visible band runs ~19.7%-65.2% down the image — centre ≈ 42%. */}
        <Media
          src={image}
          alt=""
          className="absolute inset-0 h-full w-full"
          objectPosition="center 42%"
        />
        <div className="absolute inset-0 bg-black/20" aria-hidden="true" />

        <div className="relative z-10 flex w-[486px] max-w-full flex-col gap-8 rounded-[32px] bg-black/40 p-6">
          <div className="flex flex-col gap-3">
            {/* 407px box (109:755) — that width is what breaks the line after "לרשימת". */}
            <h3 className="mx-auto w-[407px] max-w-full text-center font-heading text-[50px] leading-none text-white">
              <span className="font-light">{newsletter.titleLead}</span>{' '}
              <span>{newsletter.titleRest}</span>
            </h3>

            <p className="text-center text-base leading-[1.4] text-white/80">
              {sent ? 'תודה! נרשמתם בהצלחה.' : newsletter.subtitle}
            </p>
          </div>

          {/* Field first so RTL paints it right and the button left, as designed. */}
          <form onSubmit={onSubmit} className="flex w-full items-center gap-4">
            <input
              type="email"
              required
              value={email}
              onChange={function (e) { setEmail(e.target.value); }}
              placeholder={newsletter.placeholder}
              className="h-10 flex-1 rounded-[32px] bg-white/20 px-3 py-2 text-right text-base text-white placeholder:text-[#E9E9E9]"
            />

            <button
              type="submit"
              className={
                'flex h-10 w-[146px] shrink-0 items-center justify-center rounded-pill px-6 text-lg font-semibold transition-colors ' +
                (sent ? 'bg-cat-green text-bg' : 'bg-white text-bg hover:bg-body')
              }
            >
              {sent ? '✓' : newsletter.cta}
            </button>
          </form>
        </div>
      </div>
    </section>
  );
}
