/* Dev-only: create file:// previewable copies of docs/ pages in docs/_preview
   with absolute asset paths and sibling cross-links so pages render off the filesystem. */
const fs = require('fs'), path = require('path');
const DOCS = path.join(__dirname, '..', 'docs');
const PREV = path.join(DOCS, '_preview');
fs.mkdirSync(PREV, { recursive: true });
const assetBase = 'file:///' + DOCS.replace(/\\/g, '/') + '/assets/';
for (const f of fs.readdirSync(DOCS)) {
  if (!f.endsWith('.html')) continue;
  let html = fs.readFileSync(path.join(DOCS, f), 'utf8');
  html = html.split('/assets/').join(assetBase);
  html = html.replace(/href="\/([a-z0-9-]*\.html)"/g, 'href="$1"');
  html = html.replace(/href="\/"/g, 'href="index.html"');
  fs.writeFileSync(path.join(PREV, f), html);
}
console.log('preview written to', PREV);
