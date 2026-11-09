# Laws of the Craft

These core principles and empirical metrics are distilled from the study of 247 award-winning websites (One Page Love, Awwwards Site of the Day).

---

## 1. Typography

- **Scale Ratios**: Two or three sizes do the visible heavy lifting — one very large display size, one readable body size, and minimal intermediate sizes.
  * Median headline-to-body size ratio across top-tier sites is **4.3:1** (top sites reach a median of **8.4vw**, whereas generic sites stall around **5.0vw**).
  * A page should have no more than **five distinct font sizes** total.
- **Display Typography**:
  * Letter-spacing (tracking): `-0.02em` to `-0.05em`.
  * Line-height (leading): tight, between `0.85` and `1.05`.
- **Body Typography**:
  * Font-size: `16px` to `20px`.
  * Line-height: `1.45` to `1.6`.
  * Measure (line width): `45` to `75` characters per line.
- **Font Families**: Single font family is preferred (used by over a third of top sites); maximum of two families allowed.
- **Alignment**: Left-aligned by default. Centre alignment is reserved for at most one deliberate, high-impact moment on the entire page.

---

## 2. Space & Geometry

- **Unified Grid & Margins**: A single master grid with one pair of consistent, tight outer margins (`16px` to `32px`). Large elements bleed to the edges. Text blocks align to two or three shared left margins across the whole page.
- **Framed Asymmetry**: Asymmetry must live *inside* the grid, never in the frame itself. The two outer margins are strictly equal. An off-centre heading must always be balanced by content on the far side of the grid.
- **Section Rhythm**: The gap between sections must be substantially larger than any gap inside a section:
  * Desktop section spacing: `120px` to `240px` (study median is ~`240px`).
- **Section Variety**: Neighbouring sections must not share identical layouts. Card grids are strictly avoided.

---

## 3. Colour

- **The Triad**: One dominant ground (background), one ink (text), and one accent colour. The accent colour is applied to primary actions and at most one other focal point.
- **Full Ink Running Text**: Body copy must always be set in full ink contrast. Setting body text in muted or washed-out grey makes the page feel unrendered or unfinished.
- **Tone Steps**: Visual separation between surfaces must be achieved via subtle background tone steps (e.g. 3–5% lightness shift), never via border rules or drop shadows.
- **Field Color Shifts**: Sections can transition the entire background field color cleanly (e.g., light hero into deep dark story section), without gradient fades.

---

## 4. Imagery

- **Dominance Over Clutter**: One large, dominant visual beats six fragmented thumbnails. On top-ranked sites, hero media covers 50% or more of the initial viewport in **68%** of cases.
- **Consistent Treatment**: Maintain uniform aspect ratios, color grading, and square corners (73% of studied sites keep image corners crisp and square).
- **Authenticity First**: Real imagery of the subject's world (found in open collections or captured directly) trumps abstract placeholders, icon illustrations, or decorative SVG blobs.
- **Proper Licensing**: Found images must be downloaded into the project, credited in the footer, and never falsely represented as proprietary client assets.
- **Clean Text Clearance**: Media or background graphics must never overlap or compromise the legibility of headlines or statements.

---

## 5. Components

- **Buttons**: Flat styling with a single solid fill. Choose either crisp square corners (`0px` radius) or fully rounded pill corners (`9999px`), decided once across the design (52% square, 23% pill in top sites). Avoid the generic 8–16px soft-corner default. Strong heroes frequently require zero or only one action button.
- **Navigation**: Clean, uncluttered bar consisting of a distinct wordmark, 3 to 5 plain navigation links, and one clear primary action. No heavy chrome or floating glass capsules.
- **Form Controls & Inputs**: Form inputs must clearly signal their affordance through clean labels and positioning. Constraints (allowed formats, file sizes, character limits) should be visible beside the input and connected via `aria-describedby`.
- **Footers**: The footer serves as a deliberate closing visual statement (often displaying the full brand wordmark across the viewport), rather than a bloated, cluttered link directory.
