# Dental Connect — Session Handoff (2026-09-17, part 2)

## 1. Where this picks up from

Same day as `handoffs/2026-09-17-landing-page-hardening-and-brand-assets.md` —
read that first, it covers the full-image hero rebuild, real logo assets,
em-dash cleanup, and the register-page role-switch security fix. This
document covers everything that happened **after** that handoff was
written, in the same conversation.

**Committed and pushed this round:** commit `1cdd53d` on `main`
(previous HEAD `188c6d0`). There is a **pending, unimplemented request**
at the end of this session (§3 below) — the user gave it, then the
message was cut off / interrupted before I could act on it, and they
asked to end the session instead. Nothing for §3 has been written to
disk. Start there tomorrow.

## 2. What was completed and pushed (commit `1cdd53d`)

### Header
- Added a thin `border-white/15` divider line under the transparent
  hero header, visible before scrolling — matches the divider already
  used in the hero's own bottom bar, per explicit request ("just like
  the one on bottom of hero section").
- **Fixed a real bug**: `components/dc-logo.blade.php` was sizing the
  full wordmark image with a Tailwind arbitrary-value class built from
  a PHP-interpolated number — `class="... [height:{{ round($size*0.78) }}px]"`.
  Tailwind's build-time scanner reads the **raw source file as text**;
  it cannot resolve Blade interpolation inside a bracket class, so it
  silently generated no CSS rule at all. The image had no height
  constraint and rendered at its native ~440px height on every
  non-home page (About, Contact, etc.) — full-bleed, breaking the
  layout. Fixed by reverting to a plain inline `style="height: {{ round($size*0.8) }}px"`.
  **Lesson for future work on this component**: never build a Tailwind
  arbitrary-value class from a runtime/PHP value — inline `style` is
  the only reliable way to do computed sizing in Blade.

### Hero card carousel
- The single floating "rating card" became a **two-card swipeable
  carousel** (Alpine `x-data` on the `<section>` itself, so both the
  card track and the bottom-bar Prev/Next buttons share one scope):
  - Card 1: dental-examination-closeup photo, "Restore natural healthy
    confident dental growth.", 5.0 rating
  - Card 2: dentist-virtual-consultation photo, "Book appointments and
    consult online with ease.", 5.0 rating
  - Both cards show side by side on `sm`+ (tablet/desktop); on mobile,
    one card shows at ~74vw width with a peek of the next, swipeable
    via native touch scroll (CSS scroll-snap) or by tapping the dot
    indicators / the pager's Prev-Next buttons
  - The "Preview 01/08" pager (previously pure decoration) is now real:
    it reads "01/02", reflects `cardIndex`, and Prev/Next actually move
    the carousel via `scrollToCard()`
- **Fixed a real alignment bug**: the card row was `position: absolute`
  directly on the `<section>` with `left-6`/`left-8`, which is measured
  from the section's own edge — but the heading above sits inside a
  `max-w-7xl mx-auto` container that gets centered with extra margin
  once the viewport exceeds ~1344px. Above that width the card
  drifted out of alignment with "Family-Friendly Dental Care" by
  exactly the container's side margin. Fixed by wrapping both the card
  row and the service tags in the same `max-w-7xl mx-auto px-4 sm:px-6
  lg:px-8` container the heading uses. Verified pixel-exact alignment
  (both at `left: 32px` at 1024px width, both at `left: 112px` at
  1440px width) via `getBoundingClientRect()`, not just eyeballing.
- **Fixed a real flexbox bug**: after adding `min-w-0` was needed —
  without it, the card-track flex item wouldn't shrink below its
  content's intrinsic width (classic `min-width: auto` flex default),
  so `scrollWidth === clientWidth` and nothing ever actually
  overflowed/scrolled on mobile, even though the carousel *looked*
  right in a screenshot. Caught this only by checking
  `track.scrollWidth > track.clientWidth` in the console — a
  screenshot alone would not have revealed it.
- Reserve-spacer height (the invisible strip that lets the hero text
  center without overlapping the card) had to be re-tuned twice this
  round: once to `270px` on mobile to fit the new swipe-dot row, then
  the card row's own `bottom` offset had to go from `bottom-28` to
  `bottom-44` on mobile specifically (reverting to `sm:bottom-28
  lg:bottom-24` at larger sizes, where the swipe dots are hidden and
  the row is shorter) because the dot row was overlapping the bottom
  pager by 53px. **Both of these were verified numerically** via
  `getBoundingClientRect()` diffs at 375×812, not just visually.

### Content
- Info chips ("Verified Clinics" / "Transparent Pricing" / "Trusted
  Reviews") no longer wrap to two lines on mobile — they're a single
  horizontally-scrollable row (`flex-nowrap overflow-x-auto`, edge
  bleed via negative margin, hidden scrollbar), reverting to normal
  `flex-wrap` at `sm`+ where there's room.

### Scroll-reveal ("live movement, not a static page")
- New: `resources/js/app.js` — an `IntersectionObserver` that adds
  `.is-revealed` to any `[data-reveal]` element the first time it's
  ~12% visible, then stops observing it. Skips entirely (marks
  everything revealed immediately) if `prefers-reduced-motion: reduce`
  or `IntersectionObserver` isn't supported.
- New CSS in `resources/css/app.css`, gated behind a `.js-reveal` class
  that an inline `<script>` in `<head>` (in `public.blade.php`) adds to
  `<html>` **before** anything else runs. This means: if JavaScript
  never loads, `.js-reveal` is never added, and `[data-reveal]`
  elements are never hidden in the first place — pure progressive
  enhancement, no flash-of-invisible-content risk.
- Applied `data-reveal` to every section below the hero (How It Works,
  About, Featured Treatment, Why Dental Connect, Services, Team
  banner, Insights, Newsletter), with a `--reveal-delay` CSS variable
  set per-item via inline `style` for a staggered cascade on repeated
  grid items (stat cards, service cards, insight articles).
- The reduced-motion media query in `app.css` now also zeroes
  `transition-duration`/`transition-delay` (it previously only
  targeted `animation-*`, which wouldn't have covered the reveal
  system's plain CSS transitions).

### Glassmorphism beyond the hero
- The `.dc-card` glass treatment (`bg-white/75 backdrop-blur-xl`) was
  already used in About/Services/Why-Dental-Connect/Insights, but
  against the page's pale mint gradient it barely registered as
  "glass" — there wasn't enough contrast behind it to blur. Added
  large, low-opacity, heavily-blurred colored circles (`blur-3xl`,
  teal/mint at 10–50% opacity) positioned behind the card grids in How
  It Works, About, Why Dental Connect, Services and Insights (each
  section got `relative overflow-hidden` + 1–2 `pointer-events-none
  absolute ... blur-3xl` divs). Confirmed visually in-browser that the
  frosted-glass effect is now clearly visible on scroll, not just in
  the hero.

### Verification performed this round
- `php artisan test` → 41/41, run after every batch of changes.
- Alignment/overlap claims verified with `getBoundingClientRect()` math
  printed to console, not screenshots alone (screenshots in this
  environment were intermittently stale/cached this session — twice a
  screenshot showed no change after a click that had, per direct DOM
  inspection, actually worked correctly. When a screenshot looks wrong
  after an interaction, re-check with a fresh `computer{action:
  "screenshot"}` call or a JS `getComputedStyle`/`getBoundingClientRect`
  check before concluding something is actually broken).
- Manually tested the carousel's swipe dots and Prev/Next buttons at
  375px and confirmed `cardIndex`/`scrollLeft` actually changed, not
  just that a screenshot looked different.
- Confirmed the logo-sizing bug fix on `/about` at mobile (375px) and
  tablet (768px) widths.

## 3. Pending — not started, needs to be picked up first tomorrow

The user gave five more requests in a single message, the last of
which (a Pinterest link) cut off before I could act on any of them.
**Nothing below has been implemented.** Verbatim/near-verbatim from the
user, with what I know so far for each:

1. **Add the tagline back on the home page header.** "home page after
   logo also add this 'Connected dental care for Tanzania' as on other
   pages." The non-home header (`components/layouts/public.blade.php`,
   the `@else` branch, ~line 46) already renders this tagline via
   `<x-dc-logo :size="36" tagline="Connected dental care for Tanzania" />`
   (shown at `lg:` and up, inside the component). The **home page's
   transparent header** (the `@if ($transparentHeader)` branch, ~lines
   15–44) uses hand-written `<img>` tags instead of `<x-dc-logo>`
   (because it needs the scroll-reactive white/color swap the shared
   component doesn't support) and currently has no tagline text at
   all. Fix: add a matching `<span>` next to the logo image in that
   branch, hidden below `lg` and color-swapped via the same `scrolled`
   Alpine state as everything else in that header (see how the old
   hand-coded SVG version did this color-swap before it was replaced
   with the image — same pattern, just on a `<span>` instead of an
   `<svg>` `stroke` attribute).

2. **Redesign the hero cards — current design rejected.** The user
   pasted the rendered text content of both cards run together
   ("Professional dental examination / Restore natural healthy
   confident dental growth. / 5.0 / [Rating] / Online dental
   consultation / Book appointments..."), said "on mobile view, this
   cards are in half" and "ididint like the design so i attached a
   card design to copy and apply it." They then gave a Pinterest link:
   **`https://pin.it/2myZTe5dh`** — I have not opened this and don't
   know what it shows. **First thing tomorrow**: open that link (the
   built-in browser should be able to load it) to see the reference
   design before touching any code. "Cards are in half" most likely
   means the current mobile treatment — one card at ~74vw with a
   visible slice of the second card peeking in from the right edge —
   reads as broken/cut-off rather than as an intentional "swipe to see
   more" affordance. The fix is probably a different mobile card
   layout entirely (full-width single card per view, or a different
   visual style altogether), not just a width tweak — don't guess at
   it without seeing the reference first.

3. **Shrink the info chips further.** "on mobile make this a little
   small 'Verified Clinics / Transparent Pricing' so they can fit well
   in single row." This round already made the chips row
   horizontally scrollable on mobile (§2 above) so they technically
   fit in "a single row" via scroll, but the user apparently wants them
   small enough to **not need scrolling at all** — i.e., shrink
   padding/font/icon size further until all three chips fit within the
   ~343px usable width (375px viewport minus 32px padding) without
   overflow. Do the arithmetic against actual rendered widths (use
   `getBoundingClientRect()`, don't estimate) before committing to a
   size — this was tried once already this session and needed two
   iterations to actually fit (see the em-dash/chip-icon handoff
   entry from earlier today).

4. **Bottom nav needs a notched/cradle shape around the center FAB.**
   The user attached a reference image: a bottom nav bar (Home,
   Explore, [center "+" FAB], Inbox, Profile) where the **bar's own
   shape has a cut-out/notch around the FAB** — the pill's top edge
   curves inward and down around the button, so there's visible empty
   space (you can see through to the page background) framing the FAB
   on both sides, rather than the button simply floating on top of a
   continuous pill. "the design i wantet had a cpace around the middle
   tab check it well and do exactly the same." The current
   implementation (`components/layouts/public.blade.php`, the mobile
   app-nav `<nav>` block) is a plain continuous rounded pill with the
   FAB positioned `-mt-6` on top of it — no notch, no cut-out. This is
   a proper "notched bottom nav" and needs an actual shaped background,
   most practically as an SVG path (a rounded-rect with a scalloped/
   concave arc cut into the top edge) rather than a Tailwind
   border-radius trick, since CSS alone can't cut a concave notch out
   of a convex pill shape. Plan: build the bar as an inline SVG (or a
   `clip-path: path(...)`) sized to the nav's bounding box, with the
   FAB absolutely positioned in the notch. Look at how libraries like
   Flutter's `BottomAppBar` with `shape: CircularNotchedRectangle` or
   common "notched navigation bar" CodePen examples construct the SVG
   path before writing one from scratch.

5. **Audit for duplicate nav tabs.** "make sure no repetition on tabs
   like two tabs all landing to same place." I checked this before
   writing this handoff (`grep -n "route(" components/layouts/public.blade.php`)
   and found **no exact duplicate** inside the mobile bottom-nav bar
   itself — Home, Clinics (`clinics.index`), the Get-Started FAB
   (`register`), More (opens the sheet, not a route), and Login
   (`login`) are all distinct. The closest thing to what the user might
   mean: the bottom nav's "Clinics" tab goes to `clinics.index` (the
   patient-facing clinic directory), while the "More" sheet has a
   separate "For Clinics" item going to `for-clinics` (the marketing
   page aimed at clinic owners) — two *different* destinations with
   confusingly *similar* names, not a technical duplicate. Since this
   part of the message arrived just before the interruption, **I
   couldn't confirm with the user which two tabs they actually mean** —
   ask them directly rather than guessing, since "fix it" could mean
   either renaming for clarity or actually merging/removing one.

## 4. Suggested order for tomorrow

1. Open the Pinterest link (item 2) first — it likely informs both the
   card redesign and possibly gives a general aesthetic direction that
   touches item 4 (the notched nav) too, since both came from the same
   reference-hunting instinct the user is in right now.
2. Ask the user directly which two tabs they mean in item 5 before
   changing navigation structure — don't guess and rename/remove
   something they didn't mean.
3. Then work through 1 (tagline — quick), 3 (chip sizing — quick,
   needs measurement), 2 (card redesign — needs the Pinterest
   reference first), 4 (notched nav — the most involved, needs an SVG
   shape).
4. Re-run the full viewport sweep (375, 768, 1024×768, 1440×900) after
   each change per the pattern established today — this session found
   three real, non-obvious layout bugs (Tailwind arbitrary-value
   class silently failing, absolute-positioning against the wrong
   container, flexbox min-width trap) that a screenshot alone did not
   reveal; keep using `getBoundingClientRect()`/`getComputedStyle()`
   checks alongside screenshots, not instead of them.
5. `php artisan test` + `npm run build` before ending the session, then
   commit and push (the user has asked for this explicitly both times
   so far this project).

## 5. Everything else

No other project state changed this round. See the Sep 15 and the
earlier Sep 17 handoff for full project context, demo accounts, run
instructions, and the Milestone 6 (Administration) work that's still
queued up once the landing page is finally signed off.
