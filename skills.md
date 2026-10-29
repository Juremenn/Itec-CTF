---
name: anti-slop
description: Enforces production-grade, human-designed UI standards. Prohibits both bubbly AI slop (emojis, Poppins, pastel gradients) and pseudo-hacker AI slop (forced monospace, green-on-black terminal clichés, '// COMMENT' labels).
version: 2.1.0
---

# Anti-Slop Design Specification (Professional Security Platforms)

This skill overrides default AI aesthetic tendencies when generating web apps, cybersecurity platforms, dashboards, and developer tooling.

## 1. Typography Governance
- **Forbidden Fonts**
  * `Poppins`, `Nunito`, `Montserrat` (often generate generic, child-like rounded AI dashboards).
  * Monospace applied globally across headings, paragraphs, and navbars.
- **Mandatory UI Font**
  * Use `Inter`, `Geist Sans`, or native system stacks — `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`.
  * Monospace (`JetBrains Mono`, `ui-monospace`) is strictly restricted to IP addresses, flags, source code, hashes, and terminal outputs.

## 2. Eliminate Pseudo-Hacker Clichés
- **No Artificial Jargon Syntax** — Do NOT prefix normal UI text with `//`, `>>`, `[SYSTEM]`, or write standard navigation in all-caps (`ADMINISTRATION // STATE RUNNING`).
- **No Gratuitous Emojis** — Do not use unicode emojis (`🚀`, `🔒`, `💉`, `⚡`, `🏆`) in technical dashboards. Use subtle inline SVGs with 1.5px stroke width.
- **No Comic Book Terminal Palettes** — Do not use pure pitch black `#000000` combined with 100% saturated neon green `#00ff00` or oversaturated mint.

## 3. Reference Architecture — Modern SecOps Aesthetic (CyberLabs / HackTheBox / Cloudflare)
- **Surfaces** — Dark neutral layered tones (`#090a0f` base, `#0e111a` sidebars, `#131724` cards).
- **Hairline Precision** — UI elements are demarcated by subtle 1px solid borders (`#1e2538` to `#262f47`), not blurry glowing drop shadows.
- **Buttons & Controls**
  * Default button — Dark slate surface `#1b2030`, subtle border `#2b344c`, white text `#f1f5f9`.
  * Primary highlight — Solid neutral light button or controlled emerald (`#10b981` at normal contrast).
  * Border-radius — Small and clean (`4px` to `6px`). Avoid full pill shapes (`rounded-full`) for card containers and inputs.

## 4. Copywriting & Content Formats
- Write clean human-readable labels such as "Challenge Status", "Active Target", "Reset Scores".
- Avoid artificial dramatic roleplay like "Operator Authenticated", "Orchestration Matrix Online", "Broadcast Alert Strip Pushed".
- **Strictly Avoid Colons (':') — Zero AI Slop Punctuation**
  * Do NOT use colons as repetitive structural formatting crutches (such as `**Label:** Value`, `Status: Active`, `Catatan:`, or `Perhatian:`). Overusing colons is a blatant giveaway of robotic AI generation.
  * In UI copy, labels, cards, tables, bullet points, and agent responses, eliminate colons entirely. Use natural sentence structure, em-dashes (`—`), or dedicated UI layout hierarchy (distinct weights, muted colors, or separate visual blocks).