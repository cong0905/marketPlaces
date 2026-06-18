---
name: Amber Marketplace
colors:
  surface: '#fff8f3'
  surface-dim: '#e5d8c8'
  surface-bright: '#fff8f3'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#fff2e2'
  surface-container: '#faecdb'
  surface-container-high: '#f4e6d6'
  surface-container-highest: '#eee0d0'
  on-surface: '#211b11'
  on-surface-variant: '#514532'
  inverse-surface: '#372f24'
  inverse-on-surface: '#fdefde'
  outline: '#847560'
  outline-variant: '#d6c4ac'
  surface-tint: '#7e5700'
  primary: '#7e5700'
  on-primary: '#ffffff'
  primary-container: '#ffb300'
  on-primary-container: '#6b4900'
  inverse-primary: '#ffba38'
  secondary: '#0051d5'
  on-secondary: '#ffffff'
  secondary-container: '#316bf3'
  on-secondary-container: '#fefcff'
  tertiary: '#00677e'
  on-tertiary: '#ffffff'
  tertiary-container: '#00d2fe'
  on-tertiary-container: '#00566a'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffdeac'
  primary-fixed-dim: '#ffba38'
  on-primary-fixed: '#281900'
  on-primary-fixed-variant: '#604100'
  secondary-fixed: '#dbe1ff'
  secondary-fixed-dim: '#b4c5ff'
  on-secondary-fixed: '#00174b'
  on-secondary-fixed-variant: '#003ea8'
  tertiary-fixed: '#b5ebff'
  tertiary-fixed-dim: '#43d6ff'
  on-tertiary-fixed: '#001f28'
  on-tertiary-fixed-variant: '#004e60'
  background: '#fff8f3'
  on-background: '#211b11'
  surface-variant: '#eee0d0'
typography:
  display-lg:
    fontFamily: Be Vietnam Pro
    fontSize: 48px
    fontWeight: '700'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Be Vietnam Pro
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
  headline-lg-mobile:
    fontFamily: Be Vietnam Pro
    fontSize: 24px
    fontWeight: '700'
    lineHeight: '1.2'
  headline-md:
    fontFamily: Be Vietnam Pro
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
  headline-sm:
    fontFamily: Be Vietnam Pro
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.3'
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.05em
  price-lg:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '700'
    lineHeight: '1'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 40px
  2xl: 64px
  container-max: 1280px
  gutter: 16px
  margin-mobile: 16px
  margin-desktop: 32px
---

## Brand & Style

The brand personality is **reliable, energetic, and efficient**. It is designed to bridge the gap between casual peer-to-peer selling and professional e-commerce. The target audience includes eco-conscious Gen Z shoppers, value-seeking families, and high-volume resellers who prioritize speed and trust.

This design system utilizes a **Modern Minimalist** aesthetic with high-utility layouts. The interface prioritizes content—specifically product imagery—by using expansive whitespace and a restrained decorative palette. It avoids unnecessary ornamentation, focusing instead on clear affordances and "conversion-first" ergonomics. The emotional response is one of clarity and confidence: the UI feels "fast" and the marketplace feels "vetted."

## Colors

The palette is anchored by **Amber (#FFB300)**, used strategically for primary actions and "Buy" flows to evoke a sense of urgency and warmth. **Blue (#2563EB)** serves as the secondary color, reserved for trust-building elements like verified badges, links, and secondary navigation.

- **Primary (Amber):** Hero actions, primary buttons, and price highlights.
- **Secondary (Blue):** Informational cues, verification, and interactive text.
- **Background (Slate 50):** A cool-toned base that makes product photos pop and reduces eye strain.
- **Text (Slate 900):** High-contrast typography for maximum readability.
- **Semantic (Success/Danger):** Standardized green for "Sold" or "Available" statuses and red for price drops or alerts.

## Typography

This design system uses a dual-font strategy. **Be Vietnam Pro** is used for headlines to provide a contemporary, friendly, and approachable character. **Inter** is used for all functional UI elements, body text, and price labels due to its exceptional legibility and systematic feel.

For mobile, headlines scale down significantly to ensure long Vietnamese product titles do not break the layout. Prices are treated as a distinct typographic role with increased weight to ensure they are the first thing a user sees on a product card.

## Layout & Spacing

The system follows an **8px soft grid** for spacing components and a **12-column fluid grid** for page-level layouts.

- **Mobile (<768px):** Single or double column layout. 16px margins. Bottom-fixed navigation for reachability.
- **Tablet (768px - 1024px):** 3-column product grid. 24px margins.
- **Desktop (>1024px):** 4 or 5-column product grid. 1280px max-width container. 32px margins.

Spacing is generous to promote a "premium" feel even when selling used goods. Gutters between cards are kept at 16px to maintain a high density of information while preventing visual clutter.

## Elevation & Depth

Visual hierarchy is achieved through **Tonal Layers** supplemented by **Ambient Shadows**.

- **Level 0 (Base):** Slate 50 background.
- **Level 1 (Cards/Surface):** Pure white (#FFFFFF) with a 1px border (#E2E8F0). No shadow.
- **Level 2 (Interaction):** When hovering over product cards or buttons, a soft, diffused shadow (10% opacity Slate 900) is applied to suggest lift.
- **Level 3 (Modals/Sticky Nav):** High-blur shadows (20px blur, 5% opacity) to separate global navigation and critical action sheets from the content layer.

Sticky headers use a semi-transparent white background with a `backdrop-filter: blur(8px)` to maintain context while scrolling.

## Shapes

The design system uses a **Rounded** shape language to appear friendly and safe. 

- **Components:** Buttons, input fields, and small chips use 0.5rem (8px).
- **Cards:** Product cards and containers use `rounded-lg` (1rem / 16px) to soften the overall grid.
- **Search Bars:** Large global search bars use `rounded-xl` (1.5rem / 24px) or full pill-shape to distinguish them from other inputs.
- **Images:** Thumbnails within cards should always inherit the container's top-border radius.

## Components

### Buttons
Primary buttons use the Amber color with Slate 900 text for maximum contrast. They feature a subtle 2px bottom "border-shadow" of a darker amber tint to feel tactile. Secondary buttons use a Slate 100 background or a ghost-style Blue outline.

### Modern Cards
Product cards are the core of the marketplace. They feature a fixed 1:1 aspect ratio image, followed by a padded section containing the title (max 2 lines), price (Primary Amber), location/time (Muted text), and a small "Trust Badge" if the seller is verified.

### Search Bar
The primary search bar is oversized and sticky on mobile. It includes a leading icon and a trailing "Filter" button. It should use a slight shadow to remain visible over scrolling content.

### Chips & Badges
Used for categories and item status. Categories use a Slate 100 background with Slate 900 text. Status badges (e.g., "New", "Likely New") use small, uppercase Inter Bold text with subtle background tints of Success or Secondary colors.

### Inputs
Text fields use a 1px border that shifts from Slate 200 to Blue 600 on focus. Error states are clearly marked with a Red border and an icon.

### Sticky Navigation
Mobile layouts must feature a bottom sticky tab bar for: Home, Chat, Post (centered, highlighted in Amber), Likes, and Profile.