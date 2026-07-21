# BRALIM Website

Official website for **BRALIM** — a non-profit community organisation bringing together Ugandans living in the Netherlands.

Live site: **https://bralim.org** (served via GitHub Pages from the `/docs` folder).

## Tech
- Static, hand-built HTML/CSS/JS — fast, SEO-ready, no runtime dependencies.
- A tiny Node build system assembles pages from reusable partials so the header, footer and layout stay consistent (the brief's "reusable templates" requirement).
- No framework, no build tooling to install — just Node to run the generator.

## Project structure
```
src/
  data/site.json        # site name, nav, contact, socials — edit here to change nav/footer everywhere
  partials/             # shell (full HTML doc), header, footer
  pages/                # one file per page (front-matter + body content)
scripts/
  gen-programs.js       # generates the 6 program detail pages from data
  gen-seo.js            # generates sitemap.xml + robots.txt
  serve.js              # local preview server
build.js                # assembles everything into /docs
docs/                   # BUILD OUTPUT — this is what GitHub Pages serves (do not edit by hand)
assets/                 # css, js, images (copied into docs/ on build)
```

## Build & preview locally
```bash
npm run build     # regenerate everything into /docs
npm run serve     # preview at http://localhost:4321
```
Always run `npm run build` after editing anything in `src/` or `assets/`, then commit the updated `/docs`.

## Editing content
- **Text**: edit the matching file in `src/pages/`. The block between the `---` lines at the top is page metadata (title, description); everything below is the page body.
- **Navigation / footer / contact details**: edit `src/data/site.json`.
- **Program pages**: edit the data array in `scripts/gen-programs.js`, then rebuild.
- **Images**: replace the `<div class="ph …">` placeholders with real `<img>` tags. Drop photos into `assets/img/` and reference them as `/assets/img/your-photo.jpg`. Keep descriptive `alt` text.

## Making the forms send real emails
The site is static, so forms need a form-handling service (no server required):
1. Create a free form endpoint at **Formspree** (formspree.io) or **Getform**.
2. On each `<form>` in `src/pages/`, add `data-endpoint="https://formspree.io/f/XXXX"`.
3. Rebuild. Submissions will be emailed to you; until then forms run in a friendly demo mode.

## Google Analytics
In `src/partials/shell.html`, uncomment the Google Analytics snippet and replace `G-XXXXXXXXXX` with your Measurement ID, then rebuild.

## Deployment (GitHub Pages)
The repo is configured to serve GitHub Pages from `main` → `/docs`. Pushing to `main` publishes automatically. The custom domain is set via `docs/CNAME`.
