/** @type {import('tailwindcss').Config} */
// Tokens read from the RED POINT Figma file (Kx2t5PDOZvCVbhCeAnqFgl).
module.exports = {
  content: [
    './app/**/*.{js,jsx}',
    './components/**/*.{js,jsx}',
  ],
  theme: {
    extend: {
      colors: {
        bg: '#0C0C0C',
        panel: '#111111',
        surface: '#252525',
        well: '#000000',
        accent: '#FF3B3B',
        body: '#E6E6E6',
        muted: '#818181',
        navlink: '#D7D7D7',
        // Every category owns a colour — it borders its nav pill and tints its card CTA.
        cat: {
          red: '#FF3B3B',     // חומרי סיכוך
          green: '#36E07A',   // פטיש ו-BDSM
          yellow: '#FFD13B',  // ביגוד ולונז'ארי
          purple: '#A45CFF',  // חוויה אנאלית
          orange: '#FF8A2B',  // צעצועי סקס
          pink: '#FF3DD1',    // לזוגות
          cyan: '#25D9F5',    // לגברים
          magenta: '#FF4FA8', // לנשים
        },
        star: '#FFD13B',
      },
      borderRadius: {
        pill: '100px',
      },
      fontFamily: {
        // Both faces are real now, self-hosted from public/fonts/ (see globals.css).
        heading: ['Futurism', 'sans-serif'],
        body: ['Google Sans', 'sans-serif'],
      },
      maxWidth: {
        shell: '1440px',
      },
      spacing: {
        gutter: '60px',
      },
    },
  },
  plugins: [],
};
