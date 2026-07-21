/* Generates robots.txt and sitemap.xml in /docs after the main build. */
const fs = require('fs');
const path = require('path');
const ROOT = path.join(__dirname, '..');
const site = JSON.parse(fs.readFileSync(path.join(ROOT, 'src', 'data', 'site.json'), 'utf8'));
const OUT = path.join(ROOT, 'docs');
const base = 'https://' + site.domain;

const pages = fs.readdirSync(OUT).filter(f => f.endsWith('.html') && f !== '404.html');
const today = new Date().toISOString().slice(0, 10);

const urls = pages.map(p => {
  const loc = p === 'index.html' ? base + '/' : base + '/' + p;
  const priority = p === 'index.html' ? '1.0' : '0.7';
  return `  <url>\n    <loc>${loc}</loc>\n    <lastmod>${today}</lastmod>\n    <changefreq>weekly</changefreq>\n    <priority>${priority}</priority>\n  </url>`;
}).join('\n');

const sitemap = `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n${urls}\n</urlset>\n`;
fs.writeFileSync(path.join(OUT, 'sitemap.xml'), sitemap);

const robots = `User-agent: *\nAllow: /\n\nSitemap: ${base}/sitemap.xml\n`;
fs.writeFileSync(path.join(OUT, 'robots.txt'), robots);

console.log('Wrote sitemap.xml (' + pages.length + ' urls) and robots.txt');
