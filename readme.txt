=== Design System for Elementor ===
Contributors: ramiy
Tags: elementor, design system, design.md, design tokens, ai
Stable tag: 1.0.0
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Requires Plugins: elementor
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Expose your Elementor Design System to external AI tools, so AI-generated pages stay on-brand.

== Description ==

**Keep your design system when redesigning with AI.**

Modern web design has changed. Advanced users no longer redesign pages by hand — they prompt an external AI tool like [Google Stitch](https://stitch.withgoogle.com/), v0, Lovable, Figma AI, ChatGPT, Claude, or Cursor to generate entire pages, landing sections, or even complete website redesigns in seconds.

But those AI tools don't know **your** brand. For instance, ask Stitch to "redesign my pricing page" and you'll get a beautiful, generic, off-brand page that ignores your colors, your typography, your buttons, your spacing — everything that makes your website look like _your site_.

This plugin fixes that. It converts your Elementor **Site Settings** (Global Colors, Global Fonts, spacing, border radius, buttons, headings, and more) into the [design.md schema](https://github.com/google-labs-code/design.md) — the emerging standard for expressing a design system to AI agents — and serves it at `https://yoursite.com/design.md` as a portable, machine-readable representation of your Design System.

Point Stitch (or any other AI tool) at your `design.md` URL, and the AI now designs **with** your brand instead of ignoring it. Same design system, same tokens, same components — every generated page fits your existing website.

**This plugin does not change your Elementor design.** It's a one-way, read-only bridge: it only helps external AI tools understand and respect your existing Elementor design system. It doesn't touch your Kit, your pages, or your styles — and it doesn't import anything back into Elementor either.

The result: brand consistency across every external tool you use. Colors, fonts, spacing, border radius, button styles — all stay aligned with your live website, without manual copy-paste into every AI prompt.

**Why Design.md?**

* **AI redesigns stay on-brand** — colors, typography, spacing, button styles all flow through automatically.
* **One source of truth** — your Elementor Kit already defines your design system. The plugin just exposes it in a format AI understands.
* **Zero manual sync** — every change to Site Settings is reflected instantly. No exporting, no copy-paste, no drift.
* **Portable** — the `/design.md` URL works with any AI tool, editor, or agent that accepts a link or file.
* **Full-site or per-page ready** — expose the whole website's design system today; per-page tokens on the roadmap.

**What it's used for:**

* **AI-driven page redesigns** — paste your `/design.md` URL into Google Stitch, v0, Lovable, or similar to generate a new landing page that matches your existing design system.
* **New page generation** — spin up on-brand pricing pages, about pages, blog templates, or landing pages without breaking visual consistency.
* **AI coding agents** — feed `design.md` to Cursor, Claude Code, GitHub Copilot, or ChatGPT so generated components use your real brand tokens instead of placeholder colors.
* **Design system documentation** — hand designers and developers a live, always-up-to-date reference of the tokens actually in production.
* **Design handoff to external teams** — share one URL instead of a Figma file, a style guide PDF, and a Slack thread of "wait, what's the exact hex again?"
* **Cross-tool consistency** — the same design tokens power the AI in your prompts, the code your dev team writes, and the pages your Elementor builds.

**Works with:**

Any AI tool that accepts a URL, file upload, or pasted text — including [Google Stitch](https://stitch.withgoogle.com/), v0, Lovable, Figma AI, ChatGPT, Claude, Cursor, GitHub Copilot, Windsurf, and Bolt. If a tool can read a Markdown file, it can read your design system.

**What's included in the output:**

* Site name and description
* Global color tokens (system colors + custom colors)
* Global typography tokens (system fonts + custom fonts)
* Body, heading (h1–h6), link, and button component styles
* Layout styles
* Button style tokens

**Example output:**

    ---
    name: "My Site"
    description: "An example Elementor website"
    colors:
      primary: "#000000"
      secondary: "#54595F"
      text: "#7A7A7A"
      accent: "#000000"
    typography:
      primary:
        fontFamily: "Roboto"
        fontWeight: 600
      secondary:
        fontFamily: "Roboto Slab"
        fontWeight: 400
    components:
      button-primary:
        backgroundColor: "#196CFF"
      button-primary-hover:
        backgroundColor: "#FD0000"
    ---

    ## Overview

    An example Elementor website

    ## Colors

    - **primary** (#000000): Primary
    - **secondary** (#54595F): Secondary

    ## Typography

    - **primary:** Roboto, weight 600
    - **secondary:** Roboto Slab, weight 400

    ## Components

    ### button-primary

    - **backgroundColor:** `#196CFF`

    ### button-primary-hover

    - **backgroundColor:** `#FD0000`

**Customizing the output:**

You can modify the Elementor "Site Settings" or modify the generated output using the `design_system_for_elementor` filter hook:

    add_filter( 'design_system_for_elementor', function( $output, $kit ) {
        // Modify $output string here.
        return $output;
    }, 10, 2 );

**What this plugin does _not_ do:**

* It does **not** import designs generated in external AI tools back into Elementor. The flow is one-way: Elementor → external AI. Bringing an AI-generated layout into Elementor is still a manual (or separate-tool) job.
* It does **not** modify your Kit, your pages, your CSS, or anything else on your website.
* It does **not** invent a design system. It only exposes what you've already defined in Elementor Site Settings.

**When this plugin helps most (and when it doesn't):**

This plugin is designed for **existing websites with a customized design system** — websites that already have real brand decisions baked into Elementor. It's most valuable when you're redesigning or extending an existing website and need external AI tools to stay on-brand.

You'll get the most value if your website:

* Uses **Global Colors** (instead of hard-coded hex values on every widget).
* Uses **Global Fonts** (instead of one-off typography per widget).
* Has **customized headings, buttons, spacing, or border radius** in Site Settings.

You'll get little to no value if your website:

* Doesn't use Global Colors or Global Fonts.
* Hasn't customized its heading structure or typography.
* Uses the **default Elementor Kit** as-is, without brand adjustments.
* Is a **brand new website** with no design system decisions made yet — in that case, define your Site Settings first, then use this plugin.

In short: the richer your Elementor Site Settings, the more useful your `design.md` becomes.

**Privacy / Security note:**

The `/design.md` endpoint is publicly accessible and unauthenticated. It exposes the same design system your website already ships in its CSS stylesheet. No user data, passwords, or private content are exposed.

== Frequently Asked Questions ==

= How do I use this with Google Stitch? =

Copy your `https://yoursite.com/design.md` URL and reference it in your Stitch prompt (for example: "Redesign my pricing page using the design system at https://yoursite.com/design.md"). Stitch will read the file and produce a design that uses your colors, typography, and component styles instead of generic defaults.

= Will this change how my website looks? =

No. The plugin creates a read-only endpoint for `/design.md` file. It only exposes what your Elementor Kit already contains — it never modifies your website, your Kit, or any page. Uninstalling the plugin leaves your website exactly as it was.

= Can I use this to import an AI-generated design back into Elementor? =

No. This plugin is one-way only: it exports your Elementor design system so external AI tools can respect it. Bringing an AI-generated layout back into Elementor is not something this plugin does.

= I have a brand new website. Should I install this? =

Not yet. This plugin is most useful for existing websites with a customized design system (Global Colors, Global Fonts, custom headings, buttons, spacing). If you're starting fresh, define your Elementor Site Settings first — then install this plugin so external AI tools can build on top of your decisions.

= Does this plugin expose sensitive data? =

No. The `/design.md` endpoint only exposes design tokens (colors, typography, spacing, component styles) that your website already sends to every visitor through its CSS. No user data, authentication credentials, or private content are included.

= Why does `/design.md` return a 404 after activation? =

Go to **Settings → Permalinks** and click **Save Changes**. This flushes WordPress rewrite rules and registers the new route. The plugin does this automatically on activation, but some hosting setups or caching layers may interfere.

= Can I use this with a WordPress subdirectory install? =

Yes. The plugin uses `home_url()` for path detection, so it is subdirectory-aware.

= Can I customize the output? =

Yes, use the `design_system_for_elementor` filter hook to modify the output. It was designed for external plugins that extend the Elementor Site Settings, and want to expose the new data to external agents.

= Does this work with Elementor Pro? =

Yes. It reads from the active Kit, which works with both Elementor Core and Elementor Pro.

== Changelog ==

= 1.0.0 =
* Initial release.
* Generates a `design.md` file from Elementor's active Kit (Site Settings).
* Exposes colors, typography, spacing, and component tokens.
