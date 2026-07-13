// All copy below is transcribed from the RED POINT Figma file, not invented.
// Where the design repeats one placeholder product ("Eclipse Duo"), the extra
// products here keep that card's shape so grids fill out realistically.
import { pleasureImages, productImages, featureImages, blogImages, portraitImages, galleryImages } from './images';

// The 8 category pills (Frame 2147225523). Each owns a border colour in the design.
//
// Order matters: the Figma canvas is LTR, so its DOM runs חומרי סיכוך → לנשים and
// paints לנשים on the RIGHT. Under dir="rtl" the first child paints right, so this
// list is the Figma order REVERSED to land the pills in the same visual sequence.
export const categories = [
  { slug: 'women', name: 'לנשים', border: 'border-cat-magenta' },
  { slug: 'men', name: 'לגברים', border: 'border-cat-cyan' },
  { slug: 'couples', name: 'לזוגות', border: 'border-cat-pink' },
  { slug: 'sex-toys', name: 'צעצועי סקס', border: 'border-cat-orange' },
  { slug: 'anal', name: 'חוויה אנאלית', border: 'border-cat-purple' },
  { slug: 'lingerie', name: "ביגוד ולונז'ארי", border: 'border-cat-yellow' },
  { slug: 'bdsm', name: 'פטיש ו-BDSM', border: 'border-cat-green' },
  { slug: 'lubricants', name: 'חומרי סיכוך', border: 'border-cat-red' },
];

// Top nav links (Navigation Bar). "בית" is the active/bold item in the design.
export const navLinks = [
  { href: '/', label: 'בית', active: true },
  { href: '/about', label: 'עלינו' },
  { href: '/what-is-redpoint', label: 'מה זה רד פוינט?' },
  { href: '/shipping', label: 'משלוחים' },
  { href: '/privacy', label: 'אבטחה ופרטיות' },
  { href: '/contact', label: 'יצירת קשר' },
];

export const hero = {
  title: ['לעורר את', 'כל החושים.'],
  subtitle: ['הנאה מחודשת ומעוצבת, לסקרנים, למנוסים', 'ולמי שביניהם'],
};

// Trust strip under the hero (109:257): icon chip + title + subtitle, 131px wide.
// Icons are the design's own SVGs (red #FF3B3B stroke) exported from Figma.
//
// Reversed relative to Figma's LTR DOM: the canvas paints אריזה פשוטה leftmost, and
// under dir="rtl" the first child paints rightmost — so it has to come last here.
export const trustBadges = [
  { id: 'badge-4', icon: '/icons/trust-4.svg', title: '+40 שנות אמון', subtitle: 'מאז 1984' },
  { id: 'badge-3', icon: '/icons/trust-3.svg', title: 'משלוח דיסקרטי', subtitle: 'ללא מותג על הקופסה' },
  { id: 'badge-2', icon: '/icons/trust-2.svg', title: 'תשלום מאובטח', subtitle: 'צ’קאאוט מוצפן, תמיד' },
  { id: 'badge-1', icon: '/icons/trust-1.svg', title: 'אריזה פשוטה', subtitle: 'חבילה דיסקרטית 100%' },
];

// Value strip (109:555), sandwiched BETWEEN the two product rows of the
// "מוצרים ששווים תשומת הלב" section. Same chip as the trust badges, title only.
// Phosphor names kept for Elementor icon mapping. Reversed for RTL.
export const valueBadges = [
  { id: 'value-4', icon: '/icons/value-4.svg', phosphor: 'Handshake', title: 'שירות אישי' },
  { id: 'value-3', icon: '/icons/value-3.svg', phosphor: 'MagnifyingGlass', title: 'שקיפות' },
  { id: 'value-2', icon: '/icons/value-2.svg', phosphor: 'SealCheck', title: 'איכות' },
  { id: 'value-1', icon: '/icons/value-1.svg', phosphor: 'EyeSlash', title: 'דיסקרטיות' },
];

export const promo = {
  title: 'לרגעים מיוחדים',
  lines: ['עד 25% הנחה לאביזרים לזוגות', 'לרגל חג האהבה'],
  cta: 'קנו עכשיו',
};

// "למצוא את ההנאה שלך" grid. The first card is wide (620px), the rest are 286px.
// Each card carries its own CTA colour straight from the design.
const pleasureCardContent = [
  {
    id: 'p1',
    title: 'התחילו בעדינות',
    desc: 'היכרות עדינה עבור הסקרנים השקטים',
    color: 'text-cat-pink',
    wide: true,
    href: '/category/women',
  },
  {
    id: 'p2',
    title: 'לזוגות',
    desc: 'נועד לחוויה משותפת',
    color: 'text-cat-pink',
    href: '/category/couples',
  },
  {
    id: 'p3',
    title: 'הגבירו את העוצמה',
    desc: 'למי שיודעים בדיוק מה הם רוצים',
    color: 'text-cat-orange',
    href: '/category/sex-toys',
  },
  {
    id: 'p4',
    title: 'גלו עוד',
    desc: 'BDSM, משחק אנאלי והדברים הייחודיים והיוצאי דופן',
    color: 'text-cat-red',
    href: '/category/bdsm',
  },
  {
    id: 'p5',
    title: 'לבשו את זה',
    desc: 'הלבשה תחתונה וביגוד שמושכים את כל תשומת הלב',
    color: 'text-cat-yellow',
    href: '/category/lingerie',
  },
];

export const pleasureCards = pleasureCardContent.map(function (card, i) {
  return Object.assign({}, card, { image: pleasureImages[i] });
});

const PLACEHOLDER_DESC =
  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna.';

// Attributes below (type / material / colour / function / brand / size / rating /
// availability) are NOT in the Figma file — the design leaves those filter groups
// collapsed and empty. They are sample data so the filter rail can be demonstrated
// working. On the real site these are WooCommerce product attributes.
const productContent = [
  { slug: 'eclipse-duo', name: 'Eclipse Duo', price: 610, oldPrice: 679, category: 'couples', badge: 'New', desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'ויברטור', material: 'סיליקון', colour: 'סגול', func: 'רטט', brand: 'VIVE', size: 'בינוני', rating: 5 },
  { slug: 'velvet-touch', name: 'Velvet Touch', price: 320, oldPrice: null, category: 'women', badge: null, desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'ויברטור', material: 'סיליקון', colour: 'ורוד', func: 'רטט', brand: 'Aura', size: 'קטן', rating: 4 },
  { slug: 'midnight-pulse', name: 'Midnight Pulse', price: 450, oldPrice: 520, category: 'men', badge: 'Discount', desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'ויברטור', material: 'סיליקון', colour: 'כחול', func: 'שלט רחוק', brand: 'Eclipse', size: 'בינוני', rating: 5 },
  { slug: 'silk-bind-set', name: 'Silk Bind Set', price: 210, oldPrice: null, category: 'bdsm', badge: null, desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'כבילה', material: 'עור', colour: 'שחור', func: 'ללא', brand: 'רד פוינט', size: 'אחיד', rating: 4 },
  { slug: 'aura-glide', name: 'Aura Glide', price: 140, oldPrice: null, category: 'lubricants', badge: 'New', desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'חומר סיכוך', material: "ג'ל", colour: 'שקוף', func: 'חימום', brand: 'Aura', size: 'קטן', rating: 5 },
  { slug: 'lace-bodysuit', name: 'Lace Bodysuit', price: 380, oldPrice: 430, category: 'lingerie', badge: 'Discount', desc: PLACEHOLDER_DESC,
    availability: 'אזל מהמלאי', type: 'ביגוד', material: 'תחרה', colour: 'אדום', func: 'ללא', brand: 'רד פוינט', size: 'בינוני', rating: 4 },
  { slug: 'crimson-wand', name: 'Crimson Wand', price: 290, oldPrice: null, category: 'women', badge: null, desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'ויברטור', material: 'סיליקון', colour: 'אדום', func: 'רטט', brand: 'VIVE', size: 'גדול', rating: 5 },
  { slug: 'onyx-ring', name: 'Onyx Ring', price: 165, oldPrice: null, category: 'men', badge: null, desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'טבעת', material: 'סיליקון', colour: 'שחור', func: 'רטט', brand: 'Onyx', size: 'קטן', rating: 4 },
  { slug: 'satin-blindfold', name: 'Satin Blindfold', price: 95, oldPrice: null, category: 'bdsm', badge: null, desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'אביזר', material: 'סאטן', colour: 'שחור', func: 'ללא', brand: 'רד פוינט', size: 'אחיד', rating: 3 },
  { slug: 'obsidian-plug', name: 'Obsidian Plug', price: 120, oldPrice: null, category: 'anal', badge: null, desc: PLACEHOLDER_DESC,
    availability: 'בהזמנה מראש', type: 'פלאג', material: 'סיליקון', colour: 'כחול', func: 'ללא', brand: 'Onyx', size: 'קטן', rating: 5 },
  { slug: 'pulse-mini', name: 'Pulse Mini', price: 260, oldPrice: null, category: 'sex-toys', badge: null, desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'ויברטור', material: 'סיליקון', colour: 'ורוד', func: 'עמיד במים', brand: 'VIVE', size: 'קטן', rating: 4 },
  { slug: 'duo-gift-box', name: 'Duo Gift Box', price: 340, oldPrice: 390, category: 'couples', badge: 'Discount', desc: PLACEHOLDER_DESC,
    availability: 'במלאי', type: 'מארז', material: 'מעורב', colour: 'אדום', func: 'רטט', brand: 'רד פוינט', size: 'בינוני', rating: 5 },
];

// The design reuses a handful of product shots across every card; cycle them.
// The product gallery shows five frames (109:1377).
export const products = productContent.map(function (p, i) {
  const image = productImages[i % productImages.length];
  // The gallery is the design's own five-frame rail (109:1380–1384), not a slice of the
  // catalogue: the file specifies one gallery, for Eclipse Duo. On the real store it is
  // whatever the WooCommerce product carries.
  return Object.assign({}, p, { image: image, gallery: galleryImages });
});

// Filter rail (109:1165). Figma only fills in "קטגוריה" and leaves the other nine
// groups collapsed and empty, so their options are sample data. Each group names the
// product field it filters on; `price` and `rating` use predicates instead.
export const filterGroups = [
  {
    id: 'category',
    label: 'קטגוריה',
    field: 'category',
    options: categories.map(function (c) { return { label: c.name, value: c.slug }; }),
  },
  { id: 'availability', label: 'זמינות', field: 'availability', options: ['במלאי', 'אזל מהמלאי', 'בהזמנה מראש'] },
  { id: 'type', label: 'סוג', field: 'type', options: ['ויברטור', 'פלאג', 'טבעת', 'כבילה', 'ביגוד', 'חומר סיכוך', 'אביזר', 'מארז'] },
  { id: 'material', label: 'חומר', field: 'material', options: ['סיליקון', 'עור', 'תחרה', 'סאטן', "ג'ל", 'מעורב'] },
  { id: 'colour', label: 'צבע', field: 'colour', options: ['שחור', 'אדום', 'ורוד', 'סגול', 'כחול', 'שקוף'] },
  { id: 'function', label: 'פונקציה', field: 'func', options: ['רטט', 'חימום', 'שלט רחוק', 'עמיד במים', 'ללא'] },
  { id: 'brand', label: 'מותג', field: 'brand', options: ['רד פוינט', 'VIVE', 'Aura', 'Eclipse', 'Onyx'] },
  {
    id: 'price',
    label: 'מחיר',
    options: [
      { label: 'עד ₪100', test: function (p) { return p.price < 100; } },
      { label: '₪100-300', test: function (p) { return p.price >= 100 && p.price <= 300; } },
      { label: '₪300 ומעלה', test: function (p) { return p.price > 300; } },
    ],
  },
  { id: 'size', label: 'גודל', field: 'size', options: ['קטן', 'בינוני', 'גדול', 'אחיד'] },
  {
    id: 'rating',
    label: 'דירוג',
    options: [
      { label: '5 כוכבים', test: function (p) { return p.rating === 5; } },
      { label: '4 כוכבים ומעלה', test: function (p) { return p.rating >= 4; } },
      { label: '3 כוכבים ומעלה', test: function (p) { return p.rating >= 3; } },
    ],
  },
];

export function getProduct(slug) {
  return products.find(function (p) { return p.slug === slug; });
}

export function getProductsByCategory(slug) {
  return products.filter(function (p) { return p.category === slug; });
}

export function getCategory(slug) {
  return categories.find(function (c) { return c.slug === slug; });
}

// Product detail page (frame 109:1330), using the design's "Eclipse Duo" content.
export const productDetail = {
  tagline: 'חווייה אינטימית שנועדה לעונג ללא מאמץ.',
  vendor: 'רד פוינט - ישראל',
  specLine: '3 מנועים · טעינה מגנטית USB · סיליקון רפואי / היפואלרגני',
  reviewCount: '(10 ביקורות)',
  blurb: [
    'עיצוב חלק ונעים למגע, לחוויה אינטימית טבעית ובטוחה.',
    'נוצר כדי להעניק תחושת נוחות, שליטה והנאה אישית.',
  ],
  stock: 'במלאי',
  shipping: 'משלוח דיסקרטי',
  addToCart: 'הוספה לסל',
  // Customer-service card (109:1430).
  help: {
    title: 'צריכים עזרה? רוצים לקבל ייעוץ?',
    subtitle: 'שירות לקוחות שלנו כאן בשבילכם.',
    name: 'שם',
    phone: 'טלפון',
    submit: 'שלחו',
  },
  // "תיאור המוצר" — "תיאור" white, "המוצר" accent red.
  description: {
    lead: 'תיאור',
    accent: 'המוצר',
    body: PLACEHOLDER_DESC + PLACEHOLDER_DESC + PLACEHOLDER_DESC + PLACEHOLDER_DESC,
  },
  // Two collapsible headers above the light spec panel (109:1446 / 109:1450).
  faq: { lead: 'שאלות', accent: 'ותשובות' },
  spec: { lead: 'מפרט', accent: 'ומשלוח' },
  // Five white feature rows inside the panel (109:1458+).
  specRows: [0, 1, 2, 3, 4].map(function (i) {
    return {
      id: 'spec-' + i,
      title: 'Feature line one - lorem ipsum',
      body: PLACEHOLDER_DESC + PLACEHOLDER_DESC,
    };
  }),
  // The design leaves "שאלות ותשובות" collapsed with no content behind it. The client
  // asked for it to open the same panel as "מפרט ומשלוח", so these Q&As are sample
  // content in that shape — real copy to come from the client.
  faqRows: [
    {
      id: 'faq-0',
      title: 'איך בוחרים את המוצר הראשון?',
      body: 'מומלץ להתחיל במוצר פשוט ורב-שימושי, מסיליקון רפואי, עם עוצמה מתכווננת. אם יש התלבטות — צוות השירות שלנו ישמח לייעץ בדיסקרטיות מלאה.',
    },
    {
      id: 'faq-1',
      title: 'איך נשלחת ההזמנה?',
      body: 'כל ההזמנות נשלחות באריזה אטומה, ללא שם המותג או סימון חיצוני כלשהו. על שטר המשלוח מופיע שם ניטרלי בלבד.',
    },
    {
      id: 'faq-2',
      title: 'איך מנקים ומתחזקים את המוצר?',
      body: 'יש לשטוף במים פושרים וסבון עדין לפני ואחרי כל שימוש, לייבש היטב ולאחסן במקום יבש. אין להשתמש בחומרים ממיסים או באלכוהול.',
    },
    {
      id: 'faq-3',
      title: 'האם אפשר להחזיר מוצר?',
      body: 'מטעמי היגיינה לא ניתן להחזיר מוצרים שנפתחו. מוצר באריזתו המקורית והסגורה ניתן להחזרה תוך 14 יום ממועד קבלתו.',
    },
    {
      id: 'faq-4',
      title: 'איזה חומר סיכוך מתאים?',
      body: 'עם מוצרי סיליקון יש להשתמש בחומר סיכוך על בסיס מים בלבד. חומר על בסיס סיליקון עלול לפגוע בפני השטח של המוצר.',
    },
  ],
  // Four feature panels (109:1576). The design repeats the same copy in all four and
  // alternates the image side: rows 1 and 3 have the photo on the LEFT, 2 and 4 right.
  features: featureImages.map(function (image, i) {
    return {
      id: 'f' + (i + 1),
      title: 'משמש גם כקרם לטיפוח העור ולהרגעה',
      line: 'עד 25% הנחה על אביזרים לזוגות במבצע ולנטיין',
      image: image,
      imageLeft: i % 2 === 0,
    };
  }),
};

// Section headings are Futurism 80/0.9 with the trailing words in accent red —
// `lead` renders white (Futurism Light), `accent` renders #FF3B3B (Futurism Regular).
export const sections = {
  // kickerWidth is the design's own text-box width — 211px for the pleasure grid
  // (109:313), 236px for the blog (109:654). They are not the same, and hardcoding one
  // of them made the other one re-wrap.
  pleasure: {
    lead: 'למצוא את',
    accent: 'ההנאה שלך',
    kicker: ['בלי תוויות. בלי שיפוטיות.', 'רק מה שמרגיש לך נכון.'],
    kickerWidth: 211,
  },
  bestSellers: { lead: 'הנמכרים', accent: 'ביותר' },
  worthAttention: { lead: 'מוצרים ששווים', accent: 'תשומת הלב' },
  youMayLike: { lead: 'מוצרים שאולי', accent: 'תאהבו' },
  upsell: { lead: 'שדרוג', accent: 'רכישה' },
  blog: { lead: 'שווה', accent: 'לדעת', kicker: ['תשובות לשאלות שכולם', 'ביישנים מדי כדי לשאול'], kickerWidth: 236 },
  testimonials: { lead: 'מדברים עלינו' },
};

// Homepage blog cards (109:657) — the design fills these with lorem, not Hebrew:
// date 12/#818181, title 18 Medium white, excerpt 14/#C5C5C5, red "Read more".
const BLOG_EXCERPT =
  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna. adipiscing elit. Sed do eiusmod tempor incididunt ut labore et ...olore magna.  tempor incididunt ut labore et dolore magna.';

export const blogPosts = blogImages.map(function (image, i) {
  return {
    id: 'post-' + (i + 1),
    date: 'Sep 21, 2026',
    title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor',
    excerpt: BLOG_EXCERPT,
    cta: 'Read more',
    image: image,
  };
});

// The wide single card on the Category and Product pages carries the real copy.
export const blogFeature = {
  date: '21/09/2026',
  title: '5 דרכים לבצע קונילינגוס בצורה הטובה ביותר',
  excerpt:
    'במשך חודשים ערכנו סקר בנושא מין אוראלי לנשים, והתוצאות מראות שכמעט 75% מהנשים חוו אורגזמה משמעותית ביותר קצרה יותר.',
  cta: 'קרא עוד',
  image: blogImages[1],
};

// Two review cards in the design (109:701, 109:720), portrait beside the copy.
// The design's quote (109:718) is the lorem block three times over — and the third
// repeat is joined with NO space ("...magna.Lorem ipsum..."), which is why it wraps the
// way it does. Reproduced verbatim: at two repeats the quote came up short and every
// line below it sat too high.
const TESTIMONIAL_QUOTE =
  PLACEHOLDER_DESC + ' ' + PLACEHOLDER_DESC + PLACEHOLDER_DESC;

export const testimonials = [
  { id: 't1', name: 'Sonia Hamilton', date: '21/09/2026', quote: TESTIMONIAL_QUOTE, portrait: portraitImages[0] },
  { id: 't2', name: 'Edward Piastri', date: '21/09/2026', quote: TESTIMONIAL_QUOTE, portrait: portraitImages[1] },
];

// Section 109:744 — centred, 90px two-tone heading over a red radial glow.
// No wordmark and no CTA in the design.
export const brandStory = {
  lead: '40 שנים בחושך',
  accent: 'עכשיו הדלקנו את האורות.',
  body: 'רד פוינט מנחה עונג כבר ארבעה עשורים, וסיימנו ללחוש על זה. עונג הוא טבעי, חושני, ולכל גוף. בלי בושה. בלי פשרות זולות. רק אוצרות יוצאת דופן, ליווי מקצועי ודיסקרטיות מלאה בכל הזמנה.',
};

// 109:755 — the title mixes Futurism Light and Regular within one line.
export const newsletter = {
  titleLead: 'הצטרפו לרשימת',
  titleRest: 'התפוצה הדיסקרטית',
  subtitle: 'הישארו מעודכנים באמצעות הדוא״ל. ללא ספאם.',
  placeholder: 'כתובת אימייל',
  cta: 'הירשמו',
};

export const footer = {
  brand: 'Redpoint Sex Shop',
  tagline: 'חנות אביזרי המין המובילה והוותיקה בישראל',
  categoriesHeading: 'Website Categories',
  contactHeading: 'Contact Info.',
  phoneLabel: 'טלפון',
  phone: 'abcd',
  copyright: '2026 Redpoint All rights reserved.',
};
