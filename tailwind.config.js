/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './patterns/**/*.php',
    './blocks/**/*.{php,js,json}',
    './templates/**/*.html',
    './parts/**/*.html',
    './src/css/**/*.css',
  ],
  safelist: [
    { pattern: /^bg-{{SITE_PREFIX}}-/ },
    { pattern: /^text-{{SITE_PREFIX}}-/ },
    { pattern: /^border-{{SITE_PREFIX}}-/ },
  ],
  theme: {
    extend: {
      colors: {
        '{{SITE_PREFIX}}': {
          primary:   '#1B2A6B',
          primaryDk: '#0F1842',
          accent:    '#D42027',
          accentDk:  '#B31A20',
          bg:        '#FAF8F5',
          surface:   '#EFEAE1',
          line:      '#E5DED0',
          gray:      '#6B6660',
          ink:       '#32302D',
          white:     '#FFFFFF',
        },
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        soft:   '0 20px 40px -20px rgba(0,0,0,0.12)',
        softer: '0 10px 30px -15px rgba(0,0,0,0.10)',
        lift:   '0 28px 60px -30px rgba(0,0,0,0.28)',
        cta:    '0 10px 24px -12px rgba(0,0,0,0.55)',
      },
      borderRadius: {
        xl2: '18px',
      },
      maxWidth: {
        content: '1280px',
        prose: '68ch',
      },
    },
  },
  plugins: [],
};
