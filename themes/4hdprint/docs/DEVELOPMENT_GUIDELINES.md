# 4HD PRINT — Development Guidelines

## Project & Stack
- WordPress and WooCommerce.
- Custom theme: `4hdprint`.
- PHP, JavaScript, CSS, and WordPress/WooCommerce hooks.
- Development environment: LocalWP; deployment environment may differ.

## Core Principles
- Preserve the existing visual identity, theme architecture, and working behavior.
- Prefer the smallest maintainable change over a broad rewrite.
- Reuse existing functions, components, selectors, and CSS custom properties.
- Follow WordPress coding standards and WooCommerce conventions.
- Make one logical change at a time; verify before proceeding.
- Do not assume code or files that have not been provided or inspected.

## Visual Identity & CSS
- Primary orange: `#f47721` (verify the actual `--main-color` value in the current theme before changing it).
- Black: `#151515` (`--black`, where defined).
- Reuse the project's existing variables and selector naming.
- Avoid duplicated declarations, competing media queries, and unnecessary specificity.
- Preserve desktop layouts while refining tablet/mobile breakpoints.
- Prefer modifying an existing relevant rule or media query to adding an override elsewhere.
- Check header, navigation, breadcrumbs, cart indicator, and WooCommerce views for regressions.
- Keep accessible focus states, touch target sizes, contrast, and keyboard navigation.

## PHP / WordPress / WooCommerce
- Do not remove or replace WordPress/WooCommerce hooks without explaining the consequences.
- Escape output, sanitize input, and use nonces and capability checks where applicable.
- Preserve product variations, cart/checkout, customizer, uploads, pricing, and order metadata.
- Verify PHP syntax before recommending PHP changes; avoid edits that could cause a critical error.
- Keep theme behavior in the theme and plugin-specific functionality in the appropriate plugin.

## JavaScript
- Reuse existing event handlers and state management.
- Avoid duplicated listeners and DOM elements.
- Keep desktop and mobile interactions consistent unless a deliberate difference is required.
- Check menu open/close, focus behavior, and responsive interactions after changes.

## Change Workflow
1. Ask for the relevant current code and, when helpful, a screenshot at the affected viewport.
2. Identify the exact file, selectors/functions, breakpoints, and potential conflicts.
3. Explain the simplest proposed fix before implementing it.
4. Specify the exact file and section to edit.
5. Provide a focused replacement or patch, not an unnecessary full-file rewrite.
6. Explain how to test the change on mobile and desktop.
7. Wait for confirmation before starting the next change.

## Change Response Format
- **Diagnosis:** what is happening and why.
- **Conflict/risk:** which existing rules or behaviors might be affected.
- **Proposed change:** minimal solution.
- **File and section:** precise location.
- **Code:** only after inspecting the relevant source.
- **Verification:** short reproducible checklist.

## Safety & Version Control
- Create a backup or commit before risky changes.
- Do not edit unrelated files.
- Do not claim a local change has been applied unless it actually has.
- If required context is missing, request it before proposing implementation.
- Record any intentional exceptions to these guidelines.
