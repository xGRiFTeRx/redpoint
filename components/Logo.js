import Link from 'next/link';
import Image from 'next/image';

// Wordmark (109:231), 176x60: "רד" in #D8103A above "פוינט" in white, both
// Futurism 44.44/0.56, with the mermaid mark (42x60) on the RIGHT of the text.
// Mark first so RTL paints it rightmost, matching the design.
export default function Logo() {
  return (
    <Link href="/" className="flex h-[60px] w-[176px] items-center justify-start gap-2">
      <Image
        src="/images/logomark.webp"
        alt=""
        width={42}
        height={60}
        className="h-[60px] w-[42px] shrink-0 object-contain"
      />

      {/* Figma sets 44px/0.56 (a 25px line box), which stacks the two words almost
          touching. Client asked for more air, so the leading is opened to 0.72 —
          this is a deliberate deviation from the Figma spec, not a translation of it. */}
      <div className="font-heading text-right text-[42px] leading-[0.72]">
        <span className="block text-[#D8103A]">רד</span>
        <span className="block text-white">פוינט</span>
      </div>
    </Link>
  );
}
