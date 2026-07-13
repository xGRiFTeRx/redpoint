import Image from 'next/image';

// Image slot. Renders the exported Figma asset when we have one, and a grey
// placeholder block when we don't.
//
// next/image `fill` needs a positioned ancestor. Callers that place the slot
// themselves pass their own positioning (e.g. "absolute inset-0"); only add
// `relative` when they haven't, otherwise the two classes fight and the
// wrapper collapses to zero height.
const POSITIONED = /(^|\s)(absolute|fixed|relative|sticky)(\s|$)/;

export default function Media({
  src,
  alt = '',
  className = '',
  fill = true,
  width,
  height,
  objectPosition,
}) {
  if (!src) {
    return <div className={'bg-[#1a1a1a] ' + className} aria-hidden="true" />;
  }

  if (!fill) {
    return <Image src={src} alt={alt} width={width} height={height} className={className} />;
  }

  const position = POSITIONED.test(className) ? '' : 'relative ';

  return (
    <div className={position + 'overflow-hidden ' + className}>
      <Image
        src={src}
        alt={alt}
        fill
        className="object-cover"
        style={objectPosition ? { objectPosition } : undefined}
      />
    </div>
  );
}
