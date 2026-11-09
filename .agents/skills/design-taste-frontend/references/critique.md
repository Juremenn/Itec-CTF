# Visual Critique & Juror Review Rubric

Code passing automated linters does not ensure that the resulting page looks aesthetically compelling. This document outlines the visual critique workflow and evaluation rubric.

---

## 1. The Screenshot Review Process

Do not evaluate layouts exclusively in code. Always review rendered screenshots across three canonical breakpoints:
- **Desktop**: 1440px
- **Tablet**: 768px
- **Mobile**: 390px

> **Core Principle**: If a visual defect is visible in the screenshot, it is real — regardless of whether the CSS is technically valid or cleanly organized.

---

## 2. The Critique Review Passes

When reviewing rendered viewports, systematically check the following:

1. **The 5-Second Stranger Test**: Can a first-time visitor reading the first screen for five seconds clearly identify what this is, who it is for, and what action to take?
2. **Headline Scale & Wrapping**: Does the primary display headline wrap to at most two lines? Does it stand out distinctly from body copy with a bold, deliberate scale difference?
3. **Outer Margins & Symmetry**: Are the left and right outer margins strictly identical across the page? Is anything awkwardly clinging to one screen edge?
4. **Negative Space Balance**: Are void areas intentional and framed by opposing elements, or do they feel like accidental gaps left by missing content?
5. **Section Rhythm**: Are section dividers distinct (120–240px desktop gap)? Do consecutive sections showcase contrasting compositions?
6. **Elimination of Tells**: Are there any lingering AI tells (eyebrow badges, icons in tinted squares, hairline card borders, gradients, generic icons)?
7. **Typography Purity**: Are there strictly two or fewer font families? Is monospace strictly confined to code, data keys, and hashes?
8. **Contrast & Legibility**: Is body copy readable in full ink? Do all secondary text elements pass WCAG AA contrast against their immediate surface?
9. **Interactive Affordance**: Are buttons and primary links immediately recognizable as clickable elements?
10. **Mobile Integrity**: Does the 390px mobile view feel like a thoughtful vertical composition rather than a cramped desktop shrink?

---

## 3. The 3-Round Iteration Loop

1. **Capture**: Capture full-page screenshots at 1440px, 768px, and 390px.
2. **Review**: Evaluate the screenshots against the review passes above.
3. **Prioritize Top 3**: Identify the top three highest-impact visual defects.
4. **Fix & Re-check**: Apply code adjustments, re-capture screenshots, and verify improvements. Repeat for a maximum of 3 focused rounds.
