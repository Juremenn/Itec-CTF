---
name: design-taste-frontend
description: >-
  tasteskill v2, the anti-slop frontend skill. Art direction for websites, distilled from a study of
  247 curated gallery sites (One Page Love, Awwwards Sites of the Day). Deals researched directions
  (idea, composition, type, palette, photographs, section blueprints) from a library, enforces a strict
  no-slop house style with a real-browser inspector, and runs a look-and-fix loop on screenshots. Use
  this whenever you build, design, redesign, restyle, polish or review a landing page, marketing site,
  portfolio, product page, one-pager, hero or any public-facing web page, in any stack (HTML/CSS, React,
  Next.js, Vue, Svelte, Astro, Tailwind) — also when the user says a page looks generic, templated,
  "AI-made", sloppy, or asks to make it look designed, premium, award-level or less like every other
  site. Also for app screens: dashboards, admin and analytics views, settings, inboxes and other screens
  people work in, with their own run and rules.
---

# tasteskill v2: Anti-Slop Frontend Art Direction

A page built by default AI patterns tends to look like a template: a centered 3-line headline, a pill label above it, a 3-column row of icon cards, soft gradients, and thin hairline borders.

This skill eliminates AI defaults through authentic art direction, empirical design laws, a strict no-ornament house style, and visual screenshot reviews.

---

## Modular References

To optimize token context, detailed guidelines are organized into modular reference files:

| Reference Document | Contents & When to Read |
| :--- | :--- |
| [house-rules.md](./references/house-rules.md) | Strict prohibitions (no eyebrows, no gradients, no icon cards, no shadows at rest) and statistical data. |
| [laws-of-the-craft.md](./references/laws-of-the-craft.md) | Core rules for Typography (scale, tracking, leading), Spacing (margins, section rhythm), Color, Imagery, and Components. |
| [app-screens.md](./references/app-screens.md) | Dedicated guidelines for screens worked in: dashboards, admin, settings, telemetry, and `data-ui` interface fragments. |
| [copy.md](./references/copy.md) | The Claim & Proof argument deck, the strict 2-line headline rule (~32 chars), and content standards. |
| [critique.md](./references/critique.md) | The 10-point visual screenshot critique rubric across 1440px, 768px, and 390px viewports. |
| [motion.md](./references/motion.md) | 4-pillar motion layer: signature move, staggered section entrances, control feedback, and reduced motion. |
| [special-cases.md](./references/special-cases.md) | Preserving existing brand tokens, staged redesign workflows, single components, and offline/tool fallbacks. |

---

## 1. Page or App Screen

Determine the primary nature of the interface before beginning:

- **Public Page (read & scrolled)**: Landing pages, marketing sites, portfolios, event pages. Follow **The Run** below.
- **App Screen (worked in)**: Dashboards, admin portals, analytics views, settings, inboxes, and tables. Follow [references/app-screens.md](./references/app-screens.md).
- **Hybrid**: When a landing page demonstrates a product, build embedded interface fragments according to [app-screens.md](./references/app-screens.md) tagged with `data-ui`.

---

## 2. The 10-Step Run

Follow these steps in sequence:

### Step 1: Inventory the Subject
- Read the brief to find authentic assets: the product, history, numbers, physical materials, and unique constraints.
- Make an inventory of 8–12 distinct facts unique to this subject (door numbers, founding year, specific materials, location, ritual hours).
- Avoid generic category tropes.

### Step 2: Write the Argument
- Structure the page as claims backed by proof (see [references/copy.md](./references/copy.md)).
- One claim per section. If a section lacks a distinct claim, delete it.

### Step 3: Direction & Geometry
- Establish the visual direction: select sector, structural layout, tone, and section sequence.
- Ground the palette in authentic material colors of the subject (one ground, one ink, one accent).

### Step 4: Find the Idea
- Fill this formula: `On this page, [something that belongs to the subject] becomes [the device the layout calls for]`.
- **Competitor Test**: If the idea fits a direct competitor, it is too generic — refine it until it is unique to this subject.
- **One Live Thing**: Include one interactive feature made from the subject's content (a calculator, live status, previewer, or simulator).

### Step 5: Write the Words
- Prepare the copy deck before markup.
- **Headlines**: Strictly ≤ 2 lines at every breakpoint (~32 characters safe limit).
- **Hero Structure**: Display headline + 1 direct supporting sentence (32–48px below) + 1 named primary action button.
- Provide full, rich content rather than thin placeholders. Never fabricate customer testimonials or press quotes.

### Step 6: Build It, Still
Build the static page completely before adding motion:
1. **Tokens**: Set CSS custom properties for palette and typography (see [references/laws-of-the-craft.md](./references/laws-of-the-craft.md)).
2. **Hero Section**: Build large display headline and primary media first.
3. **Sections**: Give neighboring sections distinct compositions. Avoid card grids.
4. **Imagery**: Prioritize authentic photographs or high-fidelity code-drawn graphics over stock icons. No graphics over headlines.
5. **Frame & Margins**: Keep left and right outer margins equal; maintain 120–240px desktop section gaps.
6. **Enforce House Rules**: Verify against [references/house-rules.md](./references/house-rules.md).

### Step 7: Automated Gate Checks
- Scan for banned patterns (eyebrows, gradients, hairline cards, emoji in technical copy).
- Verify responsive viewports (1440px desktop, 768px tablet, 390px mobile).

### Step 8: Visual Screenshot Critique
- Capture screenshots at 1440px, 768px, and 390px.
- Evaluate against the 10-point critique rubric in [references/critique.md](./references/critique.md).
- Identify and resolve the top 3 visual defects. Repeat up to 3 focused cycles.

### Step 9: Layer Motion
- Add the motion layer (see [references/motion.md](./references/motion.md)):
  1. One signature move.
  2. Staggered section entrances (60–90ms stagger, 16–32px translation, opacity).
  3. Responsive hover/active/focus feedback on all controls.
  4. One moving visual element (ambient loop or subtle photo scale).
- Enforce strict `prefers-reduced-motion` fallbacks.

### Step 10: Final Review & Handoff
Verify before completion:
- Zero lint or visual gate failures across desktop, tablet, and mobile.
- The 5-second stranger test passes.
- Every section features a clear heading, balanced margins, and rich information density.
- All external image sources and licenses are documented and credited.
