---
name: anti-slop
description: >-
  Enforces production-grade, human-designed UI standards for cybersecurity and technical platforms.
  Prohibits bubbly AI slop (emojis, Poppins, pastel gradients), fake unclickable button badges,
  pseudo-hacker clichés (forced monospace, green-on-black terminal tropes), and punctuation crutches
  (em-dashes and repetitive colons). Use this when building, designing, or refactoring CTF challenges,
  dashboards, admin views, and developer tooling.
---

# Anti-Slop Design Specification (Professional Security Platforms)

This skill overrides default AI aesthetic tendencies when generating web apps, cybersecurity platforms, dashboards, and developer tooling.

---

## 1. Typography Governance
- **Forbidden Fonts**
  * `Poppins`, `Nunito`, `Montserrat` (generate generic, child-like rounded AI dashboards).
  * Monospace applied globally across headings, body paragraphs, and navbars.
- **Mandatory UI Font**
  * Use `Inter`, `Geist Sans`, or native system stacks (`-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`).
  * Monospace (`JetBrains Mono`, `ui-monospace`) is strictly restricted to IP addresses, flags, source code, hashes, target IDs, and terminal outputs.

---

## 2. Eliminate Pseudo-Hacker Clichés & Fake Button Badges
- **No Fake Buttons on Static Metadata**
  * Never style non-clickable text, category tags, CWE labels, or status labels with button-like containers (`border`, `background`, `padding`, `border-radius`).
  * If an element cannot be clicked, it must look like plain typography, never like a button or chip control. Reserve button styling strictly for real, clickable actions.
  * Strictly forbid pill-shaped containers, button borders, and sparkle/star icons (`✦`, `✨`, `⭐`) around static metadata (such as `✦ Featured Target`). Use clean, unboxed typography (`Target Rekomendasi / by ITEC`).
- **No Artificial Jargon Syntax**
  * Do NOT prefix normal UI text with `//`, `>>`, `[SYSTEM]`, or write standard navigation in all-caps (`ADMINISTRATION // STATE RUNNING`).
- **No Gratuitous Emojis**
  * Do not use unicode emojis (`🚀`, `🔒`, `💉`, `⚡`, `🏆`) in technical dashboards. Use subtle inline SVGs with 1.5px stroke width.
- **No Comic Book Terminal Palettes**
  * Do not use pure pitch black `#000000` combined with 100% saturated neon green `#00ff00` or oversaturated mint.
- **No Circular Dots or Colored Balls on Difficulty Levels or Status Indicators ("Bulat-bulat" Cliché)**
  * Strictly forbidden: Placing green, yellow, or red circular dots (`•`, `●`, `border-radius: 50%`, small colored circles) beside difficulty levels (`Easy`, `Medium`, `Hard`), category items, or dropdown menu options.
  * Never place circular dots or colored balls next to status text (such as `• LIVE SYNC`, `🟢 LIVE`, `• ONLINE`, `• AKTIF`, `• STATUS`).
  * Status and difficulty levels must be communicated purely through clean typography, distinct text contrast, signal meter bars, or subtle hairline border states, never through traffic-light colored circles or pulsating balls.
  * Eliminate non-essential decorative filter fields (such as dummy "Event" dropdowns with static tournament names) that add clutter without functional filtering purpose.

---

## 3. Reference Architecture — Modern SecOps Aesthetic
- **Surfaces**
  * Dark neutral layered tones (`#090a0f` base, `#0e111a` panels, `#131724` card rows).
- **Hairline Precision**
  * UI elements are demarcated by subtle 1px solid borders (`#1e2538` to `#262f47`), not blurry glowing drop shadows.
- **Buttons & Controls**:
  * Default button: Dark slate surface `#1b2030`, subtle border `#2b344c`, white text `#f1f5f9`.
  * Primary highlight: Solid neutral light button or controlled emerald (`#10b981` at normal contrast) or amber.
  * Border-radius: Small and clean (`4px` to `6px`). Avoid full pill shapes (`rounded-full`) for card containers and inputs.

---

## 4. Copywriting & Content Formats
- Write clean human-readable labels such as "Status Tantangan", "Target Aktif", "Reset Skor".
- Avoid artificial dramatic roleplay like "Operator Authenticated", "Orchestration Matrix Online", "Broadcast Alert Strip Pushed".
- **Strictly Eliminate Colons (':') and Em-Dashes ('—') on the Website**:
  * Do NOT use colons (`:`) as repetitive structural crutches (such as `**Label:** Value`, `Status: Active`, `Catatan:`, `Perhatian:`).
  * Do NOT use em-dashes (`—`) on the website.
  * Use natural sentence structure with periods and commas, or use pure typographic hierarchy (distinct font weights, muted secondary colors, separate lines).

---

## 5. Security Academy Dashboard Benchmark (CyLab Standard)
- **Deep Midnight Welcome Banner**:
  * Background: Deep midnight navy gradient (`#071930` to `#092244`) with border `1px solid #13345e` and `border-radius: 10px` to `12px`.
  * Date badge: Small calendar icon and human date string at top left.
  * Two-tone title: Display font size `1.75rem` to `2rem`, greeting in white (`Selamat datang, `) with the username highlighted in sky blue (`#60a5fa`).
  * Action controls: Dual pill buttons (`border-radius: 9999px`) in solid vibrant orange `#f97316` and secondary outline.
- **Asymmetric Split**:
  * Left: `Your Progress` metrics column with streak card, challenge completion bar (`#22c55e`), and participant profile details.
  * Right: `Continue where you left off` primary featured card directing participant straight to next lab target.

---

## 6. Scoreboard & Timeline Visual Standards (Fintech/Trading Dashboard Benchmark)
- **Dynamic Time Bounding**: Never stretch timeline axes across days or weeks of idle time, which compresses real competition solves into an illegible sliver on the left. Dynamically bound the x-axis to actual activity (`[first_solve - 15m, last_solve + 30m]`).
- **Smooth Spline Curves**: Use smooth bezier curves (`tension: 0.35` to `0.38`) with milestone ring nodes on actual solve events (hollow center, line-color border). Never use stepped staircase wires that turn into unreadable spiderwebs.
- **Minimalist Legends**: Do NOT render stacked, chunky button badges or pill chips for legends. Use clean unboxed typography with horizontal line swatches (`14px x 3px`), and provide segmented view controls (`Top 3`, `Top 5`, `Semua`) with click-to-toggle interactivity.
- **Clean Gridlines**: Zero vertical gridlines on the x-axis. Subtle horizontal dashed gridlines (`borderDash: [3, 3]`, rgba 0.04) on the y-axis only. Standout primary line in warm amber/orange `#f59e0b` commanding visual hierarchy.


