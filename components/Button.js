// Two button treatments in the design:
//  - solid  : light pill, near-black label (#0C0C0C), 18/18 w600 — primary CTAs
//  - outline: hairline border, white label, 12/12 w500 — product card "הוספה לסל"
export default function Button({ children, variant, className, ...props }) {
  // leading-none is what makes these land on the design's heights: the label sets the
  // line box, so solid = 11 + 18 + 11 = 40 (109:650) and outline = 14 + 12 + 14 = 40
  // (109:396). Without it Tailwind's text-lg contributes a 28px line-height and the
  // solid button renders 56px tall.
  const base = 'inline-flex items-center justify-center rounded-pill font-body leading-none transition-colors';
  const variants = {
    solid: 'bg-white text-bg text-lg font-semibold px-8 py-[11px] hover:bg-body',
    outline: 'border border-white/20 text-white bg-transparent text-xs font-medium px-8 py-[14px] hover:border-white/50',
  };
  const style = variants[variant || 'solid'];
  const classes = base + ' ' + style + (className ? ' ' + className : '');

  return (
    <button className={classes} {...props}>
      {children}
    </button>
  );
}
