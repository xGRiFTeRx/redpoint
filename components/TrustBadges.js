import { trustBadges } from '../lib/data';

// Trust strip (109:257). Figma:
//   flex row, justify-center, align-center, padding 30px 60px, gap 90px, 1440x154
// Each badge (131px, gap 16, centred):
//   chip  — bg rgba(255,59,59,0.1), fully rounded, 12px padding, 20px red glyph
//   title — Google Sans Medium 14, white
//   sub   — Google Sans Regular 12, rgba(255,255,255,0.35)
export default function TrustBadges() {
  return (
    <section className="mx-auto flex h-[154px] max-w-shell items-center justify-center gap-[90px] px-gutter py-[30px]">
      {trustBadges.map(function (b) {
        return (
          <div key={b.id} className="flex w-[131px] flex-col items-center gap-4 text-center">
            <span className="flex items-center justify-center rounded-full bg-accent/10 p-3">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img src={b.icon} alt="" className="h-5 w-5" />
            </span>

            <div className="flex flex-col items-center gap-2">
              <span className="text-sm font-medium leading-none text-white">{b.title}</span>
              <span className="text-xs leading-none text-white/35">{b.subtitle}</span>
            </div>
          </div>
        );
      })}
    </section>
  );
}
