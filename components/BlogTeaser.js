import Media from './Media';
import SectionHeading from './SectionHeading';
import CarouselDots from './CarouselDots';
import { blogPosts, sections } from '../lib/data';

// "שווה לדעת" (109:652). Card (109:657):
//   image  305px, rounded top
//   body   #111, 16px padding, 16px gap, rounded bottom
//          date 12/#818181 · title 18 Medium white 1.3 · excerpt 14/#C5C5C5 1.4
//          "Read more" 14 Medium in accent red with a 14px arrow
export default function BlogTeaser() {
  return (
    <section className="mx-auto flex max-w-shell flex-col gap-[50px] px-gutter pb-[68px] pt-[90px]">
      <SectionHeading
        lead={sections.blog.lead}
        accent={sections.blog.accent}
        kicker={sections.blog.kicker}
        kickerWidth={sections.blog.kickerWidth}
      />

      {/* Reversed: an RTL grid fills right-to-left, so without this the three posts
          come out mirrored against the design's left-to-right order (109:656). */}
      <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
        {blogPosts.slice().reverse().map(function (post) {
          return (
            <article key={post.id} className="flex flex-col">
              <Media src={post.image} alt="" className="h-[305px] w-full rounded-t" />

              <div className="flex flex-col items-start gap-4 rounded-b bg-panel p-4">
                {/* Latin copy: dir=ltr keeps the punctuation at the correct end. */}
                <div dir="ltr" className="flex w-full flex-col gap-1 text-right">
                  <span className="text-xs leading-[1.4] text-muted">{post.date}</span>
                  <h3 className="text-lg font-medium leading-[1.3] text-white">{post.title}</h3>
                </div>

                <p dir="ltr" className="w-full text-right text-sm leading-[1.4] text-[#C5C5C5]">
                  {post.excerpt}
                </p>

                <span className="flex items-center gap-1.5 text-sm font-medium text-accent">
                  {post.cta}
                  <svg viewBox="0 0 14 14" className="h-3.5 w-3.5" fill="none" stroke="currentColor" strokeWidth="1.17" strokeLinecap="round" aria-hidden="true">
                    <path d="M11.08 7H2.92M6.42 3.5 2.92 7l3.5 3.5" />
                  </svg>
                </span>
              </div>
            </article>
          );
        })}
      </div>

      <CarouselDots count={4} active={0} />
    </section>
  );
}
