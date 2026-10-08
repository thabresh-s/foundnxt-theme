# FoundNXT WordPress Theme — Installation Guide

## Prerequisites
- WordPress 6.0+
- PHP 7.4+
- Your existing content, posts, and categories will carry over automatically

---

## Step 1 — Upload the Theme

1. Log in to **WordPress Admin** → Appearance → Themes
2. Click **Add New** → **Upload Theme**
3. Choose `foundnxt-theme.zip` and click **Install Now**
4. Click **Activate**

---

## Step 2 — Set Up Menus

1. Go to **Appearance → Menus**
2. Create a menu and assign it to **Primary Navigation**
3. Add: Home, About, Services, Articles, Blog, Contact Us
4. Add dropdowns under Articles: Startups, Funding, Valuation, Business

### Services Page
1. Create a new Page (e.g. titled "Services")
2. Under Page Attributes → Template, choose **Services Page**
3. Publish, then add it to your Primary Navigation menu

---

## Step 3 — Upload Logos

1. Go to **Appearance → Customize → Site Identity**
2. Upload your **light logo** (used on light background)
3. The theme supports both light and dark logos — use the same logo file; the dark mode toggle handles inversion via CSS filter if needed

---

## Step 4 — Customizer Settings

Go to **Appearance → Customize → FoundNXT Theme** and configure:

### Header Settings
- Enable/disable scrolling top bar
- Enable/disable dark mode toggle

### Homepage Settings
- Hero tagline (main H1 on homepage)
- Hero subtitle text
- Number of posts in hero grid

### Newsletter Banner
- Heading and subtext for the newsletter section
- Works with Mailchimp for WordPress (mc4wp) plugin automatically

### Brand & Colours
- Change primary indigo/violet accent colour if needed

### Footer Settings
- Footer tagline
- Copyright text

---

## Step 5 — Set Homepage

1. Go to **Settings → Reading**
2. Set **Your homepage displays** → **Your latest posts**
   _(Or create a static front page and set a posts page to /blog/)_

---

## Step 6 — Set Up Sidebars (Widgets)

1. Go to **Appearance → Widgets**
2. Configure:
   - **Blog Sidebar** — search, recent posts, categories, tags
   - **Article Sidebar** — newsletter signup, related resources
   - **Footer Columns 1–3** — categories, pages, recent posts
   - **Before Footer Banner** — for newsletter or promo block

---

## Step 7 — Recommended Plugins

Install these free plugins for full functionality:

| Plugin | Purpose |
|--------|---------|
| Yoast SEO / Rank Math | SEO meta, sitemap |
| Mailchimp for WP (mc4wp) | Newsletter form |
| WP Super Cache / LiteSpeed | Page caching |
| Smush / ShortPixel | Image optimisation |
| UpdraftPlus | Backups |

---

## Step 8 — Post Meta Fields (optional)

On each post edit screen, you can set custom fields:

| Meta Key | Value | Effect |
|----------|-------|--------|
| `fnx_read_time` | e.g. `5 min read` | Override auto read time |
| `fnx_featured` | `1` | Mark as featured (appears in trending) |
| `fnx_post_badge` | e.g. `🔥 Hot Take` | Badge shown on post hero |
| `fnx_key_takeaway` | Text | Shows yellow callout box at top of article |
| `fnx_toc_enabled` | `1` | Auto-generates Table of Contents |

Use **Advanced Custom Fields (ACF)** or the WordPress default Custom Fields panel to set these.

---

## Step 9 — Paste Blog Page Blocks

On your `/blog/` page, in the Block Editor:
1. Delete any existing blocks
2. Set page template to **Full Width (No Sidebar)**
3. Add 4 × **Custom HTML** blocks
4. Paste: `block-1-intro.html`, `block-4-what-we-cover.html`, `block-2-recent-posts.html`, `block-3-categories.html` in that order

---

## Migrating from Your Current Theme

Your posts, pages, categories, tags, images, menus, and widgets all carry over — nothing is lost. Only the visual presentation changes.

After activation:
1. Check your menus (Appearance → Menus) and re-assign to Primary Navigation
2. Check Widgets and reassign to new sidebar locations
3. Clear any caching plugin cache

---

## Dark Mode

Dark mode is built into the theme. It:
- Detects OS preference automatically on first visit
- Remembers user's manual choice via localStorage
- Toggle appears in the header (can be disabled in Customizer)
- All 4 blog blocks inherit the theme's dark/light mode automatically

---

## Support

For questions or customisations, contact: foundnxt.com/contact/
