# Quillmoor

A WordPress site for a garden design studio, built by [WestchesterAI](https://westchesterai.com/for-agencies) as a portfolio piece. Quillmoor is fictional and its photographs are generated concept images.

It shows what an agency gets when it hands over a finished design: a real WordPress site the client can edit by logging in, with no paid page builder.

**[Open the live demo](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/Alexandercolombo/quillmoor-wordpress/main/blueprint.json)**. It runs a full copy of WordPress in your browser, so give it a moment to start. You are logged in, so you can open the dashboard and edit anything. Nothing you change is saved.

## What's in here

- `wp-content/themes/quillmoor`: a block theme. Colors and type live in `theme.json`, every page edits in the standard WordPress editor, and fonts are self-hosted.
- `wp-content/plugins/quillmoor-core`: content that should survive a theme change.
  - Garden projects, a custom post type with a plain details panel in the editor. Template fields use core block bindings.
  - Site-visit requests, saved to the dashboard and emailed to the site admin, with a spam honeypot and inline form errors.
  - Four custom blocks: season strip, twelve-month planting chart, site-visit town picker and site-visit form. No build step.

## Try it locally

Copy the theme and plugin into any WordPress 6.6 or newer install and activate both. To load the sample content:

```bash
wp eval-file wp-content/plugins/quillmoor-core/demo/seed.php
```

Run that on a fresh install only.

## License

GPL-2.0-or-later, like WordPress. Bricolage Grotesque and Gelasio are licensed under the SIL Open Font License.
