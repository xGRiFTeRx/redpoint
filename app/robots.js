// Pre-launch preview build — nothing here should be crawled.
export default function robots() {
  return {
    rules: [{ userAgent: '*', disallow: '/' }],
  };
}
