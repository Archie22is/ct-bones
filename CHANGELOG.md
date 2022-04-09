# Changelog

All notable changes to this project will be documented in this file.

## 6.2.9

- Change top and bottom product category content to expand block

## 6.2.8

- Fix product gallery thumbnails

## 6.2.7

- Fix items list on hero cover block

## 6.2.6 - 29/01/2022

- Fix button with prefix class 'is-style-fill'.
- Add default 'text-center' css class to button.
- Update npm package in package.json

## 6.2.5 - 05/01/2022

- Move all checkout functions to public access for child theme removal if needed

## 6.2.4 - 26/12/2021

- Fix spacing post card title
- Move style default content to same ct-bones css

## 6.2.3 - 24/11/2021

- Fix default sticky header variable from Customizer
- Remove link underline in block cover and breadcrumbs
- Fix related posts layout and spacing
- Deprecated support function `wp_is_mobile()`
- Fix header menu spacing, reduce duplicate style
- Fix slideout menu spacing, remove icon toggle background
- Fix duplicate assets
- Fix typography and spacing single product elements
- Add new hook `codetot_before_footer` to close page block section
- Fix footer dark contract css class
- Fix fullwidth page template selection
- Update poppin font for Vietnamese language

## 6.2.2 - 22/11/2021

- Fix archive product image
- Fix button style
- Fix default new grid column width in mobile

## 6.2.1 - 21/11/2021

- Convert hero image to native block, remove require ct-blocks plugin
- Add new function generate grid class
- Fix pagination style
- Add search result count on search page
- Change search result to post list
- Limit search to only post
- Fix widget link style
- Fix image load on archive product page

## 6.2.0 - 20/11/2021

- Convert legacy button to core/button block
- Add same spacing if any .section class has background
- Remove top spacing if .section has background class

## 6.1.3 - 19/11/2021

- Remove top spacing footer copyright
- Move block footer outside of hook
- Fix spacing ul > li, follow vietmoz report
- Format style file typography
- Remove custom radio + checkbox style
- Fix default field row spacing
- Update login form style on frontend

## 6.1.2 - 12/11/2021

- Optimize image block
- Composer fix phpcs standards

## 6.1.1 - 11/11/2021

- Update phpcs and composer packages
- Run compose standards fix automatically
- Fix button outline color css style

## 6.1.0 - 06/11/2021

- Set default container in single product sections
- Fix tabs spacing in single product page
- Fix widget product categories spacing
- Fix sanitize hex color meta key
- Enqueue admin editor styles (to render server side block in Block Editor)

## 6.0.10 - 01/11/2021

- Add unit test for library dom.js (WIP) via npm run test:cov

## 6.0.8 - 6.0.9 - 30/10/2021

- Fix #490: Restore slideout menu, modal search form - iOS render bug with React.

## 6.0.7 - 30/10/2021

- Fix break link in post content

## 6.0.6 - 19/10/2021

- Fix slideout menu trigger to open/collapse sub-menu
- Fix slideout menu style

## 6.0.5 - 19/10/2021

- Fix path load for css, js woocommerce.

## 6.0.4 - 18/10/2021

- Fix embed block with aspect ratio, remove legacy class .video-responsive
- Fix spacing header menu item in desktop

## 6.0.3 - 15/10/2021

- Convert Slideout menu to React component
- Convert Modal search form to React component

## 6.0.2 - 15/10/2021

- Fix missing assets CSS woocommerce.
- Fix ci

## 6.0.1 - 15/10/2021

- Fix editor style with link and content layout.
- Fix video responsive style
- Update build workflow CircleCI

## 6.0.0 - 14/10/2021

- Release first Gutenberg compatitible version.

## 5.7.5 - 11/10/2021

- Fix single post template conflict with other post type in child theme
- Replace default metabox page css class with pure metabox
- Remove top footer spacing toggle in admin ui
- Complete remove Metabox requirement from a theme
- Update security npm packages

## 5.7.4 - 05/10/2021

- Update security patchs npm
- Fix wrong constructor in blocks

## 5.7.3

- Fix top header background contract and background
- Load frontend.min.css to ensure all dependencies load correctly
- Change footer copyright text data to Customize settings
- Fix header 3rd level position
- Fix wrong h1 typography font size in 1200.css

## 5.7.2

- Update global typography scale, add `html` font-size attribute responsive screen.
- Disable check `variables.css` for prettier.

## 5.7.1

- Fix default Customizer settings:

```
post_card_style
footer_column
topbar_column
seo_h1_homepage
```

## 5.0.0 - 5.7.0

- Convert from CT-Theme to Customize Settings.
