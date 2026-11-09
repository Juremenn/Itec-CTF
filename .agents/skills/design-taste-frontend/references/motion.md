# Motion Layer & Micro-Interactions

Motion must be purposeful, tactile, and responsive. A page that requires motion to look finished is fundamentally flawed; build and stabilize the static design first, then layer motion systematically.

---

## 1. The Four Pillars of the Motion Layer

1. **The Signature Move**:
   - One high-impact, custom motion sequence tailored to the subject's theme (e.g. a scroll-linked perspective tilt, a reveal curtain, or an interactive canvas interaction).
2. **Staggered Section Entrances**:
   - As each section scrolls into the viewport, animate its child elements individually:
     * Heading enters first.
     * Body copy enters second (delayed by 60–90ms).
     * Imagery/controls enter third (delayed by another 60–90ms).
   - Use subtle translations: `16px` to `32px` translateY coupled with opacity transition (`0 → 1`).
   - Trigger once; never reset or hide content if JavaScript is disabled or fails.
3. **Interactive Control Feedback**:
   - Provide distinct visual feedback on every button, link, and interactive widget:
     * Hover: subtle scale (`scale(1.02)`) or background tone change.
     * Active/Press: slight depression (`scale(0.98)` or slight inset).
     * Focus: clear 2px outline with high visibility offset.
4. **One Moving Visual Element**:
   - Incorporate at least one continuously active or scroll-reactive visual element:
     * A muted, looping ambient video background.
     * A subtle, slow panoramic drift/scale on an archival hero photograph.
     * A scroll-coupled parallax shift on hero imagery.

---

## 2. Technical Performance Guidelines

- **Hardware Acceleration**: Animate **only** `transform` and `opacity`. Never animate layout-triggering properties (`width`, `height`, `margin`, `top`, `left`, `padding`).
- **Timing Curves**: Use natural easing functions such as `cubic-bezier(0.16, 1, 0.3, 1)` (out-expo) for responsive, physical-feeling deceleration.
- **Accessibility & Reduced Motion**:
  * Every animation must respect user preferences:
    ```css
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
      }
    }
    ```
  * In reduced motion mode, retain full content visibility while eliminating spatial translation and movement.
