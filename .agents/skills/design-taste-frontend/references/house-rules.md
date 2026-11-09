# House Rules & Anti-Slop Prohibitions

These rules hold on every build, regardless of the chosen direction or sector. They enforce an authentic, human-designed aesthetic by eliminating generative AI clichés, fake polish, and arbitrary ornaments.

---

## 1. The Prohibitions Table

| Rule | What to Do Instead |
| :--- | :--- |
| **Headlines: two lines or fewer, at every width.** Applies to every `h1`–`h6`, and the main headline of the first screen regardless of HTML tag. | Cut words first. Then widen the measure. Adjust type size only as the last resort. |
| **A clear title on the first screen** in the page's one `h1`, plus a heading on every section. | Clearly state what this is — the name with what it does, the product with what it is for, or the event with its date and place. |
| **Equal outer margins; no section huddled on one side** with the other half of the screen left vacant. | Close every void with content on its far side — the section's picture, a second column, a list, or a figure. |
| **No blank stretches** taller than approximately half a screen. | Use consistent, standard section gaps (120–240px on desktop). A deliberate rest is a placed picture or a field of colour, permitted once per page at most. |
| **Statements are paragraphs.** Intro sentences or quotations set large must be marked up as `<p>` or `<blockquote>`, kept at statement size (≤36px desktop, ≤24px phone), and capped around 25 words (three lines). | Above 36px, text functions as a headline and the strict two-line limit applies. In blind testing, readers perceive a 42px sentence over four lines as an uncomfortably long headline. |
| **No eyebrows.** No small uppercase labels above headings, no pill badges above headlines, no "New ·" chips. | Integrate the context directly into the headline or first sentence, or omit it completely. |
| **No monospace outside real code blocks.** | Use the chosen primary text typeface. Use tabular numbers (`font-variant-numeric: tabular-nums`) where numbers must align. |
| **No index numbering.** No `01 / 02 / 03`, no `(001)`, no `"Step 2"`. | Establish sequence through natural layout hierarchy, scale, or copy. Use semantic `<ol>` if sequential ordering is strictly required. |
| **No thin rules.** No hairline dividers, no 1px outlined card boxes, no grid lines, crosshairs, or crop marks. | Structure layout using negative space, tone steps in background surfaces, changes in scale, or solid filled shapes. |
| **No gradients.** Not as backgrounds, not on buttons, not in text fills. | Use flat color fields or authentic photography. A neutral scrim under text on a photo is the only exception. |
| **No shadows on things at rest.** No decorative glows. | Use subtle tone steps between background layers. Reserve drop shadows exclusively for elements that genuinely float (dropdown menus, modal dialogs). |
| **No assistant palette.** No yellow-cream ground paired with terracotta/rust; no generic violet or indigo. | Follow the dealt palette derived from the subject's authentic materials and context. |
| **No tiny uppercase letter-spaced labels.** | Use sentence case at a readable body size. |
| **No icon-topped feature cards, no rows of identical cards.** | Use distinct section blueprints with asymmetrical or editorial layouts. |
| **No glass.** No `backdrop-filter: blur()`, no floating frosted pill navigations. | Use a clean, solid, grounded navigation bar — wordmark, 3–5 direct links, and one primary action. |
| **No emojis, no gradient blobs, no decorative shapes, no fake dashboards of grey bars.** | Show a real photograph, an authentic functional interface fragment, or clean typography. |
| **No styled accent word in a headline** (no italicized serif, coloured, or underlined single word). | Keep headlines in a single face, weight, and colour. The phrasing itself must carry the emphasis. |
| **Two typefaces at most,** usually one. | Strictly stick to the dealt typography system. |

---

## 2. Protected Zones (Exceptions)

Three areas maintain their own native conventions and are not flagged:
1. **Real Code**: Code within `<pre>` and `<code>` blocks (monospace is appropriate).
2. **Tabular Data & Forms**: Native HTML `<table>` elements and input fields (functional boundary lines are permitted).
3. **Real Interface Fragments**: Embedded UI components whose root element carries `data-ui` (representing a real product's internal borders, menus, and controls).

---

## 3. Rationale and Empirical Evidence

These rules are not arbitrary dogma; they are grounded in empirical analysis of 247 curated award-winning sites (Awwwards Site of the Day, One Page Love):

- **Feature card grids**: Sites scoring 7.5+ used icon feature cards only **1%** of the time, compared to **28%** for sites scoring under 6.5.
- **Pill badges / Eyebrows**: Present in only **14%** of top sites vs **34%** in lower-scoring sites.
- **Gradients**: Present on **22%** of top sites vs **52%** of lower-scoring sites.
- **Drop shadows at rest**: Used on **24%** of top sites vs **48%** of lower-scoring sites.
- **Tiny tracked uppercase labels**: Seen on **16%** of top sites vs **57%** of lower-scoring sites.

Monospace labels, index numbers, and hairline grids became heavily overused by AI generators imitating design tropes, causing modern designs to look like costume rather than craft. Removing ornament creates breathing room. Fill that space with **scale** (make key content larger), **complete information**, **authentic imagery**, or **confident fields of solid color**.
