# Handoff: AutoVia — Car Buy & Sell Marketplace

## Overview
AutoVia is a peer-to-peer car marketplace where private sellers can list vehicles and buyers can search, compare, and contact sellers. This handoff covers the full UI prototype including 6 screens with interactive state management.

## About the Design Files
The files in this bundle are **high-fidelity design references created in HTML/React** — working prototypes showing intended look, content, and behavior. They are **not** production code to copy directly. Your task is to **recreate these designs in your existing codebase** (React, Next.js, Vue, etc.) using its established patterns, routing, and component libraries. If no codebase exists yet, React + Next.js is recommended.

## Fidelity
**High-fidelity (hifi)** — Pixel-accurate colors, typography, spacing, and interactions. Recreate pixel-perfectly using your codebase's design system. All hex values, font sizes, and spacing are specified below.

---

## Design Tokens

### Colors
| Token | Value | Usage |
|-------|-------|-------|
| `accent` | `#f07040` (Orange — user-selected) | Primary CTA, links, highlights |
| `bg-page` | `#F8FAFC` | Page background |
| `bg-surface` | `#FFFFFF` | Cards, panels |
| `border` | `#E2E8F0` | Dividers, card borders |
| `border-light` | `#F1F5F9` | Subtle separators |
| `text-primary` | `#0F172A` | Headings, body |
| `text-secondary` | `#64748B` | Subheadings, metadata |
| `text-muted` | `#94A3B8` | Placeholders, timestamps |
| `text-faint` | `#CBD5E1` | Disabled, very subtle |
| `badge-featured` | accent | Featured badge bg |
| `badge-topviewed` | `#F59E0B` | Top Viewed badge bg |
| `badge-new` | `#10B981` | New badge bg |
| `badge-premium` | `#8B5CF6` | Premium badge bg |
| `hero-bg` | `linear-gradient(135deg, #0F172A, #1E3A5F)` | Hero section background |

### Typography
- **Font family:** `Plus Jakarta Sans` (Google Fonts), weights 400/500/600/700/800
- **Hero heading:** 42px / weight 800 / letter-spacing -1px
- **Section heading:** 22px / weight 800
- **Card title:** 15–16px / weight 700
- **Card meta:** 12–13px / weight 400 / color `text-muted`
- **Price:** 17–32px / weight 800 / color `accent`
- **Label:** 12px / weight 600 / uppercase / letter-spacing 1px / color `text-secondary`
- **Button:** 14–15px / weight 700

### Spacing & Shape
- **Card border-radius:** 12px (user-selected via Tweaks)
- **Button border-radius:** 8–10px
- **Card shadow (hover):** `0 8px 24px rgba(0,0,0,0.10)`
- **Card shadow (default):** `0 1px 4px rgba(0,0,0,0.04)`
- **Page max-width:** 1200px, centered, `padding: 0 24px`
- **Navbar height:** 64px, `position: sticky top: 0 z-index: 100`
- **Section gap:** 52px between home sections
- **Card grid gap:** 20px (featured/most-viewed), 16px (search results)

---

## Screens / Views

### 1. Home (`/`)
**Purpose:** Landing page — hero search, featured cars, most-viewed ranking, listings list.

**Layout:**
- Full-width dark hero (`padding: 64px 24px`) with centered text + search bar (max-width 560px)
- Below hero: max-width 1200px container
  - **Featured** section: 3-column card grid
  - **Most Viewed** section: 3-column card grid with numbered rank badge (position: absolute, top-left)
  - **More Listings** section: full-width list layout (horizontal cards)

**Hero components:**
- Eyebrow label: 12px uppercase tracking-widest, accent color
- H1: 42px/800, white + accent span
- Subtitle: 16px, `#94A3B8`
- Search bar: white box, border-radius 12px, `padding: 8px`, inner input + accent button
- Stats row: 3 items (Listings / Rating / Safety), white number 20px/800, label 12px gray

**Section headers:**
- Title 22px/800 + subtitle 14px/`text-secondary` on left
- "View all →" outline button on right (`border: 1px solid accent`, `border-radius: 8px`, `padding: 8px 16px`)

---

### 2. Search (`/search`)
**Purpose:** Full search with filters sidebar + results grid/list.

**Layout:** 2-column grid — `280px` sidebar + `1fr` results area, `gap: 32px`

**Sidebar (sticky, `top: 80px`):**
- White card, border-radius 14px, padding 20px
- Filters: Brand (select), State (select), Fuel (select), Transmission (select), Color (select), Price range (2 inputs), Year range (2 inputs), Mileage range (2 inputs)
- "Clear filters" button at bottom
- Input style: `border: 1px solid #E2E8F0`, border-radius 8px, padding `8px 10px`, font-size 13px

**Results area:**
- Top bar: "{N} cars found" + sort select + grid/list toggle
- Grid mode: 3-column, `gap: 16px`
- List mode: flex column, `gap: 12px`
- Empty state: centered icon + heading + subtext

**Interactions:**
- All filters are live (no submit button)
- Sort options: Most viewed / Lowest price / Highest price / Newest
- Grid/List toggle: icon buttons in a segmented control (`background: #F1F5F9`, radius 8px)

---

### 3. Car Detail (`/cars/:id`)
**Purpose:** Full car page — photos, specs, contact CTA, seller info, related cars.

**Layout:** 2-column grid — `1fr` main content + `340px` sidebar

**Main (left):**
- Photo area: white card, border-radius 14px, height 360px
- Tabs (Details / Specifications): tab bar with active indicator (`border-bottom: 2px solid accent`)
- Spec rows: `padding: 12px 0`, `border-bottom: 1px solid #F1F5F9`, label left / value right

**Sidebar (right), top to bottom:**
1. **Price card:** brand/model, year·km, badge, price (32px/800/accent), view count, "Contact Seller" button (full-width, accent), "Show Phone" button (full-width, `#F1F5F9`)
   - After contact: button turns `#10B981` with "✓ Message Sent!"
2. **Seller card:** avatar (44px circle, accent background, initial letter), name, city/state, rating + review count. Entire card is clickable → Seller Profile
3. **Related cars:** small horizontal cards (60×42px photo + model/price)

---

### 4. Seller Profile (`/sellers/:id`)
**Purpose:** Seller bio + all their active listings.

**Layout:** Single column, max-width 1000px

**Header card:**
- Gradient background (`accent + "15"` to `accent + "05"`)
- 80px circular avatar, name 24px/800, city/state/since, rating row
- "Contact" button (accent, top-right)

**Listings grid:** 3-column CarCard grid

---

### 5. New Listing (`/listings/new`)
**Purpose:** Multi-step form to publish a car ad.

**Layout:** Centered, max-width 680px

**Stepper:** 4 steps — Vehicle / Pricing / Contact / Review
- Step circles: 32px, filled accent when active/past, gray border when future
- Connecting line: `position: absolute`, animated width via CSS transition

**Steps:**
1. **Vehicle:** Brand (select), Model, Year, Mileage, Fuel Type, Transmission, Color — 2-column grid
2. **Pricing & Location:** Price (full width), State + City (2-col), Description (textarea)
3. **Contact:** Full name, Phone, Email
4. **Review:** Car placeholder + spec summary grid (2-col)

**Navigation:** Back (gray) / Next → (accent) buttons, flex space-between

---

### 6. Compare (`/compare`)
**Purpose:** Side-by-side comparison of up to 3 cars.

**Layout:** CSS Grid — `160px` label column + 3 × `1fr` car columns

**Car slot (empty):** dashed circle `+` button, "Add a car" label
**Car slot (filled):** photo placeholder + name/year + "View" button + "×" remove button

**Spec rows:** 7 rows — Price, Year, Mileage, Fuel, Transmission, Color, Location
- Winner cell highlighted: `background: accent + "12"`, `border: 1px solid accent + "30"`, fontWeight 700, accent color + "✓" badge
- Winners determined by: lowest price, lowest km, newest year

**Car picker modal:**
- Overlay (`rgba(0,0,0,0.5)`) with centered white panel (max-width 560px)
- List of all cars — click to select, selected ones tinted `accent + "10"`

---

## Shared Components

### CarCard
Two variants:

**Grid (default):**
- White card, border-radius 12px, border `1px solid #E2E8F0`
- Hover: `translateY(-2px)`, shadow `0 8px 24px rgba(0,0,0,0.10)`, border tinted with accent
- Photo placeholder: 180px height
- Badge: absolute top-left
- Favorite button: absolute top-right, 32px circle, white 90% bg
- Body: padding `14px 16px`, title 15px/700, meta 12px/muted, price + location row

**List:**
- Horizontal flex, 220px photo on left, content right, favorite button far right
- Height ~140px photo

### Navbar
- White bg, `border-bottom: 1px solid #E2E8F0`, sticky top 0, z-index 100, height 64px
- Logo: SVG circle (accent fill) + "Auto**Via**" wordmark (accent span)
- Nav links: Home / Search / Compare — active state has `border-bottom: 2px solid accent`
- CTA: "+ List a Car" button, accent fill

### Badge
Sizes: `font-size: 11px`, `font-weight: 700`, `padding: 3px 8px`, `border-radius: 4px`, white text

---

## Interactions & Behavior

| Interaction | Behavior |
|-------------|----------|
| Favorite (♡/♥) | Toggle local state, color changes to `#EF4444` |
| Card hover | `translateY(-2px)` + shadow increase + border accent tint |
| Contact Seller | Button turns green `#10B981`, shows "✓ Message Sent!" |
| Search filters | Live filtering, no submit needed |
| Compare picker | Modal overlay with car list |
| New listing stepper | Step circles fill with accent as user advances |

---

## Data Model (mock)

```ts
interface Car {
  id: number;
  brand: string;
  model: string;
  year: number;
  price: number;       // BRL
  km: number;
  fuel: "Flex Fuel" | "Gasoline" | "Diesel" | "Hybrid" | "Electric";
  transmission: "Automatic" | "Manual";
  color: string;
  city: string;
  state: string;       // BR state abbreviation
  views: number;
  featured: boolean;
  badge: "Featured" | "Top Viewed" | "New" | "Premium" | null;
  images: string[];    // URLs
  sellerId: number;
}

interface Seller {
  id: number;
  name: string;
  city: string;
  state: string;
  rating: number;      // 0–5
  reviews: number;
  carIds: number[];
  memberSince: string; // year
  phone: string;
}
```

---

## Assets
- **Car images:** Prototype uses SVG color-coded placeholders. Replace with real photos from your API/CDN.
- **Seller avatars:** Placeholder uses first letter of name in a colored circle. Replace with real avatars.
- **Font:** [Plus Jakarta Sans](https://fonts.google.com/specimen/Plus+Jakarta+Sans) — load via Google Fonts or bundle locally.
- **Icons:** No icon library used — consider Lucide or Heroicons for a clean match.

---

## Files in This Bundle
| File | Description |
|------|-------------|
| `AutoVia.html` | Full interactive prototype (all 6 screens) |
| `tweaks-panel.jsx` | Tweaks panel helper component (design tool only, not for production) |
| `README.md` | This document |
