# App Screens: Dashboards, Admin & Internal Tools

This document governs applications and views that users *work in* rather than merely scroll through. This includes dashboards, admin portals, analytics views, settings screens, billing interfaces, inboxes, filterable lists, and editors.

---

## 1. Page vs. App Screen Distinction

- **Marketing / Landing Pages**: Read and scrolled. They rely on high typographic contrast, wide open spacing (120–240px gaps), large editorial imagery, and narrative pacing.
- **App Screens**: Worked in. They demand high density, immediate utility, rapid scanning, keyboard accessibility, and state clarity.

---

## 2. App Screen House Rules

### What Is Permitted & Normal in App Screens
- **Quiet Borders**: Subtle, hairline 1px borders (`#1e2538` to `#262f47` on dark surfaces) are appropriate to define tables, data grids, and input panels.
- **Repeated Panels & Data Rows**: Uniform list rows and metric containers are necessary for predictable scanning.
- **Compact Labels**: Small, readable labels (sentence case) to identify data metrics, status tags, and form fields.
- **Monospace Font**: Allowed inside data cells for hashes, IP addresses, flag keys, code snippets, and aligned numeric data.

### What Is Strictly Banned in App Screens
- **No Gradients & Glows**: No glowing drop shadows around metric cards or pulsating border animations.
- **No Icons in Tinted Pastel Squares**: Avoid the ubiquitous AI template trope of an icon placed inside a rounded pastel square with 10% opacity.
- **No Rainbow Multi-Color Charts**: Charts must use monochromatic tone steps or a single focused accent colour. Don't use 8 different rainbow hues across cards.
- **No Invented Vanity Metrics**: Every number must represent concrete, plausible data that mathematically adds up. Never show fake placeholder telemetry or meaningless progress bars.
- **No Fake Grey Bar Dashboards**: Never display placeholder skeletons or grey rectangles when representing interface fragments. Build actual functional UI with realistic content.
- **No Circular Dots or Colored Balls on Difficulty Levels or Status Indicators ("Bulat-bulat" Cliché)**: Do not place green, yellow, or red circular dots (`•`, `●`, `border-radius: 50%`) beside difficulty levels (`Easy`, `Medium`, `Hard`) or beside status labels (e.g. `• LIVE SYNC`, `• ONLINE`). Rely purely on clean typography, distinct text contrast, signal meter bars, or subtle hairline containers.
- **No Redundant Dummy Filter Fields**: Never add decorative dropdowns (such as an "Event" filter when there is only one tournament event) that serve no functional purpose and clutter the workspace canvas.
- **No Fake Button Pill Badges or Sparkle Icons**: Never style static labels with button-like containers (`border`, `background`, `border-radius: 9999px`, `padding`) or sparkle/star icons (`✦`, `✨`, `⭐`). Labels such as `✦ Featured Target` are AI slop. Render static metadata with clean, unboxed typography (`Target Rekomendasi / by ITEC`).

---

## 3. Interface Fragments (`data-ui`)

When showcasing a software product on a landing page, interface fragments (such as interactive query bars, filter panels, or metric widgets) are the most convincing visual assets.

To construct interface fragments:
1. **Mark Root with `data-ui`**: Tag the top-level container element with `data-ui`. This tells automated inspectors that internal borders, buttons, and control conventions are intentional product UI.
2. **Real Data & Stated Finding**: Display authentic data that tells a specific story (e.g. "Response time: 42ms — 99.4% SLA adherence").
3. **Cohesive Palette**: Keep the fragment aligned with the site's primary palette and typography while preserving clean panel borders and status indicators.

---

## 4. App Screen Workflow

1. **Establish Foundation**:
   - Layout: Collapsible sidebar or compact top bar navigation.
   - Surface hierarchy: Deep background ground (`#090a0f`), secondary panel layer (`#0e111a`), interactive card layer (`#131724`).
2. **Define Data States**:
   - Explicitly support: **Loading**, **Empty** (with actionable empty state guidance), **Error** (clear plain error message), and **Active Data**.
3. **Gate Checks**:
   - Verify keyboard navigation through every form and interactive table.
   - Ensure contrast on text meets WCAG AA standards.

---

## 5. Security Academy Dashboard Benchmark Pattern (CyLab Standard)

For CTF platforms and cyberacademy dashboards:
1. **Welcome Hero Banner**:
   - **Surface**: Deep midnight navy gradient (`#071930` to `#092244`) with hairline border `1px solid #13345e` and `border-radius: 10px` to `12px`.
   - **Timestamp**: Small calendar icon and date badge at top-left (`Sunday, Nov 1` or `Minggu, 1 Nov`) in muted blue `#7ba4cc`.
   - **Two-Tone Heading**: Greeting prefix in crisp white (`Selamat datang, ` or `Good evening, `) paired with participant name highlighted in sky blue (`#60a5fa` or `#38bdf8`).
   - **Narrative Subhead**: Clean directional sentence explaining immediate mission status.
   - **Action Controls**: Dual pill buttons (`border-radius: 9999px`) with primary action in solid orange `#f97316` and secondary action in subtle outline or contrast.
2. **Asymmetric 2-Column Split**:
   - **Left Column ("Your Progress")**:
     * Streak or tournament participation status card with subtle hairline border.
     * Challenges completed metric card with clean green progress bar (`#22c55e`).
     * Participant profile link and points tally.
   - **Right Column ("Continue where you left off")**:
     * Prominent mission card highlighting the next unsolved or featured challenge target.
     * Unboxed metadata label (`Target Rekomendasi / by ITEC`), cellular signal meter difficulty, target title, synopsis, and direct action button.
3. **Anti-Slop Strict Enforcement**:
   - Zero em-dashes (`—`) anywhere in the copy.
   - Zero `Poppins` or comic-book green monospace outside data cells.
   - Real data metrics that mathematically match participant solves and leaderboard state.
