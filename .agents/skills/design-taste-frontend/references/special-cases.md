# Special Cases & Environment Fallbacks

Workflows and adaptations for established codebases, pre-existing brand systems, partial UI tasks, and constrained runtime environments.

---

## 1. Existing Brands & Established Design Systems

When working with an organization that already has strict brand guidelines:
- **Lock Type & Palette**: Preserve existing brand colors and typestacks. Map existing brand tokens directly to the ground, ink, and accent roles.
- **Adopt Editorial Composition**: Apply the skill's composition, whitespace rules, two-line headline limit, and section rhythm while honoring the brand's assets.
- **Brand Device Conflicts**: If an official brand logo or badge relies on an otherwise prohibited element (e.g. a legacy brand gradient), retain the official asset, document the exception in the final report, and pass `--allow <rule>` to the inspector.

---

## 2. Redesign, Restyle, or Review Workflows

When modernizing an existing page or application screen:

1. **Capture Baseline ("Before")**:
   - Inspect the current deployment and capture baseline screenshots across viewports.
   - Inspect existing technology stack (framework, CSS setup, routing, state management).
   - Identify assets and functionalities that must be preserved intact.
2. **Review-Only Mode**:
   - If requested only to critique, identify the top three highest-leverage visual and structural issues using the critique rubric.
3. **Preserve Foundation & Content**:
   - Avoid unnecessary framework rewrites. Retain the existing build tooling and backend wiring.
   - Retain authentic content and functional forms.
4. **Staged Implementation by Priority**:
   - **Step 1: Typography**: Establish strict 4:1 scale hierarchy, tight display leading, and 2-line headline constraints.
   - **Step 2: Surface & Palette**: Eliminate muddy gradients and establish clear ground, ink, and accent tokens.
   - **Step 3: Geometry & Frame**: Align outer margins, introduce 120–240px section rhythm, ensure clear headings.
   - **Step 4: States & Components**: Replace generic icon cards and pill chips with custom editorial layouts.
5. **Validation ("After")**:
   - Re-capture viewports at 1440px, 768px, and 390px. Compare before and after side-by-side to verify improvements.

---

## 3. Single Component or Section Builds

When requested to create or improve a single component rather than a full page:
- Match surrounding design variables and surface colors.
- Enforce house rules strictly (no unneeded border lines, no icon-box crutches, proper focus/active states).
- Verify responsiveness across viewports.

---

## 4. Forms & Backend Disclaimers

- **No Mock Payments**: Never simulate fake payment forms or credit card inputs. For e-commerce flows, point action buttons to `#checkout` and note the required backend integration in the handoff report.
- **Contact Forms**: Route actions to explicit email triggers (`mailto:` with prefilled subject/body) or explicit API endpoints.

---

## 5. Environment & Tooling Fallbacks

- **No Headless Chrome or Node 22**:
  * If headless browser inspection tools are unavailable, capture screenshots using available browser tools or manual previews.
  * Evaluate visual quality directly using the visual critique rubric.
- **Offline / Restricted Network**:
  * If open image archives (Openverse, Wikimedia) cannot be reached, generate SVG graphic assets directly in code or use high-contrast typographic treatments. Note the constraint in the final report.
