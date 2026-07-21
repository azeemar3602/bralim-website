#!/usr/bin/env node
/**
 * BRALIM static site builder.
 * Assembles pages from partials + per-page content into /docs (GitHub Pages source).
 *
 * Templating:
 *   {{> partialName }}      -> injects src/partials/partialName.html
 *   {{ key }}               -> replaced by page front-matter value or site.json value
 *   Front-matter block at the very top of each page file, between --- lines, in KEY: value form.
 */
const fs = require('fs');
const path = require('path');

const ROOT = __dirname;
const SRC = path.join(ROOT, 'src');
const PAGES = path.join(SRC, 'pages');
const PARTIALS = path.join(SRC, 'partials');
const OUT = path.join(ROOT, 'docs');

const site = JSON.parse(fs.readFileSync(path.join(SRC, 'data', 'site.json'), 'utf8'));

function read(p) { return fs.readFileSync(p, 'utf8'); }

function parseFrontMatter(raw) {
  const meta = {};
  let body = raw;
  const m = raw.match(/^---\s*\r?\n([\s\S]*?)\r?\n---\s*\r?\n?/);
  if (m) {
    m[1].split(/\r?\n/).forEach(line => {
      const idx = line.indexOf(':');
      if (idx > -1) {
        const k = line.slice(0, idx).trim();
        const v = line.slice(idx + 1).trim();
        if (k) meta[k] = v;
      }
    });
    body = raw.slice(m[0].length);
  }
  return { meta, body };
}

function injectPartials(str, ctx) {
  return str.replace(/\{\{>\s*([\w.-]+)\s*\}\}/g, (_, name) => {
    const file = path.join(PARTIALS, name + '.html');
    if (!fs.existsSync(file)) throw new Error('Missing partial: ' + name);
    return render(read(file), ctx);
  });
}

function resolveKey(obj, key) {
  if (Object.prototype.hasOwnProperty.call(obj, key)) return obj[key];
  if (key.indexOf('.') > -1) {
    return key.split('.').reduce((acc, part) => (acc && acc[part] !== undefined ? acc[part] : undefined), obj);
  }
  return undefined;
}

function interpolate(str, ctx) {
  return str.replace(/\{\{\s*([\w.-]+)\s*\}\}/g, (whole, key) => {
    let v = resolveKey(ctx, key);
    if (v === undefined) v = resolveKey(site, key);
    return v === undefined ? whole : v; // leave untouched so a missing key is visible
  });
}

function render(str, ctx) {
  // partials first (they may contain interpolations resolved with same ctx), then interpolate
  return interpolate(injectPartials(str, ctx), ctx);
}

function navHtml(current) {
  return site.nav.map(item => {
    const active = item.href === current ? ' aria-current="page" class="is-active"' : '';
    const cls = item.cta ? ' class="nav-cta"' : '';
    return `<li><a href="${item.href}"${active}${cls}>${item.label}</a></li>`;
  }).join('\n');
}

function build() {
  if (fs.existsSync(OUT)) fs.rmSync(OUT, { recursive: true, force: true });
  fs.mkdirSync(OUT, { recursive: true });

  // copy assets
  copyDir(path.join(ROOT, 'assets'), path.join(OUT, 'assets'));

  // CNAME + nojekyll for GitHub Pages
  fs.writeFileSync(path.join(OUT, 'CNAME'), site.domain + '\n');
  fs.writeFileSync(path.join(OUT, '.nojekyll'), '');

  const shell = read(path.join(PARTIALS, 'shell.html'));
  const pageFiles = fs.readdirSync(PAGES).filter(f => f.endsWith('.html'));
  const built = [];

  for (const file of pageFiles) {
    const raw = read(path.join(PAGES, file));
    const { meta, body } = parseFrontMatter(raw);
    const outName = meta.slug ? meta.slug + '.html' : file;
    const current = '/' + (outName === 'index.html' ? '' : outName);

    const ctx = Object.assign({}, site, meta, {
      NAV: navHtml(current),
      YEAR: new Date().getFullYear(),
      TITLE: meta.title || site.name,
      DESCRIPTION: meta.description || site.description,
      OG_TITLE: (meta.title ? meta.title + ' — ' : '') + site.name,
      CANONICAL: 'https://' + site.domain + current,
    });

    const content = render(body, ctx);
    ctx.CONTENT = content;
    let html = render(shell, ctx);

    // Make internal links relative so the site works at any base path
    // (GitHub Pages project URL AND the custom-domain root). All pages are at
    // one directory level, so a root-absolute path becomes relative by dropping
    // the leading slash. Absolute https:// URLs (canonical/OG) are untouched.
    html = html
      .replace(/(href|src)="\/"/g, '$1="index.html"')      // home link
      .replace(/(href|src)="\/(?!\/)/g, '$1="');            // strip leading slash

    fs.writeFileSync(path.join(OUT, outName), html);
    built.push(outName);
  }

  // simple 404
  const notFound = built.includes('404.html');
  if (!notFound) {
    // generated from home shell if a 404 page file wasn't provided
  }

  console.log('Built ' + built.length + ' pages -> docs/');
  built.sort().forEach(b => console.log('  • ' + b));
}

function copyDir(from, to) {
  if (!fs.existsSync(from)) return;
  fs.mkdirSync(to, { recursive: true });
  for (const entry of fs.readdirSync(from, { withFileTypes: true })) {
    const s = path.join(from, entry.name);
    const d = path.join(to, entry.name);
    if (entry.isDirectory()) copyDir(s, d);
    else fs.copyFileSync(s, d);
  }
}

build();
