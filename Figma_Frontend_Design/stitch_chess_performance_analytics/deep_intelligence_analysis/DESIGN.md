---
name: Deep Intelligence Analysis
colors:
  surface: '#131315'
  surface-dim: '#131315'
  surface-bright: '#39393b'
  surface-container-lowest: '#0e0e10'
  surface-container-low: '#1c1b1d'
  surface-container: '#201f22'
  surface-container-high: '#2a2a2c'
  surface-container-highest: '#353437'
  on-surface: '#e5e1e4'
  on-surface-variant: '#c2c6d6'
  inverse-surface: '#e5e1e4'
  inverse-on-surface: '#313032'
  outline: '#8c909f'
  outline-variant: '#424754'
  surface-tint: '#adc6ff'
  primary: '#adc6ff'
  on-primary: '#002e6a'
  primary-container: '#4d8eff'
  on-primary-container: '#00285d'
  inverse-primary: '#005ac2'
  secondary: '#4edea3'
  on-secondary: '#003824'
  secondary-container: '#00a572'
  on-secondary-container: '#00311f'
  tertiary: '#ffb2b7'
  on-tertiary: '#67001b'
  tertiary-container: '#ff516a'
  on-tertiary-container: '#5b0017'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#d8e2ff'
  primary-fixed-dim: '#adc6ff'
  on-primary-fixed: '#001a42'
  on-primary-fixed-variant: '#004395'
  secondary-fixed: '#6ffbbe'
  secondary-fixed-dim: '#4edea3'
  on-secondary-fixed: '#002113'
  on-secondary-fixed-variant: '#005236'
  tertiary-fixed: '#ffdadb'
  tertiary-fixed-dim: '#ffb2b7'
  on-tertiary-fixed: '#40000d'
  on-tertiary-fixed-variant: '#92002a'
  background: '#131315'
  on-background: '#e5e1e4'
  surface-variant: '#353437'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  body-base:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  data-mono:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
    letterSpacing: 0.02em
  label-caps:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '700'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  gutter: 16px
  margin: 24px
---

## Brand & Style
The design system is engineered for high-density information environments where precision is paramount. It caters to a target audience of serious strategists and analysts who require a high-tech, "heads-up display" (HUD) aesthetic.

The visual direction is **Modern Technical**. It prioritizes legibility and data hierarchy through a disciplined dark-mode-first approach. By utilizing a minimalist structure paired with high-contrast semantic accents, the design system evokes a sense of deep intelligence and computational power. The emotional response should be one of focus, clarity, and analytical confidence.

## Colors
The palette is rooted in a "Deep Dark" foundation using Zinc and Slate tones to minimize eye strain during long analysis sessions. 

- **Primary (Electric Blue):** Used for trend lines, interactive states, and active selection.
- **Success (Emerald):** Reserved exclusively for positive outcomes, winning advantages, and "best move" indicators.
- **Error (Rose):** Indicates losses, blunders, or critical negative trends.
- **Warning (Amber):** Designated for draws, inaccuracies, or neutral parity.
- **Neutrals:** A range of grays from Zinc-950 (background) to Zinc-400 (secondary text) provides the structural scaffolding. 

Color should be used sparingly but purposefully; in a data-rich environment, color is information, not decoration.

## Typography
Typography in the design system is optimized for tabular data and technical notation. 

**Inter** serves as the primary typeface for its exceptional legibility and neutral, professional tone. For move lists, coordinates, and PGN (Portable Game Notation), **JetBrains Mono** is utilized to ensure every character has distinct visual weight and perfect vertical alignment. 

All numerical data should utilize `tabular-nums` CSS settings to prevent layout shifting when values update in real-time. Use `label-caps` for secondary metadata and table headers to create a clear distinction from primary data points.

## Layout & Spacing
The layout follows a **Fluid Grid System** with a 12-column structure for desktop. This allows for complex dashboard arrangements where engine analysis, board visualization, and move-lists can coexist.

The spacing rhythm is built on a 4px baseline grid. 
- **Tight (4px/8px):** Use for internal component grouping, such as move pairs or badge icons.
- **Standard (16px):** The default for component padding and gutters.
- **Loose (24px/32px):** Used for major section margins to provide "visual breathing room" in an otherwise dense UI.

On mobile devices, the layout reflows into a single column with a 16px side margin, prioritizing the board visualization and move-list vertically.

## Elevation & Depth
Depth is established through **Tonal Layering** rather than traditional shadows, which can become muddy in deep dark interfaces.

- **Level 0 (Base):** Zinc-950 (#09090B). The canvas.
- **Level 1 (Surface):** Zinc-900 (#18181B). For primary cards and containers.
- **Level 2 (Overlay):** Zinc-800 (#27272A). For hover states, tooltips, and modals.

To define boundaries, use **Low-Contrast Outlines** (1px borders using Zinc-800). Subtle background blurs (12px to 20px) should be applied to fixed navigation bars or floating panels to maintain context of the data underneath.

## Shapes
The shape language is **Soft (0.25rem)**. This subtle rounding provides a modern touch without sacrificing the "engineered" feel of the interface. 

Buttons and input fields should strictly follow the `rounded-sm` (4px) or `rounded-md` (8px) rules. Performance badges and small tags may use a pill-shape to distinguish them from interactive buttons. Data bars in charts should remain sharp (0px) to ensure accurate visual comparison of values.

## Components
Consistent component styling is vital for maintaining the analytical integrity of the design system.

- **Data Cards:** Use a Zinc-900 background with a 1px border. Titles should be in `label-caps`.
- **Interactive Charts:** Lines should be 2px thick. Use the Primary (Blue) for the main trend, and apply subtle area gradients (10% opacity) beneath the lines.
- **Move-Lists:** Use a striped background (alternating Zinc-900 and Zinc-950) for rows. The "Active Move" should have a Primary Blue left-accent border and a subtle blue tint background.
- **Performance Badges:** Small, high-contrast pills. Text should be `data-mono` 12px. Background color should match the semantic meaning (Win/Loss/Draw) with a 20% opacity and a 100% opacity border.
- **Input Fields:** Darker than the surface (#09090B), with a 1px Zinc-800 border. On focus, the border transitions to Primary Blue with a 2px outer glow.
- **Buttons:** 
    - *Primary:* Solid Electric Blue with white text. 
    - *Secondary:* Ghost style (Zinc-800 border) with Zinc-100 text.