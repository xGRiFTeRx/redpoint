import './globals.css';
import Header from '../components/Header';
import Footer from '../components/Footer';
import { CartProvider } from '../components/CartProvider';

// Both design faces are now real and self-hosted from public/fonts/ — see the
// @font-face rules in globals.css:
//   Futurism    (Light 300 / Regular 400) — headings + wordmark, full Hebrew coverage
//   Google Sans (300-700, incl. italic)   — body, nav, buttons, prices
// No fallback face is loaded; the stack degrades to system sans-serif only if a
// font file fails to fetch.

export const metadata = {
  title: 'Redpoint Sex Shop',
  description: 'Redpoint - Next.js reference build for Elementor widget conversion',
  // Pre-launch client preview: keep it out of search results.
  robots: { index: false, follow: false, nocache: true },
};

export default function RootLayout({ children }) {
  return (
    <html lang="he" dir="rtl">
      <body className="bg-bg text-body font-body">
        <CartProvider>
          <Header />
          <main>{children}</main>
          <Footer />
        </CartProvider>
      </body>
    </html>
  );
}
