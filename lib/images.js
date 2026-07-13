// Assets exported from the RED POINT Figma file (Kx2t5PDOZvCVbhCeAnqFgl).
//
// These are the raw IMAGE FILLS pulled via GET /v1/files/:key/images, not rendered
// frames — rendering a frame bakes its text and child layers into the PNG. They are
// resized and converted to WebP; see README for the re-export recipe.

// The hero fill is a VIDEO in Figma (6037377_Woman_Sexy_1280x720). heroImage is the
// exported poster frame; drop the source clip at public/video/hero.mp4 and set
// heroVideo to switch the hero from a still to the animated background.
export const heroImage = '/images/hero.webp';
export const heroVideo = null; // '/video/hero.mp4'

// The Category hero uses its own photograph (109:797), not the homepage silk shot.
export const heroCategoryImage = '/images/hero-category.webp';

// Figma only designs ONE category page (לזוגות), so there is no per-category art in
// the file. The client wants each archive to carry its own hero, so these are stand-ins
// drawn from the design's existing photography — swap each for the real shot when the
// designer supplies them. Falls back to the Figma couple shot if a slug is missing.
// Each must be dark enough to carry white headline + nav — the thematically obvious
// picks (the BDSM harness on white, the grapefruit) were too bright and the type
// vanished, so they're deliberately not used here.
export const categoryHeroes = {
  couples: '/images/hero-category.webp', // the shot Figma actually specifies
  women: '/images/cat-gentle.webp',
  men: '/images/cat-intensity.webp',
  'sex-toys': '/images/spec-photo.webp',
  anal: '/images/newsletter.webp',
  lingerie: '/images/cat-wear.webp',
  bdsm: '/images/blog-1.webp',
  lubricants: '/images/hero.webp',
};
export const promoImage = '/images/promo.webp';
export const newsletterImage = '/images/newsletter.webp';
export const viveImage = '/images/vive.webp';       // 109:1263, VIVE split panel
export const logoMark = '/images/logomark.webp';    // 109:235, wordmark glyph

// Spec / FAQ panel on the product page (109:1454): light textured backdrop + photo.
export const specBackdrop = '/images/spec-bg.webp';
export const specPhoto = '/images/spec-photo.webp';

// "למצוא את ההנאה שלך" cards, in design order (first is the tall 652x600 card).
export const pleasureImages = [
  '/images/cat-gentle.webp',
  '/images/cat-couples.webp',
  '/images/cat-intensity.webp',
  '/images/cat-explore.webp',
  '/images/cat-wear.webp',
];

// Product photography, pulled per card node (NOT picked by eye off a contact sheet —
// that is how the pleasure-grid and blog shots ended up wrong twice).
//   1-4  bestsellers row   109:377, 399, 424, 449   (left -> right in the design)
//   5-10 worth-attention   109:484, 506, 531 / 578, 601, 625
// Products cycle through this list, and the two home rows consume it in order, so the
// first ten demo products land on the photograph the design actually specifies for
// their slot. The design itself repeats one placeholder product ("Eclipse Duo", ₪610)
// in every card; the names and prices here are demo data and are meant to differ.
export const productImages = [
  '/images/product-1.webp',
  '/images/product-2.webp',
  '/images/product-3.webp',
  '/images/product-4.webp',
  '/images/product-5.webp',
  '/images/product-6.webp',
  '/images/product-7.webp',
  '/images/product-8.webp',
  '/images/product-9.webp',
  '/images/product-10.webp',
];

// Product-page feature panels (109:1576) — one photo per row, pulled per node
// (109:1579, 1589, 1591, 1601).
export const featureImages = [
  '/images/feature-1.webp',
  '/images/feature-2.webp',
  '/images/feature-3.webp',
  '/images/feature-4.webp',
];

// Product gallery (109:1378). The design's main 652x600 frame (109:1385) shows a photo
// that is NOT any of the five thumbs — the rail runs [1, 2, 3, 1, 3] off three unique
// images. It is a static mock with no selected state, so there is no inconsistency to
// resolve in the file; here the gallery simply RESTS on the design's main image and the
// thumbs drive it from there.
//
// The design specifies this one gallery, for Eclipse Duo, so every product reuses it —
// on the real store the gallery comes from the WooCommerce product.
export const galleryMain = '/images/gallery-main.webp';
export const galleryImages = [
  '/images/gallery-1.webp',
  '/images/gallery-2.webp',
  '/images/gallery-3.webp',
  '/images/gallery-1.webp',
  '/images/gallery-3.webp',
];

export const blogImages = [
  '/images/blog-1.webp',
  '/images/blog-2.webp',
  '/images/blog-3.webp',
];

// Testimonial section: full-bleed pink gradient behind two black cards,
// each with a tall portrait beside the copy (109:703, 109:722).
export const testimonialBg = '/images/testimonial-bg.webp';

export const portraitImages = [
  '/images/portrait-1.webp',
  '/images/portrait-2.webp',
];
