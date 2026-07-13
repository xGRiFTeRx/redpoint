import { valueBadges } from '../lib/data';

// Value strip (109:555), 1320x106: flex row, justify-center, gap 90, padding 16/60.
// Same chip as TrustBadges — translucent red circle, 20px red glyph — but title only.
export default function ValueBadges() {
  return (
    <div className="flex items-center justify-center gap-[90px] px-gutter py-4">
      {valueBadges.map(function (v) {
        return (
          <div
            key={v.id}
            data-phosphor-icon={v.phosphor}
            className="flex w-[131px] flex-col items-center gap-4 text-center"
          >
            <span className="flex items-center justify-center rounded-full bg-accent/10 p-3">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img src={v.icon} alt="" className="h-5 w-5" />
            </span>

            <span className="text-sm font-medium leading-none text-white">{v.title}</span>
          </div>
        );
      })}
    </div>
  );
}
